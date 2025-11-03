<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SalesSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        $stores = $db->table('stores')->select('id, store_code')->orderBy('id')->get()->getResultArray();
        $products = $db->table('products')->select('id, name, unit, selling_price, tax_included')->orderBy('id')->get()->getResultArray();
        if (empty($stores) || empty($products)) {
            return;
        }

        $taxRate = (new \App\Config\Pos())->taxRate;
        $methods = ['cash','debit','qris'];

        foreach ($stores as $store) {
            $registers = $db->table('registers')->select('id, register_code')->where('store_id', $store['id'])->get()->getResultArray();
            if (empty($registers)) { continue; }
            $staffs = $db->table('staffs')->select('id')->where('store_id', $store['id'])->get()->getResultArray();

            for ($i = 0; $i < 5; $i++) {
                $reg = $registers[array_rand($registers)];
                $staffId = !empty($staffs) ? $staffs[array_rand($staffs)]['id'] : null;

                // Random date/time this month
                $day = rand(1, (int) date('t'));
                $saleTime = sprintf('%04d-%02d-%02d %02d:%02d:%02d', (int)date('Y'), (int)date('m'), $day, rand(8, 20), rand(0,59), rand(0,59));
                $saleAt = Time::parse($saleTime);

                // Build receipt: STORECODE/MMYYYY/NNNN
                $prefix = strtoupper($store['store_code']) . '/' . $saleAt->format('mY') . '/';
                $last = $db->table('sales')->select('receipt_no')
                    ->where('store_id', $store['id'])
                    ->like('receipt_no', $prefix, 'after')
                    ->orderBy('id','DESC')->get(1)->getFirstRow('array');
                $seq = 1;
                if ($last) { $parts = explode('/', $last['receipt_no']); $lastSeq = (int) end($parts); if ($lastSeq > 0) { $seq = $lastSeq + 1; } }
                do {
                    $receipt = $prefix . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
                    $exists = (int) $db->table('sales')->where('receipt_no', $receipt)->countAllResults();
                    if ($exists === 0) break;
                    $seq++;
                } while (true);

                // Cari produk yang punya promo aktif pada waktu transaksi
                $eligibleRows = $db->table('promos')->select('promoitems.product_id, promos.id, promos.promo_code, promos.type, promos.value')
                    ->join('promoitems','promoitems.promo_id = promos.id','inner')
                    ->where('promos.store_id', $store['id'])
                    ->where('promos.start_datetime <=', $saleAt->toDateTimeString())
                    ->where('promos.end_datetime >=', $saleAt->toDateTimeString())
                    ->get()->getResultArray();
                $eligibleMap = [];
                foreach ($eligibleRows as $er) { $eligibleMap[(int)$er['product_id']] = $er; }
                $eligiblePids = array_keys($eligibleMap);

                // Pilih 1-3 item. Usahakan sebagian transaksi mengandung minimal 1 item promo
                $numItems = rand(1, min(3, count($products)));
                $selectedPids = [];
                if (!empty($eligiblePids) && rand(0, 100) < 70) { // 70% sales mengandung promo
                    $selectedPids[] = $eligiblePids[array_rand($eligiblePids)];
                }
                while (count($selectedPids) < $numItems) {
                    $pid = $products[array_rand($products)]['id'];
                    if (!in_array($pid, $selectedPids, true)) { $selectedPids[] = $pid; }
                }

                $totals = [ 'total_items'=>0, 'subtotal'=>0.0, 'discount_total'=>0.0, 'tax_total'=>0.0, 'grand_total'=>0.0 ];
                $itemRows = [];
                foreach ($selectedPids as $pidSel) {
                    // ambil data produk dari cache array
                    $p = null;
                    foreach ($products as $pp) { if ($pp['id'] == $pidSel) { $p = $pp; break; } }
                    if (!$p) { continue; }
                    $qty = rand(1,3);
                    $price = (float) $p['selling_price'];
                    $base  = $qty * $price;

                    // Gunakan promo jika tersedia (70% chance utk line ini jika eligible)
                    $promo = $eligibleMap[$p['id']] ?? null;
                    $usePromo = $promo && (rand(0,100) < 70);

                    $discount = 0.0; $promoId = null; $promoCode = null;
                    if ($usePromo) {
                        if ($promo['type'] === 'percent') { $discount = $base * ((float)$promo['value']/100); }
                        elseif ($promo['type'] === 'fixed') { $discount = (float)$promo['value'] * $qty; }
                        $promoId = $promo['id']; $promoCode = $promo['promo_code'];
                    }

                    $taxable = max(0, $base - $discount);
                    if ($p['tax_included']) { $tax = $taxable * ($taxRate/(1+$taxRate)); $lineTotal = $taxable; }
                    else { $tax = $taxable * $taxRate; $lineTotal = $taxable + $tax; }

                    $totals['total_items'] += (int) round($qty);
                    $totals['subtotal']    += $base;
                    $totals['discount_total'] += $discount;
                    $totals['tax_total']   += $tax;
                    $totals['grand_total'] += $lineTotal;

                    $itemRows[] = [
                        'product_id' => $p['id'],
                        'product_name' => $p['name'],
                        'unit' => $p['unit'],
                        'qty' => $qty,
                        'price' => $price,
                        'discount' => round($discount,2),
                        'tax' => round($tax,2),
                        'line_total' => round($lineTotal,2),
                        'applied_promo_id' => $promoId,
                        'applied_promo_code' => $promoCode,
                    ];
                }

                $grand = round($totals['grand_total'], 2);
                $method = $methods[array_rand($methods)];
                $amountPaid = $grand; // mark as paid for seed
                $changeDue = 0.0;
                $status = 'paid';

                // Insert sale
                $saleId = $db->table('sales')->insert([ 
                    'store_id' => $store['id'],
                    'register_id' => $reg['id'],
                    'staff_id' => $staffId,
                    'receipt_no' => $receipt,
                    'sale_datetime' => $saleAt->toDateTimeString(),
                    'total_items' => $totals['total_items'],
                    'subtotal' => round($totals['subtotal'],2),
                    'discount_total' => round($totals['discount_total'],2),
                    'tax_total' => round($totals['tax_total'],2),
                    'grand_total' => $grand,
                    'amount_paid' => $amountPaid,
                    'change_due' => $changeDue,
                    'status' => $status,
                    'payment' => $method,
                    'created_at' => Time::now(),
                    'updated_at' => Time::now(),
                ], true);

                $saleId = $db->insertID();
                foreach ($itemRows as $row) {
                    $row['sale_id'] = $saleId;
                    $row['created_at'] = Time::now();
                    $row['updated_at'] = Time::now();
                    $db->table('saleitems')->insert($row);
                }
            }
        }
    }
}

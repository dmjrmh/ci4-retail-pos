<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sales extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
      'store_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
      'register_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
      'staff_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
      'receipt_no'     => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => false],
      'sale_datetime'  => ['type' => 'DATETIME', 'null' => false],
      'total_items'    => ['type' => 'INT', 'null' => false, 'default' => 0],
      'subtotal'       => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'discount_total' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'tax_total'      => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'grand_total'    => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'amount_paid'    => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'change_due'     => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'status'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false, 'default' => 'unpaid'],
      'payment'        => ['type' => 'VARCHAR', 'constraint' => 20],

      'created_at' => ['type' => 'DATETIME', 'null' => true],
      'updated_at' => ['type' => 'DATETIME', 'null' => true],
      'deleted_at' => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addKey('receipt_no');
    $this->forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('register_id', 'registers', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('staff_id', 'staffs', 'id', 'SET NULL', 'CASCADE');
    $this->forge->createTable('sales');
  }

  public function down()
  {
    $this->forge->dropTable('sales', true);
  }
}

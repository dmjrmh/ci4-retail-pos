<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SaleItems extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
      'sale_id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
      'product_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
      'product_name'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
      'unit'                => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
      'qty'                 => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'price'               => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'discount'            => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'tax'                 => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'line_total'          => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00'],
      'applied_promo_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
      'applied_promo_code'  => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],

      'created_at' => ['type' => 'DATETIME', 'null' => true],
      'updated_at' => ['type' => 'DATETIME', 'null' => true],
      'deleted_at' => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('sale_id', 'sales', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'RESTRICT');
    $this->forge->addForeignKey('applied_promo_id', 'promos', 'id', 'SET NULL', 'CASCADE');
    $this->forge->createTable('saleitems');
  }

  public function down()
  {
    $this->forge->dropTable('saleitems', true);
  }
}


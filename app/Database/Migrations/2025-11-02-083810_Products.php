<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Products extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id' => [
        'type'           => 'INT',
        'constraint'     => 5,
        'unsigned'       => true,
        'auto_increment' => true,
      ],
      'name' => [
        'type'          => 'VARCHAR',
        'constraint'    => '50',
        'null'          => false,
      ],
      'cover' => [
        'type'          => 'VARCHAR',
        'constraint'    => '255',
        'null'          => true,
      ],
      'unit' => [
        'type'          => 'VARCHAR',
        'constraint'    => '20',
      ],
      'cost_price' => [
        'type'           => 'DECIMAL',
        'constraint'     => '10,2',
        'default'        => 0.00,
      ],
      'selling_price' => [
        'type'           => 'DECIMAL',
        'constraint'     => '10,2',
        'default'        => 0.00,
      ],
      'tax_included' => [
        'type'           => 'BOOLEAN',
        'default'        => true,
      ],
      'created_at' => ['type' => 'DATETIME', 'null' => true,],
      'updated_at' => ['type' => 'DATETIME', 'null' => true,],
      'deleted_at' => ['type' => 'DATETIME', 'null' => true,]
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('products');
  }

  public function down()
  {
    $this->forge->dropTable('products');
  }
}

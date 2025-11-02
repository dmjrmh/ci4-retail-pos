<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Registers extends Migration
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
      'store_id' => [
        'type'           => 'INT',
        'constraint'     => 5,
        'unsigned'       => true,
      ],
      'name' => [
        'type'           => 'VARCHAR',
        'constraint'     => '50'
      ],
      'register_code' => [
        'type'           => 'VARCHAR',
        'constraint'     => '20',
        'null'           => false,
      ],
      'is_active' => [
        'type'           => 'BOOLEAN',
        'default'        => true,
      ],
      'created_at' => [ 'type' => 'DATETIME', 'null' => true, ],
      'updated_at' => [ 'type' => 'DATETIME', 'null' => true, ],
      'deleted_at' => [ 'type' => 'DATETIME', 'null' => true, ]
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('registers');
  }

  public function down()
  {
    $this->forge->dropTable('registers');
  }
}

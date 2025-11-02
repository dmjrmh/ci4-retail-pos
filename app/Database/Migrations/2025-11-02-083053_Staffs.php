<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Staffs extends Migration
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
      'staff_code' => [
        'type'          => 'VARCHAR',
        'constraint'    => '20',
      ],
      'name' => [
        'type'          => 'VARCHAR',
        'constraint'    => '50',
        'null'          => false,
      ],
      'position' => [
        'type'          => 'VARCHAR',
        'constraint'    => '50',
      ],
      'phone' => [
        'type'          => 'VARCHAR',
        'constraint'    => '20',
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
    $this->forge->createTable('staffs');
  }

  public function down()
  {
    $this->forge->dropTable('staffs');
  }
}

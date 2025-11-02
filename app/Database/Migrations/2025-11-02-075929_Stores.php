<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Stores extends Migration
{
    public function up()
    {
      $this->forge->addField([
        'id' => [
          'type' => 'INT',
          'constraint' => 11,
          'unsigned' => true,
          'auto_increment' => true,
        ], 
        'store_name' => [
          'type' => 'VARCHAR',
          'constraint' => '100',
          'null' => false,
        ],
        'store_code' => [
          'type' => 'VARCHAR',
          'constraint' => '20',
          'null' => false,
        ],
        'address' => [
          'type' => 'TEXT',
          'null' => true,
        ],
        'city' => [
          'type' => 'VARCHAR',
          'constraint' => '50',
          'null' => true,
        ],
        'is_active' => [
          'type' => 'BOOLEAN',
          'default' => true,
        ],
        'created_at' => [
          'type' => 'DATETIME',
          'null' => true,
        ],
        'updated_at' => [
          'type' => 'DATETIME',
          'null' => true,
        ], 
        'deleted_at' => [
          'type' => 'DATETIME',
          'null' => true,
        ]
      ]);
      $this->forge->addKey('id', true);
      $this->forge->createTable('stores');
    }

    public function down()
    {
      $this->forge->dropTable('stores');
    }
}

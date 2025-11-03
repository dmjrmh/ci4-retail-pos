<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Promos extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
      'store_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
      'name'         => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => false],
      'promo_code'   => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => false],
      'type'         => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => false], 
      'value'        => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false],

      'start_datetime' => ['type'=>'DATETIME','null'=>false],
      'end_datetime'   => ['type'=>'DATETIME','null'=>false],

      'created_at'   => ['type' => 'DATETIME', 'null' => true],
      'updated_at'   => ['type' => 'DATETIME', 'null' => true],
      'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey('promo_code');
    $this->forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('promos');
  }

  public function down()
  {
    $this->forge->dropTable('promos');
  }
}

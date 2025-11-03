<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PromoStores extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
      'promo_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
      'store_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],

      'created_at' => ['type' => 'DATETIME', 'null' => true],
      'updated_at' => ['type' => 'DATETIME', 'null' => true],
      'deleted_at' => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey(['promo_id', 'store_id']);
    $this->forge->addForeignKey('promo_id', 'promos', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('promostores');
  }

  public function down()
  {
    $this->forge->dropTable('promostores', true);
  }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PromoItems extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
      'promo_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
      'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],

      'created_at' => ['type' => 'DATETIME', 'null' => true],
      'updated_at' => ['type' => 'DATETIME', 'null' => true],
      'deleted_at' => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey(['promo_id', 'product_id']);
    $this->forge->addForeignKey('promo_id', 'promos', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('promoitems');
  }

  public function down()
  {
    $this->forge->dropTable('promoitems', true);
  }
}

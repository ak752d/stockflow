<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'sku' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'line_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['order_id', 'product_id']);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('order_items');
    }

    public function down(): void
    {
        $this->forge->dropTable('order_items');
    }
}

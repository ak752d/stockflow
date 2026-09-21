<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
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
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'sku' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'stock_quantity' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'low_stock_threshold' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 5,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('sku');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('products');
    }

    public function down(): void
    {
        $this->forge->dropTable('products');
    }
}

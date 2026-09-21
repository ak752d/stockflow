<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->db->table('products')->countAllResults() > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');

        $this->db->table('categories')->insertBatch([
            ['name' => 'Laptops', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Monitors', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Accessories', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Storage', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->db->table('products')->insertBatch([
            ['category_id' => 1, 'name' => 'ThinkBook 14', 'sku' => 'NB-TB14', 'price' => '54990.00', 'stock_quantity' => 18, 'low_stock_threshold' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 1, 'name' => 'Inspiron 15', 'sku' => 'NB-IN15', 'price' => '47990.00', 'stock_quantity' => 4, 'low_stock_threshold' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 1, 'name' => 'MacBook Air 13', 'sku' => 'NB-MBA13', 'price' => '99990.00', 'stock_quantity' => 7, 'low_stock_threshold' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 2, 'name' => 'Dell 24 FHD', 'sku' => 'MN-DL24', 'price' => '12490.00', 'stock_quantity' => 25, 'low_stock_threshold' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 2, 'name' => 'LG UltraWide 34', 'sku' => 'MN-LG34', 'price' => '38990.00', 'stock_quantity' => 2, 'low_stock_threshold' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 3, 'name' => 'Logitech MX Keys', 'sku' => 'AC-MXK', 'price' => '8990.00', 'stock_quantity' => 40, 'low_stock_threshold' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 3, 'name' => 'Logitech MX Master 3S', 'sku' => 'AC-MXM', 'price' => '7990.00', 'stock_quantity' => 3, 'low_stock_threshold' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 3, 'name' => 'USB-C Hub 7-in-1', 'sku' => 'AC-HUB7', 'price' => '2490.00', 'stock_quantity' => 60, 'low_stock_threshold' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 4, 'name' => 'Samsung 1TB NVMe', 'sku' => 'ST-NV1T', 'price' => '6990.00', 'stock_quantity' => 22, 'low_stock_threshold' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => 4, 'name' => 'WD 2TB External', 'sku' => 'ST-WD2T', 'price' => '5990.00', 'stock_quantity' => 1, 'low_stock_threshold' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->db->table('orders')->insertBatch([
            ['user_id' => 1, 'customer_name' => 'Priya Sharma', 'customer_email' => 'priya@example.com', 'customer_phone' => '9876500011', 'status' => 'delivered', 'total' => '62980.00', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'customer_name' => 'Rahul Mehta', 'customer_email' => 'rahul@example.com', 'customer_phone' => '9876500022', 'status' => 'shipped', 'total' => '16980.00', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'customer_name' => 'Anita Desai', 'customer_email' => 'anita@example.com', 'customer_phone' => '9876500033', 'status' => 'confirmed', 'total' => '99990.00', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'customer_name' => 'Vikram Rao', 'customer_email' => 'vikram@example.com', 'customer_phone' => '9876500044', 'status' => 'pending', 'total' => '10480.00', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'customer_name' => 'Neha Kapoor', 'customer_email' => 'neha@example.com', 'customer_phone' => '9876500055', 'status' => 'cancelled', 'total' => '38990.00', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->db->table('order_items')->insertBatch([
            ['order_id' => 1, 'product_id' => 1, 'product_name' => 'ThinkBook 14', 'sku' => 'NB-TB14', 'quantity' => 1, 'unit_price' => '54990.00', 'line_total' => '54990.00'],
            ['order_id' => 1, 'product_id' => 6, 'product_name' => 'Logitech MX Keys', 'sku' => 'AC-MXK', 'quantity' => 1, 'unit_price' => '7990.00', 'line_total' => '7990.00'],
            ['order_id' => 2, 'product_id' => 4, 'product_name' => 'Dell 24 FHD', 'sku' => 'MN-DL24', 'quantity' => 1, 'unit_price' => '12490.00', 'line_total' => '12490.00'],
            ['order_id' => 2, 'product_id' => 8, 'product_name' => 'USB-C Hub 7-in-1', 'sku' => 'AC-HUB7', 'quantity' => 1, 'unit_price' => '2490.00', 'line_total' => '2490.00'],
            ['order_id' => 2, 'product_id' => 7, 'product_name' => 'Logitech MX Master 3S', 'sku' => 'AC-MXM', 'quantity' => 1, 'unit_price' => '1999.00', 'line_total' => '1999.00'],
            ['order_id' => 3, 'product_id' => 3, 'product_name' => 'MacBook Air 13', 'sku' => 'NB-MBA13', 'quantity' => 1, 'unit_price' => '99990.00', 'line_total' => '99990.00'],
            ['order_id' => 4, 'product_id' => 9, 'product_name' => 'Samsung 1TB NVMe', 'sku' => 'ST-NV1T', 'quantity' => 1, 'unit_price' => '6990.00', 'line_total' => '6990.00'],
            ['order_id' => 4, 'product_id' => 8, 'product_name' => 'USB-C Hub 7-in-1', 'sku' => 'AC-HUB7', 'quantity' => 1, 'unit_price' => '2490.00', 'line_total' => '2490.00'],
            ['order_id' => 4, 'product_id' => 6, 'product_name' => 'Logitech MX Keys', 'sku' => 'AC-MXK', 'quantity' => 1, 'unit_price' => '1000.00', 'line_total' => '1000.00'],
            ['order_id' => 5, 'product_id' => 5, 'product_name' => 'LG UltraWide 34', 'sku' => 'MN-LG34', 'quantity' => 1, 'unit_price' => '38990.00', 'line_total' => '38990.00'],
        ]);
    }
}

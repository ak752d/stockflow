<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table          = 'order_items';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps  = false;
    protected $allowedFields  = [
        'order_id',
        'product_id',
        'product_name',
        'sku',
        'quantity',
        'unit_price',
        'line_total',
    ];
}

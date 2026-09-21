<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table          = 'products';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields  = [
        'category_id',
        'name',
        'sku',
        'price',
        'stock_quantity',
        'low_stock_threshold',
        'image',
    ];
    protected $useTimestamps  = true;
}

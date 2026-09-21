<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'total',
    ];
    protected $useTimestamps = true;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOrderDetail extends Model
{
     use HasFactory;

    // Define the table name if not following Laravel's naming convention
    protected $table = 'store_order_detail';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'store_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    /**
     * Define the relationship with StoreOrder
     */
    public function order()
    {
        return $this->belongsTo(StoreOrder::class, 'store_order_id');
    }
    public function product()
{
    return $this->belongsTo(Products::class, 'product_id', 'product_id');
}

}

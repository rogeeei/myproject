<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id'; 

    protected $fillable = [
        'order_date',
        'total_amount',
        'customer_id',
        'store_id',
        'brand_id',
        'cashier_id',
    ];

    // Define relationships

    // Order belongs to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // Order belongs to Store
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    // Order belongs to Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    // Order belongs to Cashier
    public function cashier()
    {
        return $this->belongsTo(Cashier::class, 'cashier_id', 'cashier_id');
    }
}

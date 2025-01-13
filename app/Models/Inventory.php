<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Inventory extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'inventory';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'inventory_id';

    // Define the attributes that can be mass-assigned
    protected $fillable = [
        'stock_quantity',
        'reorder_level',
        'reorder_quantity',
        'product_id',
        'store_id',
    ];

    // Define relationships

    /**
     * The product associated with the inventory.
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }

    /**
     * The store associated with the inventory.
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }
}

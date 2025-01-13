<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderApproval extends Model
{
     use HasFactory;

    // The table associated with the model
    protected $table = 'order_approval';

    // The attributes that are mass assignable
    protected $fillable = [
        'store_order_id',
        'vendor_id',
        'is_approved',
    ];

    // Define relationships (if needed)
    public function storeOrder()
    {
        return $this->belongsTo(StoreOrder::class, 'store_order_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
   use HasFactory;

    // Define the table name if not following Laravel's naming convention
    protected $table = 'store_order';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'store_order_id';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'order_date',
        'total_amount',
        'payment_method',
        'vendor_id',
        'store_id',
        
    ];

    /**
     * Define the relationship with StoreOrderDetail
     */
    public function details()
    {
        return $this->hasMany(StoreOrderDetail::class, 'store_order_id');
    }
}

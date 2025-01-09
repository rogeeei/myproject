<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'store';

    protected $primaryKey = 'store_id';

    protected $fillable = [
    'store_name', 
    'store_address', 
    'operating_hours',
    'user_id',
    'image_path',
];


    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'store_id', 'store_id');
    // }

    // public function orders()
    // {
    //     return $this->hasMany(Order::class, 'store_id', 'store_id');
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $table = 'product_type';

    protected $primaryKey = 'product_type_id';

    protected $fillable = [
    'name', 
    'parent_type_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentType extends Model
{
    use HasFactory;

     protected $table = 'parent_type';

    protected $primaryKey = 'parent_type_id';

    protected $fillable = [
    'name', 
    'store_id',
    ];
}

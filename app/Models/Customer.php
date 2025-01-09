<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
     use HasFactory;

    // Specify the table name
    protected $table = 'customer';

    // Specify the primary key column
    protected $primaryKey = 'customer_id';

    // Indicate if the primary key is auto-incrementing
    public $incrementing = true;

    // Specify the primary key data type
    protected $keyType = 'int';

    // Allow mass assignment for these columns
    protected $fillable = [
        'name',
        'email',
        'contact_no',
        'address',
        'frequent_shopper',
    ];
}

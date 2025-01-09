<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
        use HasFactory;

    // Specify the table name if it differs from the pluralized version of the model name
    protected $table = 'cashier';

    // Specify the primary key column
    protected $primaryKey = 'cashier_id';

    // Indicate if the primary key is auto-incrementing (default is true)
    public $incrementing = true;

    // Specify the primary key data type
    protected $keyType = 'int';

    // Allow mass assignment for these columns
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_no',
        'address',
        'store_id',
    ];

    // Define the relationship to the Store model
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;  
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
     use HasApiTokens, HasFactory, Notifiable; 

    protected $table = 'vendor';

    protected $primaryKey = 'vendor_id';

    protected $fillable = [
    'name', 
    'email',
    'contact_no',
    'address',
    'password',
    'password_confirmation'
    ];
}

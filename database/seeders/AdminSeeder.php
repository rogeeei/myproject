<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User; 

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use the User model instead of DB::table
        User::updateOrCreate(
            ['username' => 'admin001'], // Match by unique field
            [
                'name' => 'Admin',
                'address' => null, // Nullable fields
                'contact_no' => null,
                'email' => null,
                'password' => Hash::make('securepassword123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
    
}

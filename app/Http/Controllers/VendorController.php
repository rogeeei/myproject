<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Http\Requests\VendorRequest;

class VendorController extends Controller
{
    /**
     * Store a newly created vendor in storage.
     */
    public function store(VendorRequest $request)
{
    // Validate the incoming request through VendorRequest class
    $validated = $request->validated();

    // Check if 'name' exists in validated data
    if (!array_key_exists('name', $validated)) {
        return response()->json(['message' => 'Name is required.'], 400);
    }

    // Create a new Vendor record
    $vendor = Vendor::create([
        'name'       => $validated['name'],
        'address'    => $validated['address'],
        'email'      => $validated['email'],
        'password'   => bcrypt($validated['password']),
        'contact_no' => $validated['contact_no'],
    ]);

    // Return a response
    return response()->json([
        'message' => 'Vendor created successfully',
        'vendor'  => $vendor,
    ], 201);
}

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
     /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'brand_name' => 'required|string|max:255',
        ]);

        // Create a new brand record
        $brand = Brand::create([
            'brand_name' => $request->brand_name,
        ]);

        // Return a response (could be a redirect or a JSON response)
        return response()->json([
            'message' => 'Brand created successfully',
            'brand' => $brand,
        ], 201);
    }
}

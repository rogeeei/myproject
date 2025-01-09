<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;

class ProductTypeController extends Controller
{
    /**
     * Store a newly created brand in storage.
     */
  public function store(Request $request)
{
    // Validate incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'parent_type_id' => 'nullable|exists:parent_type,parent_type_id', 
    ]);

    // Create a new product type record
    $product_type = ProductType::create([
        'name' => $request->name,
        'parent_type_id' => $request->parent_type_id, 
    ]);

    // Return a response (could be a redirect or a JSON response)
    return response()->json([
        'message' => 'Product type created successfully',
        'product_type' => $product_type,
    ], 201);
}

}

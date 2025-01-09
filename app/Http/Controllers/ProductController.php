<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Products::with(['brand', 'productType', 'vendor'])->get();
        return response()->json($products, 200);
    }

     public function showByStore($storeId)
    {
        // Fetch products that belong to the given store ID
        $products = Products::where('store_id', $storeId)->get();

        // Check if products exist for the store
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No products found for this store.'
            ], 404);
        }

        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'UPC'              => 'required|string|unique:products,UPC',
            'product_name'     => 'required|string|max:255',
            'size'             => 'required|string|max:255',
            'packaging'        => 'required|string|max:255',
            'brand_id'         => 'required|exists:brand,brand_id',
            'product_type_id'  => 'required|exists:product_type,product_type_id',
            'vendor_id'        => 'required|exists:vendor,vendor_id',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity'         => 'required|integer',
            'price'         => 'required|numeric',
            'store_id' => 'required|exists:store,store_id',
        ]);

        // Handle the image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create the product
        $product = Products::create([
            'UPC'             => $validated['UPC'],
            'product_name'    => $validated['product_name'],
            'size'            => $validated['size'],
            'packaging'       => $validated['packaging'],
            'brand_id'        => $validated['brand_id'],
            'product_type_id' => $validated['product_type_id'],
            'vendor_id'       => $validated['vendor_id'],
            'image_path'      => $imagePath,
            'quantity'       => $validated['quantity'],
             'price'       => $validated['price'],
             'store_id' => $validated['store_id'],
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Products::with(['brand', 'productType', 'vendor'])->findOrFail($id);
        return response()->json($product, 200);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $validated = $request->validate([
            'UPC'              => 'sometimes|string|unique:products,UPC,' . $id . ',product_id',
            'product_name'     => 'sometimes|string|max:255',
            'size'             => 'sometimes|string|max:255',
            'packaging'        => 'sometimes|string|max:255',
            'brand_id'         => 'sometimes|exists:brand,brand_id',
            'product_type_id'  => 'sometimes|exists:product_type,product_type_id',
            'vendor_id'        => 'sometimes|exists:vendor,vendor_id',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        // Update the product
        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        // Delete the image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}

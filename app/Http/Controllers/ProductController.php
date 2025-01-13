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

   public function showBySubcategory($productTypeId)
{
    // Fetch products that belong to the given product type ID and include specific fields
    $products = Products::where('product_type_id', $productTypeId)->get([
        'product_id',
        'upc',
        'product_name',
        'price',
        'quantity',
    ]);

    // Check if products exist for the product type
    if ($products->isEmpty()) {
        return response()->json([
            'message' => 'No products found for this subcategory.'
        ], 404);
    }

    return response()->json($products);
}


    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'UPC'              => 'required|string|unique:products,UPC',
        'product_name'     => 'required|string|max:255',
        'size'             => 'required|string|max:255',
        'packaging'        => 'required|string|max:255',
        'brand_id'         => 'nullable|exists:brand,brand_id', // This can be null
        'product_type_id'  => 'required|exists:product_type,product_type_id',
        'vendor_id'        => 'nullable|exists:vendor,vendor_id', // This can be null
        'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'quantity'         => 'required|integer',
        'price'            => 'required|numeric',
        'store_id'         => 'required|exists:store,store_id',
    ]);

    // Set brand_id and vendor_id to null if not provided
    $brandId = $validated['brand_id'] ?? null;
    $vendorId = $validated['vendor_id'] ?? null;

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
        'brand_id'        => $brandId, // Automatically set to null if not provided
        'product_type_id' => $validated['product_type_id'],
        'vendor_id'       => $vendorId, // Automatically set to null if not provided
        'image_path'      => $imagePath,
        'quantity'        => $validated['quantity'],
        'price'           => $validated['price'],
        'store_id'        => $validated['store_id'],
    ]);

    return response()->json([
        'message' => 'Product created successfully',
        'product' => $product,
    ], 201);
}


    public function showProductsByStoreAndType($storeId, $productTypeId)
{
    // Fetch products based on store_id and product_type_id
    $products = Products::where('store_id', $storeId)
                        ->where('product_type_id', $productTypeId)
                        ->get([
                            'product_id',
                            'UPC',
                            'product_name',
                            'price',
                            'quantity',
                        ]);

    // Check if products exist
    if ($products->isEmpty()) {
        return response()->json([
            'message' => 'No products found for this store and product type.'
        ], 404);
    }

    return response()->json($products, 200);
}
public function showProductsByStore($storeId)
{
    // Fetch products based on store_id
    $products = Products::where('store_id', $storeId)
                        ->get([
                            'product_id',
                            'UPC',
                            'product_name',
                            'price',
                            'quantity',
                        ]);

    // Check if products exist
    if ($products->isEmpty()) {
        return response()->json([
            'message' => 'No products found for this store.'
        ], 404);
    }

    return response()->json($products, 200);
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

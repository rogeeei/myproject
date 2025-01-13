<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Products;
use App\Models\Store;

class InventoryController extends Controller
{
    
 public function getProductsForStore($storeId)
{
    // Fetch products that belong to the given store by checking store_id directly
    $products = Products::where('store_id', $storeId)->get();

    // Check if any products are found
    if ($products->isEmpty()) {
        return response()->json(['message' => 'No products found for this store.'], 404);
    }

    return response()->json($products);
}

public function getAllStoresWithProducts()
{
    // Fetch all stores along with their products
    $stores = Store::with('products')->get();

    // Check if any stores are found
    if ($stores->isEmpty()) {
        return response()->json(['message' => 'No stores or products found.'], 404);
    }

    return response()->json($stores);
}


    // Update stock quantity when a product is sold
    public function reduceStock(Request $request, $productId, $storeId)
    {
        $quantitySold = $request->input('quantity');

        // Find the inventory record for the given product and store
        $inventory = Inventory::where('product_id', $productId)
            ->where('store_id', $storeId)
            ->first();

        if ($inventory && $inventory->stock_quantity >= $quantitySold) {
            // Reduce the stock quantity
            $inventory->stock_quantity -= $quantitySold;
            $inventory->save();

            return response()->json(['message' => 'Stock updated successfully']);
        }

        return response()->json(['message' => 'Not enough stock available'], 400);
    }

    // Add new stock when products are restocked
    public function restock(Request $request, $productId, $storeId)
    {
        $quantityRestocked = $request->input('quantity');

        // Find the inventory record for the given product and store
        $inventory = Inventory::where('product_id', $productId)
            ->where('store_id', $storeId)
            ->first();

        if ($inventory) {
            // Increase the stock quantity
            $inventory->stock_quantity += $quantityRestocked;
            $inventory->save();

            return response()->json(['message' => 'Stock restocked successfully']);
        }

        return response()->json(['message' => 'Inventory record not found'], 404);
    }
}

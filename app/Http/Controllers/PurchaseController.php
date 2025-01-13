<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Products;
use App\Models\Store;
use App\Models\StoreOrder;
use App\Models\StoreOrderDetail;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Log;



class PurchaseController extends Controller
{
public function showStoresWithLowStockProducts()
{
    $stores = Store::with(['products' => function ($query) {
        $query->where('quantity', '<=', 100)
              ->orderBy('quantity', 'asc') // Sort products by quantity in ascending order
              ->select('store_id', 'product_id', 'UPC', 'product_name', 'price', 'quantity');
    }])->get();

    // Filter out stores without products
    $storesWithLowStock = $stores->filter(function ($store) {
        return $store->products->isNotEmpty();
    });

    if ($storesWithLowStock->isEmpty()) {
        return response()->json([
            'message' => 'No stores have products with quantity 100 or lower.'
        ], 404);
    }

    // Sort stores by the lowest quantity in their products
    $sortedStoresWithLowStock = $storesWithLowStock->sortBy(function ($store) {
        return $store->products->min('quantity');
    });

    return response()->json($sortedStoresWithLowStock->values(), 200);
}



public function getProductsForVendor($vendorId)
{
    try {
        // Fetch all products ordered for the given vendor ID
        $products = StoreOrderDetail::select(
                'products.product_id',
                'products.UPC',
                'products.product_name',
                'products.price',
                'store_order_detail.quantity as ordered_quantity',
                'store_order.store_id',
                'store_order.store_order_id', // Include store_order_id
                'store.store_name',
                'store.store_address'
            )
            ->join('products', 'store_order_detail.product_id', '=', 'products.product_id') // Join products
            ->join('store_order', 'store_order_detail.store_order_id', '=', 'store_order.store_order_id') // Join store orders
            ->join('store', 'store_order.store_id', '=', 'store.store_id') // Join stores for name and address
            ->where('store_order.vendor_id', $vendorId) // Filter by vendor ID
            ->orderBy('store_order.order_date', 'desc') // Optional: Order by most recent orders
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No products found for this vendor.',
            ], 404);
        }

        return response()->json($products, 200);
    } catch (\Exception $e) {
        // Log the exact error for debugging
        Log::error('Failed to fetch products for vendor: ' . $e->getMessage());

        return response()->json([
            'message' => 'Failed to fetch products.',
            'error' => $e->getMessage(),
        ], 500);
    }
}








   public function storeOrder(Request $request)
{
    $request->validate([
        'order_date' => 'nullable|date', 
        'vendor_id' => 'required|exists:vendor,vendor_id',
        'store_id' => 'required|exists:store,store_id',
        'payment_method' => 'nullable|string|max:255',
        'details' => 'required|array|min:1',
        'details.*.product_id' => 'required|exists:products,product_id',
        'details.*.quantity' => 'required|integer|min:1',
        'details.*.unit_price' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        // Use current date if not provided, Carbon instance for flexibility
        $orderDate = $request->order_date ? Carbon::parse($request->order_date) : Carbon::now();

        $order = StoreOrder::create([
            'order_date' => $orderDate,
            'total_amount' => 0,
            'payment_method' => $request->payment_method,
            'vendor_id' => $request->vendor_id,
            'store_id' => $request->store_id,
        ]);

        $totalAmount = 0;

        foreach ($request->details as $detail) {
            $subtotal = $detail['quantity'] * $detail['unit_price'];
            $totalAmount += $subtotal;

            StoreOrderDetail::create([
                'store_order_id' => $order->store_order_id,
                'product_id' => $detail['product_id'],
                'quantity' => $detail['quantity'],
                'unit_price' => $detail['unit_price'],
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total_amount' => $totalAmount]);

        DB::commit();

        return response()->json([
            'message' => 'Order created successfully!',
            'order' => $order->load('details'), // Load related details
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Failed to create order.',
            'error' => 'An unexpected error occurred. Please try again later.',
        ], 500);
    }
}



}

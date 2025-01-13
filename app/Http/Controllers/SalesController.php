<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Products;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB; 

class SalesController extends Controller
{
     public function getTopProductsPerStores(Request $request)
{
    // Get the top 10 products with highest sales for each store
    $topProductsPerStore = DB::table('orders')
        ->join('products', 'orders.product_id', '=', 'products.product_id')
        ->join('store', 'products.store_id', '=', 'store.store_id')
        ->select(
            'store.store_id',
            'store.store_name',
            'store.store_address',
            'orders.product_id',
            'products.product_name',
            DB::raw('SUM(orders.quantity) as total_quantity')
        )
        ->groupBy('store.store_id', 'store.store_name', 'store.store_address', 'orders.product_id', 'products.product_name')
        ->orderByDesc('total_quantity') // Sort by the total quantity ordered
        ->limit(10) // Get the top 10 products for each store
        ->get();

    if ($topProductsPerStore->isEmpty()) {
        return response()->json(['message' => 'No products found for any store.'], 404);
    }

    // Group the results by store
    $groupedByStore = $topProductsPerStore->groupBy('store_id');

    // Prepare the response with top products for each store
    $response = [];
    foreach ($groupedByStore as $storeId => $products) {
        $response[] = [
            'store' => $products->first()->store_name,
            'store_address' => $products->first()->store_address,
            'products' => $products->map(function ($product) {
                return [
                    'product_name' => $product->product_name,
                    'product_id' => $product->product_id,
                    'total_sales' => $product->total_quantity,
                ];
            }),
        ];
    }

    return response()->json($response);
}


public function getTopProductsPerStore($storeId, Request $request)
    {
        // Validate the storeId (optional, depending on your use case)
        if (!is_numeric($storeId)) {
            return response()->json(['message' => 'Invalid store ID'], 400);
        }

        // Get the top 10 products with highest sales for the given store
        $topProductsPerStore = DB::table('orders')
            ->join('products', 'orders.product_id', '=', 'products.product_id')
            ->join('store', 'products.store_id', '=', 'store.store_id')
            ->select(
                'store.store_id',
                'store.store_name',
                'store.store_address',
                'orders.product_id',
                'products.product_name',
                DB::raw('SUM(orders.quantity) as total_quantity')
            )
            ->where('store.store_id', $storeId)  // Filter by store ID
            ->groupBy('store.store_id', 'store.store_name', 'store.store_address', 'orders.product_id', 'products.product_name')
            ->orderByDesc('total_quantity') // Sort by total quantity ordered
            ->limit(10) // Get the top 10 products for the store
            ->get();

        if ($topProductsPerStore->isEmpty()) {
            return response()->json(['message' => 'No products found for this store.'], 404);
        }

        // Prepare the response with top products for the store
        $response = [
            'store' => $topProductsPerStore->first()->store_name,
            'store_address' => $topProductsPerStore->first()->store_address,
            'products' => $topProductsPerStore->map(function ($product) {
                return [
                    'product_name' => $product->product_name,
                    'product_id' => $product->product_id,
                    'total_sales' => $product->total_quantity,
                ];
            }),
        ];

        return response()->json($response);
    }

     // Method to get all products and their orders
    public function getAllProductsWithOrders()
    {
        try {
            // Retrieve all products with their associated orders
            $products = Products::with('orders')->get();

            // Check if products are found
            if ($products->isEmpty()) {
                return response()->json([
                    'message' => 'No products found.'
                ], 404);
            }

            // Return the products with their orders
            return response()->json([
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            // Handle any potential errors
            return response()->json([
                'message' => 'Error retrieving products and orders.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDailySales()
    {
        try {
            // Get the current date
            $today = Carbon::today();

            // Join orders with products to get price, calculate daily sales
            $sales = DB::table('orders')
                        ->join('products', 'orders.product_id', '=', 'products.product_id')
                        ->whereDate('orders.order_date', $today)
                        ->sum(DB::raw('orders.quantity * products.price'));

            // Return the total sales for the day
            return response()->json([
                'date' => $today->toDateString(),
                'total_sales' => $sales,
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'message' => 'Error retrieving daily sales.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

public function getDailySalesPerStore()
{
    try {
        // Get the current date
        $today = Carbon::today();

        // Get all stores, including those without products or orders
        $sales = DB::table('store')
            ->leftJoin('products', 'store.store_id', '=', 'products.store_id')  // Join with products table
            ->leftJoin('orders', 'products.product_id', '=', 'orders.product_id')  // Join with orders table
            ->where(function ($query) use ($today) {
                // Include orders only for the current date or orders with no date (i.e., no orders)
                $query->whereDate('orders.order_date', $today)
                      ->orWhereNull('orders.order_date');  // Handle stores without orders
            })
            ->select(
                'store.store_id',
                'store.store_name',
                'store.store_address',
                DB::raw('COALESCE(SUM(orders.quantity * products.price), 0) as total_sales') // Calculate total sales
            )
            ->groupBy('store.store_id', 'store.store_name', 'store.store_address') // Group by store details
            ->get();

        // Return the sales data for each store
        return response()->json([
            'date' => $today->toDateString(),
            'sales' => $sales,
        ], 200);
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json([
            'message' => 'Error retrieving daily sales.',
            'error' => $e->getMessage()
        ], 500);
    }
}





}

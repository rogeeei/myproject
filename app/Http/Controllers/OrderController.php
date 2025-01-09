<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;

class OrderController extends Controller
{
    /**
     * Show all orders.
     */
    public function index()
    {
        // Fetch all orders
        $orders = Orders::all();
        return response()->json($orders);
    }

    /**
     * Show an order by its ID.
     */
    public function show($orderId)
    {
        // Fetch the order by its ID
        $order = Orders::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    /**
     * Create a new order.
     */
   public function store(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'order_date' => 'required|date',
        'total_amount' => 'required|numeric',
        'customer_id' => 'required|exists:customer,customer_id',
        'store_id' => 'required|exists:store,store_id',
        'brand_id' => 'required|exists:brand,brand_id',
        'cashier_id' => 'required|exists:cashier,cashier_id',
    ]);

    // Create a new order
    $order = Orders::create($validated);

    // Load related names (assuming relationships are defined in the model)
    $order->load('customer', 'store', 'brand', 'cashier');

    // Format the response to include the names
    $response = [
        'order_id' => $order->order_id,
        'order_date' => $order->order_date,
        'total_amount' => $order->total_amount,
        'customer' => $order->customer->name ?? null, // Replace 'name' with the actual column
        'store' => $order->store->store_name ?? null, // Replace 'store_name' with the actual column
        'brand' => $order->brand->brand_name ?? null, // Replace 'brand_name' with the actual column
        'cashier' => isset($order->cashier) 
    ? $order->cashier->first_name . ' ' . $order->cashier->last_name 
    : null,  // Replace 'name' with the actual column
    ];

    return response()->json($response, 201);
}
    /**
     * Update an existing order.
     */
    public function update(Request $request, $orderId)
    {
        // Find the order
        $order = Orders::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'customer_id' => 'required|exists:customer,customer_id',
            'store_id' => 'required|exists:store,store_id',
            'brand_id' => 'required|exists:brand,brand_id',
            'cashier_id' => 'required|exists:cashier,cashier_id',
        ]);

        // Update the order
        $order->update($validated);

        return response()->json($order);
    }

    /**
     * Delete an order.
     */
    public function destroy($orderId)
    {
        // Find the order
        $order = Orders::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Delete the order
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}

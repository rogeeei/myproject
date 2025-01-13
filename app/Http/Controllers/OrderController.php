<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Products;
use Carbon\Carbon;

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
    // Validate the incoming request (without order_date since it's auto-set)
    $validated = $request->validate([
        'quantity' => 'required|numeric',
        'product_id' => 'required|exists:products,product_id', 
        'customer_id' => 'required|exists:customer,customer_id',
        'store_id' => 'required|exists:store,store_id',
        'brand_id' => 'nullable|exists:brands,brand_id',
        'cashier_id' => 'nullable|exists:cashiers,cashier_id',
    ]);

    // If cashier_id is not provided, set it to null
    if (empty($validated['cashier_id'])) {
        $validated['cashier_id'] = null;
    }

    // Set order_date to the current date
    $validated['order_date'] = Carbon::now();

    // Fetch the product
    $product = Products::where('store_id', $validated['store_id'])
                       ->where('product_id', $validated['product_id'])
                       ->first();

    // Check if the product exists and if there is enough stock
    if (!$product) {
        return response()->json(['message' => 'Product not found.'], 404);
    }

    if ($product->quantity < $validated['quantity']) {
        return response()->json(['message' => 'Not enough stock available.'], 400);
    }

    // Calculate the total amount by multiplying the product price and quantity
    $totalAmount = $product->price * $validated['quantity'];

    // Reduce the quantity of the product
    $product->quantity -= $validated['quantity'];
    $product->save();

    // Add the total_amount to the validated data
    $validated['total_amount'] = $totalAmount;

    // Create a new order with the calculated total_amount
    $order = Orders::create($validated);

    // Load related names (assuming relationships are defined in the model)
    $order->load('customer', 'store', 'brand', 'cashier');

    // Format the response to include the names
    $response = [
        'order_id' => $order->order_id,
        'order_date' => $order->order_date,
        'total_amount' => $order->total_amount,
        'quantity' => $order->quantity,
        'customer' => $order->customer->name ?? null, 
        'store' => $order->store->store_name ?? null, 
        'brand' => $order->brand->brand_name ?? null, 
        'cashier' => isset($order->cashier) 
            ? $order->cashier->first_name . ' ' . $order->cashier->last_name 
            : null,  
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

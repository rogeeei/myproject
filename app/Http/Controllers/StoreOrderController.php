<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreOrder;
use App\Models\Products;
use App\Models\StoreOrderDetail;
use App\Models\OrderApproval;

class StoreOrderController extends Controller
{
public function approveOrder($orderId)
{
    $vendorId = auth()->user()->vendor_id;  // Get the vendor's ID from authenticated user

    // Check if the order exists
    $order = StoreOrder::find($orderId);
    if (!$order) {
        return response()->json(['message' => 'Order not found.'], 404);
    }

    // Check if the order belongs to the vendor
    if ($order->vendor_id !== $vendorId) {
        return response()->json(['message' => 'You are not authorized to approve this order.'], 403);
    }

    // Check if the approval already exists
    $approval = OrderApproval::where('store_order_id', $orderId)
                              ->where('vendor_id', $vendorId)
                              ->first();

    if (!$approval) {
        // If no approval exists, create a new approval entry
        $approval = new OrderApproval();
        $approval->store_order_id = $orderId;
        $approval->vendor_id = $vendorId;
    }

    // Mark the order as approved (do not delete approval)
    $approval->is_approved = true;
    $approval->save();

    // Update the product quantity based on the order details
    $orderDetails = StoreOrderDetail::where('store_order_id', $orderId)->get();
    foreach ($orderDetails as $orderDetail) {
        $product = Products::find($orderDetail->product_id);
        if ($product) {
            $product->quantity += $orderDetail->quantity; // Add the ordered quantity to product's stock
            $product->save();
        }
    }

    // Delete the order after approval
    $order->delete();

    // Return response indicating success
    return response()->json(['message' => 'Order approved, product quantities updated, and order deleted.'], 200);
}





}

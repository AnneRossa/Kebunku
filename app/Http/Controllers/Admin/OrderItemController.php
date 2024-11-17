<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    public function index()
    {
        $orders = Order::with('order_items.order', 'order_items.product', 'order_items.product.location', 'order_items.product.category', 'order_items.order')
                       ->get();
        $user_adresses = UserAddress::get();
        return Inertia::render('Admin/OrderItem/Index', ['orders'=>$orders]);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|unique:orders,order_number,' . $id,
            'total_price'  => 'required|numeric',
            'status'       => 'required|in:unpaid,pending,paid,on_process,package_sent,completed',
            'order_items'  => 'required|array',
            'order_items.*.id' => 'nullable|exists:order_items,id',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity'   => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.order_items.index')
                             ->withErrors($validator)
                             ->withInput();
        }

        // Find the order
        $order = Order::findOrFail($id);

        // Update the order details
        $order->order_number = $request->order_number;
        $order->total_price = $request->total_price;
        $order->status = $request->status;
        $order->description = $request->description;
        $order->save();

        // Update order items
        foreach ($request->order_items as $item) {
            if (isset($item['id'])) {
                // Update existing order item
                $orderItem = OrderItem::findOrFail($item['id']);
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = Product::find($item['product_id'])->price;
                $orderItem->save();
            } else {
                // Add new order item
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = Product::find($item['product_id'])->price;
                $orderItem->save();
            }
        }

        return redirect()->route('admin.order_items.index')->with('success', 'Order updated successfully.');



        // $order = Order::findOrFail($id);

        // $order->total_price = $request->total_price;
        // $order->status = $request->status;
        // $order->order_number = $request->order_number;
        // $order->description = $request->description;
        // $order->order_items = $request->order_items;

        // $order->update();
        // return redirect()->route('admin.order_items.index')->with('success', 'Order updated successfully.');
    }

    // In DetailController.php
    public function orderDetail($id)
    {
        // Fetch the specific product by ID, including its relationships
        $orders = Order::with('order_items')->findOrFail($id);

        // Fetch locations and categories if needed elsewhere in the Vue component
        $order_items = OrderItem::all();
        $user_adresses = UserAddress::all();

        return Inertia::render('User/Detail', [
            'orders' => $orders,
            'order_items' => $order_items,
            // 'user_adresses' => $user_adresses
        ]);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orderitems.index')->with('success', 'Order deleted successfully.');
    }

}

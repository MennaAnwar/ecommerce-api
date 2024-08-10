<?php

namespace App\Http\Controllers;

use App\Models\location;
use App\Models\order_items;
use App\Models\orders;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index() {
        $orders = orders::with('user')->paginate(10);
        
        if ($orders) {
            foreach ($orders as $order) {
                foreach ($order->items as $order_item) {
                    $product = products::where('id', $order_item->product_id)->pluck('name');
                    $order_item->product_name = $product;
                }
            }
            return response()->json($orders, 200);
        } else {
            return response()->json("There are no orders");
        }
    }

    public function show($id) {
        $order = orders::find($id);
        return response()->json($order, 200);
    }


    public function store(Request $request) {
        $location = location::where('user_id', Auth::id())->first();

        $request->validate([
            "order_items" => "required",
            "total_price" => "required",
            "quantity" => "required",
            "date_of_delivery" => "required",
        ]);

        $order = new orders();
        $order->user_id = Auth::id();
        $order->location_id = $location->id;
        $order->total_price = $request->total_price;
        $order->date_of_delivery = $request->date_of_delivery;
        $order->save();

        foreach ($request->order_items as $order_item) {
            $item = new order_items();
            $item->order_id = $order->id;
            $item->price = $order_item['price'];
            $item->product_id = $order_item['product_id'];
            $item->quantity = $order_item['quantity'];
            $item->save();

            $product = products::where('id', $order_item['product_id'])->first();
            $product->quantity -= $order_item['quantity'];
            $product->save();
        }

        return response()->json(['message' => 'order added'],201);
    }

    public function get_order_items($id) {
        $order_items = order_items::where('order_id', $id)->get();
        
        if ($order_items) {
            foreach ($order_items as $order_item) {
                $product = products::where('id', $order_item->product_id)->pluck('name');
                $order_item->product_name = $product;
            }
            return response()->json($order_items);
        } else {
            return response()->json("No items found");
        }
    }

    public function get_user_orders($id) {
        $orders = orders::where('user_id', $id)
            ->with('items')
            ->latest()
            ->get();

        if ($orders) {
            foreach ($orders as $order) {
                foreach ($order->items as $order_item) {
                    $product = products::where('id', $order_item->product_id)->pluck('name');
                    $order_item->product_name = $product;
                }
            }
            return response()->json($orders);
        } else {
            return response()->json("No orders found for this user");
        }
    }

    public function change_order_status($id, Request $request) {
        $order = orders::find($id);
        
        if ($order) {
            $order->update(['status' => $request->status]);
            return response()->json("Status changed successfully");
        } else {
            return response()->json("Order was not found");
        }
    }
}

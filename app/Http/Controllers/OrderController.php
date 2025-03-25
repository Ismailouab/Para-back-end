<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    // for converting the data to json format
    public function index(){
        return Order::all();
    }

    // for showing the data using id
    public function show($id){
        try {
            // Load the order with related product data from 'product_order' and detailed order items from 'order_items'
            $order = Order::with(['products', 'orderItems.product'])->findOrFail($id);

            // Return the order with its associated products and order items
            return response()->json($order, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getOrdersByUserId($userId)
    {
        try {
            // Fetch all orders for the given user ID, including associated products
            $orders = Order::where('user_id', $userId)
                ->with(['product', 'orderItems.product']) // Eager load related data
                ->get();

            if ($orders->isEmpty()) {
                return response()->json(['message' => 'No orders found for this user.'], 404);
            }

            return response()->json($orders, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // for storing the data
    public function store(Request $request, $userId)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'products' => 'required|array',
                'products.*.product_name' => 'required|string|exists:products,name',
                'products.*.quantity' => 'required|integer|min:1',
                'status' => 'required|string|in:pending,completed,canceled',
                'priority' => 'required|string|in:normal,urgent',
            ]);

            // Create the order for the specified user
            $order = Order::create([
                'user_id' => $userId,
                'status' => $request->status,
                'total_price' => 0,
                'priority'=>$request->priority,
            ]);

            $totalPrice = 0;

            // Add products to the order
            foreach ($request->products as $productData) {
                $product = Product::where('name', $productData['product_name'])->first();
                $productTotalPrice = $product->price * $productData['quantity'];
                $totalPrice += $productTotalPrice;

                $order->product()->attach($product->id, [
                    'quantity' => $productData['quantity'],
                    'total_price' => $productTotalPrice,
                ]);

                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'total_price' => $productTotalPrice,
                ]);
            }

            // Update the total price
            $order->update(['total_price' => $totalPrice]);

            return response()->json($order->load('product', 'orderItems.product'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // for updating the data
    public function update(Request $request, $userId, $id)
    {
        try {
            // Validate the request data
            $request->validate([
                'products' => 'required|array',
                'products.*.product_name' => 'required|string|exists:products,name',
                'products.*.quantity' => 'required|integer|min:1',
                'status' => 'required|string|in:pending,completed,canceled',
                'priority' => 'required|string|in:normal,urgent',

            ]);

            // Find the order by ID and check if it belongs to the specified user
            $order = Order::where('user_id', $userId)->findOrFail($id);

            // Clear existing items from the order (in the pivot table)
            $order->product()->detach();

            $totalPrice = 0;  // Initialize total price

            // Loop through the products array to add each product item again (or update)
            foreach ($request->products as $productData) {
                // Get the product by its name
                $product = Product::where('name', $productData['product_name'])->first();

                if (!$product) {
                    return response()->json(['error' => 'Product item not found: ' . $productData['product_name']], 404);
                }

                // Calculate the total price for this product item
                $productTotalPrice = $product->price * $productData['quantity'];
                $totalPrice += $productTotalPrice;

                // Attach the product item to the order in the pivot table
                $order->product()->attach($product->id, [
                    'quantity' => $productData['quantity'],
                    'total_price' => $productTotalPrice,
                ]);
            }

            // Update the total price of the order (optional)
            $order->update(['total_price' => $totalPrice, 'status' => $request->status]);

            // Return the updated order with its items
            return response()->json($order->load('product'), 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // for deleting the data
    public function destroy($userId, $id){
        try {
            // Find the order by ID and check if it belongs to the specified user
            $order = Order::where('user_id', $userId)->findOrFail($id);

            // Detach from product_order pivot table and delete order items
            $order->product()->detach(); // Detach from product_order pivot table
            $order->orderItems()->delete(); // Delete from order_items table
            $order->delete(); // Delete the order

            return response()->json(['message' => 'Order deleted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

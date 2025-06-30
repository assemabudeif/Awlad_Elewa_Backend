<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderItemResource;

class CartController extends Controller
{
    public function index()
    {
        $cart = OrderItem::where('user_id', Auth::id())
            ->where('order_id', null)
            ->with('product')
            ->get();

        $total = $cart->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        if ($cart->count() == 0) {
            return response()->json([
                'message' => 'Cart is empty',
                'cart' => [],
                'total' => 0,
            ]);
        }

        return response()->json([
            'cart' => OrderItemResource::collection($cart),
            'total' => $total,
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product already exists in cart
        $cartItem = OrderItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('order_id', null)
            ->first();

        if ($cartItem) {
            // Update quantity if product already in cart
            $cartItem->quantity += $request->quantity;
            $cartItem->price = $product->price * $cartItem->quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = new OrderItem();
            $cartItem->user_id = Auth::id();
            $cartItem->product_id = $request->product_id;
            $cartItem->quantity = $request->quantity;
            $cartItem->price = $product->price * $request->quantity;
            $cartItem->save();
        }

        $cartItem->load('product');

        return response()->json([
            'message' => 'Product added to cart',
            'cart_item' => new OrderItemResource($cartItem),
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $cartItem = OrderItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('order_id', null)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'message' => 'Product not found in cart',
            ], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->price = $product->price * $request->quantity;
        $cartItem->save();

        $cartItem->load('product');

        return response()->json([
            'message' => 'Cart updated',
            'cart_item' => new OrderItemResource($cartItem),
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cartItem = OrderItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('order_id', null)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'message' => 'Product not found in cart',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Product removed from cart',
        ]);
    }

    public function clearCart()
    {

        OrderItem::where('user_id', Auth::id())
            ->where('order_id', null)
            ->delete();

        return response()->json([
            'message' => 'Cart cleared',
        ]);
    }
}

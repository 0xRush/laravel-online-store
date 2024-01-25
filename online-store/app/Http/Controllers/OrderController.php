<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class OrderController extends Controller
{
    function addToCart(Request $request, $id) {
        $cart = Cart::where('user_id', $request->user()->id)->where('is_paid', 0)->first();
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $request->user()->id,
                'is_paid' => false,
                'total' => 0
            ]);
        }

        $product = Product::find($id);
        if ($product->user_id != $request->user()->id) {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1
            ]);

            return response()->json(['data' => $cartItem], 200);
        }
        return response()->json(['data' => 'you are the product owner'], 404);
    }

    function showCart(Request $request) {
        $cart = Cart::where('user_id', $request->user()->id)->where('is_paid', 0)->first();
        if ($cart) {
            return response()->json(['data' => $cart], 200);
        }
        return response()->json(['data' => 'cart not found'], 404);
    }

    function showCartHistory(Request $request) {
        $cart = Cart::where('user_id', $request->user()->id)->where('is_paid', 1)->get();
        if ($cart) {
            return response()->json(['data' => $cart], 200);
        }
        return response()->json(['data' => 'cart not found'], 404);
    }

    function pay(Request $request) {
        $cart = Cart::where('user_id', $request->user()->id)->where('is_paid', 0)->first();
        if ($cart) {
            $cart->is_paid = true;
            $cart->save();
            return response()->json(['data' => 'cart paid successfully'], 200);
        }
        return response()->json(['data' => 'cart not found'], 404);
    }
}

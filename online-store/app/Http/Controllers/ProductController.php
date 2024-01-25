<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Auth\Access\Gate;

class ProductController extends Controller
{
    function createProduct (Request $request) {
        $name = $request->input('product_name');
        $price = $request->input('price');
        $description = $request->input('description');

        $product = Product::create([
            'product_name' => $name,
            'price' => $price,
            'description' => $description,
            'user_id'=> $request->user()->id
        ]);

        return response()->json(['data' => $product], 200);
    }

    function getProducts(Request $request) {
        $products = Product::where('user_id', $request->user()->id)->get();
        if ($products->count() > 0) {
            return response()->json(['data' => $products], 200);
        }
        return response()->json(['data' => 'no products found'], 404);
    }


    function getProduct (Request $request, $id) {
        $product = Product::find($id);
        if($product) {
            if ($request->user()->id == $product->user_id) {
                return response()->json(['data' => $product], 200);
            }
            return response()->json(['data' => 'you are not autherized'], 404);
        }
        return response()->json(['data' => 'product not found'], 404);
    }


    function deleteProduct(Request $request, $id) {
        $product = Product::find($id);
        if($request->user()->id == $product->user_id) {
            $product->delete();
            return response()->json(['data' => 'product deleted successfully'], 200);
        }
        return response()->json(['data' => 'you are not autherized'], 404);
    }

    function updateProduct(Request $request, $id) {
        $product = Product::find($id);
        if ($request->user()->id == $product->user_id) {
            $product->product_name = $request->input('product_name');
            $product->price = $request->input('price');
            $product->description = $request->input('description');
            $product->save();

            return response()->json(['data' => $product], 200);
        }
        return response()->json(['data' => 'you are not autherized'], 404);
    }
}

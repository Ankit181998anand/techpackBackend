<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Products;

class CartController extends Controller
{
    //
    public function addToCart(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            // Add any other necessary validation rules
        ]);

        $cart = Cart::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            // Add any other necessary fields
        ]);

        return response()->json(['message' => 'Product added to the cart successfully', 'cart' => $cart], 201);
    }

    public function getCartByUserId($userId)
    {
        // Fetch the cart items based on the user ID
        $cartItems = Cart::where('user_id', $userId)->get();

        foreach($cartItems as $cartItem){
            $product = Products::where('id', $cartItem->product_id)
            ->with(['images']) // Eager load the images relationship
            ->get();
            $cartItem->product_details= $product;
        }

        return response()->json(['cart' => $cartItems]);
    }

    public function deleteFile($ItemId)
    {
        try {
            $Item = Cart::findOrFail($ItemId);
            $Item->delete();

            return response()->json(['message' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Item not found or could not be deleted'], 404);
        }
    }
}

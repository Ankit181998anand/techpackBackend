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

        // Check if the combination of user_id and product_id already exists in the cart
        $existingCart = Cart::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCart) {
            // If the combination already exists, return a response indicating that the product is already in the cart
            return response()->json(['message' => 'Product already exists in the cart'], 409);
        }

        // If the combination doesn't exist, create a new cart record
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

        foreach ($cartItems as $cartItem) {
            $product = Products::where('id', $cartItem->product_id)
                ->with(['images']) // Eager load the images relationship
                ->get();
            $cartItem->product_details = $product;
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

    public function getCartProductList($userId)
    {
        // Fetch the cart items based on the user ID
        $cartItems = Cart::where('user_id', $userId)->get();
        $total = 0;

        foreach ($cartItems as $cartItem) {
            $product = Products::select('id', 'product_name', 'product_price')
                ->where('id', $cartItem->product_id)
                ->first();

            $priceString = $product->product_price;

            // Remove the dollar sign and extract the numeric part
            $numericPart = preg_replace("/[^0-9.]/", "", $priceString);

            // Convert the numeric part to a float
            $priceFloat = (float) $numericPart;
            $total += $priceFloat;
            $product_details[] = [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_price' => $product->product_price,

            ];
        }

        return response()->json([
            'total' => $total,
            'product_details' => $product_details ?? [],
        ]);
    }


}

<?php

namespace App\Http\Controllers;
use App\Models\UserProducts;
use App\Models\Orders;
use App\Models\Cart;
use Illuminate\Http\Request;

class OrderControll extends Controller
{
    //
    public function store(Request $request)
    {

        $productsArray = $request->products;
        $productsArray = array_map('strval', $productsArray); // Convert elements to strings
        $productsString = implode(',', $productsArray);
        

        $order = Orders::create([
            'name' => $request->name,
            'user_id' => $request->userId,
            'email' => $request->email,
            'contact' => $request->contact,
            'address' => $request->address,
            'products' => $productsString,
            'status' => 'Pending',
            'total' => $request->total

            
        ]);

        return response()->json([
                                    'message' => 'Order Placed successfully',
                                    'orderId' => $order->id,
                                    'Paypal_ClientID'=>'AX9cQpnh7bRp_D6BEHxl_8xeNqmhSl-mZdSfEWX5C6Vu0-mePWGOjA7sNUBzfhbaQLNd-LAQ-eZxbaNt',
                                    'Paypal_Secrate'=>'EHTG88hT1j9nsA9RmLZ2zI8lFhDqM4F_dTfgoSXFfjrZygaEbATLEZRxvyrREl9FmFgNsVGXqtlhBfsV'
                                    
                                ], 
                                201);
    }

    public function updateTransactionId(Request $request, $orderId)
    {

        $validatedData = $request->validate([
            'orderID' => 'required',
            'payerID' => 'required',
            'paymentID' => 'required',
            'facilitatorAccessToken' => 'required',
            'paymentSource' => 'required',
        ]);

        $neworderId = $request->input('orderID');
        $payerID = $request->input('payerID');
        $paymentID = $request->input('paymentID');
        $facilitatorAccessToken = $request->input('facilitatorAccessToken');
        $paymentSource = $request->input('paymentSource');

        // Validate the request if needed
        // ...

        $order = Orders::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $userId = $order->user_id;
        // Update the transaction_id
        $order->orderID = $neworderId;
        $order->payerID = $payerID;
        $order->paymentID = $paymentID;
        $order->facilitatorAccessToken = $facilitatorAccessToken;
        $order->paymentSource = $paymentSource;
        $order->status = "Complete";
        $order->save();

        // Retrieve products associated with the order
    $products = $order->products; // Get product IDs as an array
    $ArrayofProducts = explode(',', $products);

    //Empty Cart Items
    
    foreach ($ArrayofProducts as $productId) {
        Cart::where('user_id', $userId)->where('product_id', $productId)->delete();
    }


    // Check if the user already has products stored
    $userProducts = UserProducts::where('user_id', $userId)->first();

    if ($userProducts) {
        // Update the array of product IDs for the user
        $productIds = explode(',', $userProducts->product_ids); // Convert string to array
        $FinalproductIds = array_unique(array_merge($productIds, $ArrayofProducts));
        $userProducts->update(['product_ids' => implode(',', $FinalproductIds)]); // Convert array back to string
    } else {
        // Create a new record for the user
        UserProducts::create([
            'user_id' => $userId,
            'product_ids' => implode(',', $ArrayofProducts), // Convert array to string
        ]);
    }


        return response()->json(['message' => 'Transaction ID updated successfully']);
    }

}



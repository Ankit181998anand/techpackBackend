<?php

namespace App\Http\Controllers;

use App\Models\Orders;
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
            'country' => $request->country,
            'products' => $productsString,
            'transaction_id' => '',
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
        $newTransactionId = $request->input('transaction_id');

        // Validate the request if needed
        // ...

        $order = Orders::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update the transaction_id
        $order->transaction_id = $newTransactionId;
        $order->save();

        return response()->json(['message' => 'Transaction ID updated successfully']);
    }
}



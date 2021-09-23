<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product   = Product::findOrFail($request->id);
        $cart      = $request->cart;
        $sub_total = $request->sub_total;

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
        } else {
            $cart[$request->id] = [
                "product_name"  => $product->name,
                "product_price" => $product->price,
                "quantity"      => 1
            ];
        }

        $sub_total += $product->price;

        $data = [
            $cart,
            $sub_total
        ];

        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cart      = $request->cart;
        $sub_total = $request->sub_total;

        $cart[$id]['quantity']--;
        $sub_total -= $cart[$id]['product_price'];

        $data = [
            $cart,
            $sub_total
        ];

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $cart      = $request->cart;
        $sub_total = $request->sub_total;

        if (array_key_exists($id, $cart)) {
            $sub_total -= $cart[$id]['product_price'];
            unset($cart[$id]);
        }

        return [$cart, $sub_total];
    }
}

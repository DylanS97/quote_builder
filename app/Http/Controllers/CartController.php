<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->cart;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $cart = $request->cart;
        $sub_total = $request->sub_total;

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
        } else {
            $cart[$request->id] = [
                "product_name" => $product->name,
                "product_price" => $product->price,
                "quantity" => 1
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $cart = $request->cart;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $data)
    {
        $cart = $request->cart;
        $sub_total = $request->sub_total;
        $arr = [];

        foreach ($cart as $id => $item) {
            if($data != $id) {
                $arr[$id] = $item;
            } else {
                $sub_total -= $item['product_price'];
            }
        }

        return [$arr, $sub_total];
    }
}

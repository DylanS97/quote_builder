<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    protected $images;

    public function __construct(ProductImage $images)
    {
        $this->images = $images;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->images;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $this->validate($request, [
            'source' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'alt'    => 'required|string|min:3|max:50'
        ]);

        $attributes['source']     = str_replace('public/product_images/', '', request()->file('source')->store('public/product_images'));
        $attributes['product_id'] = $id;
        $attributes['alt']        = request('alt');

        $this->images->create($attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = $this->images->find($id);
        $image->delete();
    }
}

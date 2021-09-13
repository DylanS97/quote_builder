<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->search && $request->size) {
            // return paginated results that match the search parameter.
            return new ProductResource(
                Product::with('images')
                    ->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orderBy('created_at', $request->filter ? $request->filter : 'ASC')
                    ->paginate($request->size ? $request->size : 10)
            );
        } else if ($request->search) {
            // return results that match the search parameter. Un-paginated.
            return Product::with('images')
                ->where('name', 'LIKE', '%' . $request->search . '%')
                ->orderBy('name', 'ASC')
                ->get();
        } else if ($request->size) {
            // return paginated results.
            return new ProductResource(
                Product::with('images')
                    ->orderBy('created_at', $request->filter ? $request->filter : 'ASC')
                    ->paginate($request->size ? $request->size : 10)
            );
        } else {
            // return products for quote create page.
            return Product::with('images')
                ->orderBy('name', 'ASC')
                ->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $this->validateForm($request);

        return Product::create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('images')->find($id);

        return $product;
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
        $attributes = $this->validateForm($request, $id);

        Product::find($id)->update($attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->images()->delete();
        $product->delete();
    }

    /**
     * Validate the product form details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function validateForm($request, $id = null) {
        return $this->validate($request, [
            'name' => $id ? 'sometimes|required|string|unique:products,name,'.$id : 'required|string|unique:products',
            'description' => 'required|min:20|max:1000',
            'price' => 'required|regex:^[1-9][0-9]+^|not_in:0'
        ]);
    }
}

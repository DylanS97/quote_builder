<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $products;

    public function __construct(Product $products)
    {
        $this->products = $products;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->products->query();

        $query->with('images');

        if ($request->search) {
            $query->where('name', 'LIKE', "%$request->search%");
        }

        if ($request->filter) {
            $query->orderBy('created_at', $request->filter);
        } else {
            $query->orderBy('name');
        }

        if ($request->size) {
            return new ProductResource(
                $query->paginate($request->size)
            );
        }

        return $query->get();
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

        return $this->products->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->products->with('images')->findOrFail($id);

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

        $this->products->findOrFail($id)->update($attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->products->findOrFail($id);
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
            'name'        => $id ? 'sometimes|required|string|unique:products,name,'.$id : 'required|string|unique:products',
            'description' => 'required|min:20|max:1000',
            'price'       => 'required|regex:^[1-9][0-9]+^|not_in:0'
        ]);
    }
}

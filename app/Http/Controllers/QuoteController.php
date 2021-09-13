<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use App\Http\Resources\QuoteResource;
use App\Models\ProductQuote;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->search) {
            return new QuoteResource(
                Quote::where('client_first_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('client_last_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('id', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('created_at', 'LIKE', '%'. $request->search . '%')
                    ->orWhere('updated_at', 'LIKE', '%' . $request->search . '%')
                    ->orderBy($request->sort_field, $request->sort_direction)
                    ->paginate($request->size ? $request->size : 10)
            );
        } else if ($request->size) {
            return new QuoteResource(
                Quote::orderBy($request->sort_field, $request->sort_direction)
                    ->paginate($request->size ? $request->size : 10)
            );
        } else {
            return Quote::orderBy('created_at', 'DESC')
                ->limit(5)
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
        $quote = $this->updateOrCreateQuote($request);

        return Quote::with('products')->find($quote->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Quote::with('products')->find($id);
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
        $quote = Quote::with('products')->findOrFail($id);

        if ($request->completed || $request->completed === 0) {
            $this->updateCompleted($request->completed, $quote);
        } else {
            $this->updateOrCreateQuote($request, $quote);
        }
        
        return $quote;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quote = Quote::find($id);

        $quote->products()->delete();
        $quote->delete();
    }

    /**
     * Validate the Quote details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function validateDetails($request) 
    {
        return $this->validate($request, [
            'client_first_name' => 'required|alpha|max:256',
            'client_last_name' => 'required|alpha|max:256',
            'client_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'client_email' => 'required|email',
        ]);
    }

    /**
     * Validate the Quote cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function validateCart($request)
    {
        return $this->validate($request, [
            'cart' => 'required'
        ]);
    }

    /**
     * Update the completed status of a Quote.
     *
     * @param  int  $value
     * @param  object $quote
     */
    protected function updateCompleted($value, $quote)
    {
        if ($value === 1) {
            $quote->update(['completed' => true]);
        } else if ($value === 0) {
            $quote->update(['completed' => false]);
        }
    }

    /**
     * Update or Create a Quote.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  object $quote = null
     * @return \Illuminate\Http\Response
     */
    protected function updateOrCreateQuote($request, $quote = null)
    {
        $products = $this->validateCart($request);
        $details = $this->validateDetails($request);

        if ($quote != null) {
            // Update
            $details['completed'] = $quote->completed;
            $details['sub_total'] = $request->sub_total;
            $details['sub_total_vat'] = $details['sub_total'] * 0.2;
            $details['total'] = $details['sub_total'] + $details['sub_total_vat'];
    
            $quote->update($details);
            $quote->products()->delete();

            $this->updateQuoteProducts($quote, $products);
        } else {
            // Create
            $details['completed'] = false;
            $details['sub_total'] = $request->sub_total;
            $details['sub_total_vat'] = $details['sub_total'] * 0.2;
            $details['total'] = $details['sub_total'] + $details['sub_total_vat'];
    
            $quote = Quote::create($details);

            $this->updateQuoteProducts($quote, $products);

            return $quote;
        }
    }

    /**
     * Add the products to a Quote.
     *
     * @param  object  $quote
     * @param  object  $products
     */
    protected function updateQuoteProducts($quote, $products)
    {
        foreach ($products['cart'] as $id => $product) {
            $product['quote_id'] = $quote->id;
            $product['product_id'] = $id;

            ProductQuote::create($product);
        }
    }
}

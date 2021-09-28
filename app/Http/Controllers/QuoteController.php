<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use App\Http\Resources\QuoteResource;
use App\Mail\MailQuote;
use App\Models\ProductQuote;
use Illuminate\Support\Facades\Mail;

class QuoteController extends Controller
{
    protected $quotes;

    public function __construct(Quote $quotes) 
    {
        $this->quotes = $quotes;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->quotes->query();

        if ($request->search) {
            $query->where('client_first_name', 'LIKE', "%{$request->search}%");
            $query->orWhere('id', 'LIKE', "%{$request->search}%");
            $query->orWhere('created_at', 'LIKE', "%{$request->search}%");
            $query->orWhere('updated_at', 'LIKE', "%{$request->search}%");
        }

        if ($request->size) {
            $query->orderBy($request->sort_field, $request->sort_direction);

            return new QuoteResource($query->paginate($request->size));
        }

        // If no size available - then on homepage.
        return $this->quotes->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
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

        return $this->quotes->with('products')->find($quote->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->quotes->with('products')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        if ($request->completed == true) {
            $this->updateCompleted($quote);
        } else {
            $this->updateOrCreateQuote($request, $quote);
        }
        
        return $quote;
    }

    /**
     * Update the completed status of a Quote.
     *
     * @param  object $quote
     */
    public function updateCompleted($id)
    {
        $quote = $this->quotes->with('products')->findOrFail($id);
        $quote->update(['completed' => $quote->completed === 1 ? 0 : 1]);
        return $quote;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        $quote->products()->delete();
        $quote->delete();
    }

    /**
     * Emails quote to the customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mailQuote($id) {
        $quote = $this->quotes->with('products')->findOrFail($id);
        
        Mail::to($quote->client_email)->send(new MailQuote($quote));
        
        return 'Successful';
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
            'client_last_name'  => 'required|alpha|max:256',
            'client_phone'      => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'client_email'      => 'required|email',
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

        $details['sub_total']     = $request->sub_total;
        $details['sub_total_vat'] = $details['sub_total'] * 0.2;
        $details['total']         = $details['sub_total'] + $details['sub_total_vat'];

        if ($quote != null) {
            // Update
            $details['completed']     = $quote->completed;
    
            $quote->update($details);
            $quote->products()->delete();

        } else {
            // Create
            $details['completed']     = false;
    
            $quote = $this->quotes->create($details);
        }

        $this->updateQuoteProducts($quote, $products);

        return $quote;
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
            $product['quote_id']   = $quote->id;
            $product['product_id'] = $id;

            ProductQuote::create($product);
        }
    }
}

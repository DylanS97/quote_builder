@component('mail::message')
<div class="shadow-2xl bg-gray-50 p-10">
    <div class="p-5 border-8 border-gray-400 bg-white">
        <h1 class="text-xl font-medium py-5 mb-5 border-b border-gray-300">Quote Details</h1>
        <div class="flex justify-between p-2">
            <h2 class="font-medium">Quote ID:</h2>
            <span class="font-light">{{ $quote->id }}</span>
        </div>
        <div class="flex justify-between p-2">
            <h2 class="font-medium">Client Name:</h2>
            <span class="font-light">{{ $quote->client_first_name }} {{ $quote->client_last_name }}</span>
        </div>
        <div class="flex justify-between p-2">
            <h2 class="font-medium">Client Mobile:</h2>
            <span class="font-light">{{ $quote->client_phone }}</span>
        </div>
        <div class="flex justify-between p-2">
            <h2 class="font-medium">Client Email:</h2>
            <span class="font-light">{{ $quote->client_email }}</span>
        </div>
    </div>
    <hr class="mt-4">
    <div class="p-5 border-8 border-gray-400 bg-white">
        <h1 class="text-xl font-medium py-5 mb-5 border-b border-gray-300">Products</h1>
        <table class="w-full border border-gray-300">
            <tr class="px-2 border-b border-gray-300">
                <th class="p-4 font-medium">Name</th>
                <th class="p-4 font-medium">Quantity</th>
                <th class="p-4 font-medium">Price</th>
            </tr>
            @foreach ($quote['products'] as $product)
                <tr class="p-2">
                    <td class="p-4 text-center font-light">{{ $product->product_name }}</td>
                    <td class="p-4 text-center font-light">{{ $product->quantity }}</td>
                    <td class="p-4 text-center font-light">{{ Currency::currency("GBP")->format($product->product_price) }}</td>
                </tr>
            @endforeach
            <tr class="px-2 py-6 border-t border-gray-300">
                <th class="text-left p-4 px-6 font-medium" colspan="2">Sub Total</th>
                <td class="text-center p-4 font-light">{{ Currency::currency("GBP")->format($quote->sub_total) }}</td>
            </tr>
            <tr class="px-2 py-6 border-t border-gray-300">
                <th class="text-left p-4 px-6 font-medium" colspan="2">VAT</th>
                <td class="text-center p-4 font-light">{{ Currency::currency("GBP")->format($quote->sub_total_vat) }}</td>
            </tr>
            <tr class="px-2 py-6 border-t border-gray-300">
                <th class="text-left p-4 px-6 font-medium" colspan="2">Total</th>
                <td class="text-center p-4 font-light">{{ Currency::currency("GBP")->format($quote->total) }}</td>
            </tr>
        </table>
    </div>
</div>

@component('mail::button', ['url' => ''])
View Quote
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

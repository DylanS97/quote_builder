@component('mail::message')
<div class="shadow-2xl bg-gray-50 p-10 w-screen">
    <div class="flex justify-between p-5 border-8 border-gray-400 bg-white">
        <div class="font-medium">Daily Overview</div>
        <div class="float-right"><strong>{{ $date }}</strong></div>
    </div>
    <hr class="mt-4">
    <div class="p-5 border-8 bg-white">
        <div class="mb-5 border-b font-medium">Created Quotes</div>
        <table class="w-full border">
            <thead class="px-2 border-b">
                <tr>
                    <th class="p-4 font-medium">ID</th>
                    <th class="p-4 font-medium">Client Name</th>
                    <th class="p-4 font-medium">Total Price</th>
                    <th class="p-4 font-medium">Created At</th>
                </tr>
            </thead>
            <tbody>
                @if (count($quotes) > 0)
                    @foreach ($quotes as $quote)
                        <tr>
                            <td class="p-4 text-center font-light">{{ $quote->id }}</td>
                            <td class="p-4 text-center font-light">{{ $quote->client_first_name }} {{ $quote->client_last_name }}</td>
                            <td class="p-4 text-center font-light">{{ Currency::currency("GBP")->format($quote->total) }}</td>
                            <td class="p-4 text-center font-light">{{ $quote->created_at->format('d-m-Y H:00') }}</td>
                        </tr>
                    @endforeach
                @elseif (count($quotes) == 0)
                    <tr>
                        <td class="p-4 text-center" colspan="4">
                            No Quotes Created Today.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endcomponent
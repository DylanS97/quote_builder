@extends('layouts.master')

@section('page')
    <div id="app">
        @include('layouts.navigation')

        <main id="page-content" class="w-full-w-nav float-right duration-300 bg-gray-100 min-h-screen">
            {{ $slot }}
        </main>
    </div>
@endsection

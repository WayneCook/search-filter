<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <title>Product Search</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
          body {
            background-color: whitesmoke;
          }
        </style>
    </head>
    <body>
      <div id="app">

        {{-- <product-search :products='{{ $products }}'></product-search> --}}
      <filterable :fields='{{ json_encode($tableFields) }}'></filterable>

  </div>
    </body>
</html>

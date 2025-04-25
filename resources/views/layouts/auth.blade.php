<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', env('APP_NAME'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/sass/main.sass','resources/js/app.js'])
        @endif
    </head>
    <body class="antialiased">
        @include('shared.flash')
        <main class="md:min-h-screen md:flex md:items-center md:justify-center py-16 lg:py-20">
            <div class="container">
       
               <!-- Page heading -->
               <div class="text-center">
                   <a href="{{route('home')}}" class="inline-block" rel="home">
                       <img src="{{Vite::image('logo.png')}}" class="w-[40px] md:w-[50px] h-[40px] md:h-[50px] rounded-[10px]" alt="SHOP">
                   </a>
               </div>
       
               @yield('content')
       
           </div>
        </main>
    </body>
</html>

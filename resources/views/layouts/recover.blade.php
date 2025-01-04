<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admissão à UniSave</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
	<link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png"/>
    @livewireStyles
    
    <tallstackui:script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" background="">
<x-ts-toast />

    <div class="min-h-screen flex">
        <!-- Coluna da esquerda - Login -->
        <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-20 w-auto" src="{{ asset('frontend1/img/logo.png') }}" alt="UniSave">
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-sm">
            @yield('content')
                
            </div>
        </div>
        <div class="hidden lg:block flex-1">
            <img src="{{ asset('frontend1/img/teste2.jpg') }}" alt="Banner" class="object-cover w-full h-full">
        </div>
    </div>

    @livewireScripts
</body>
</html>
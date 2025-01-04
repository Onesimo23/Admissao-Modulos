<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png"/>


    <title>{{ config('app.name', 'ADMISSAO_SYS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Substituir os icons com Heroicons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        @media (max-width: 640px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 999;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            #sidebar.open {
                transform: translateX(0);
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }

            .overlay.active {
                display: block;
            }
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }

        .submenu.active {
            max-height: 500px;
            transition: max-height 0.2s ease-in;
        }

        .sidebar-collapsed .sidebar-full {
            display: none;
        }

        .sidebar-collapsed .submenu {
            display: none;
        }

        .sidebar-collapsed .menu-item {
            justify-content: center;
        }

        #sidebar {
            overflow: visible;
        }

        .sidebar-collapsed .menu-item {
            position: relative;
        }

        .sidebar-collapsed .menu-item:hover .dropdown-content {
            display: block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            margin-left: 1px;
            background-color: #3730A3;
            min-width: 200px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-radius: 0.25rem;
            padding: 0.5rem 0;
        }

        .dropdown-content a {
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #4792f5;
        }

        .dropdown-menu-item {
            font-weight: bold;
            border-bottom: 1px solid #4792f5;
        }

        .dropdown-submenu-item {
            padding-left: 1.5rem !important;
            font-size: 0.9em;
        }

        .dropdown-content {
            transition: all 0.3s ease-in-out;
        }

        .flex-1 {
            z-index: 1;
        }

        /* Estilos para o modo escuro */
        .dark {
            color-scheme: dark;
        }

        .dark body {
            @apply bg-gray-900 text-white;
        }

        .dark .bg-white {
            @apply bg-gray-800;
        }

        .dark .text-gray-800 {
            @apply text-gray-200;
        }

        .dark .bg-gray-100 {
            @apply bg-gray-700;
        }

        .dark .shadow-md {
            @apply shadow-gray-700;
        }

        .dark .bg-gray-50 {
            @apply bg-gray-700;
        }

        .dark .text-gray-900 {
            @apply text-gray-100;
        }

        .dark .text-gray-700 {
            @apply text-gray-300;
        }

        .dark .border-gray-200 {
            @apply border-gray-600;
        }

        .dark .bg-gray-50 {
            @apply bg-gray-800;
        }

        .dark .text-gray-500 {
            @apply text-gray-400;
        }

        /* Estilos responsivos para o Top Navbar */
        @media (max-width: 640px) {
            .top-navbar-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .top-navbar-right {
                width: 100%;
                margin-top: 1rem;
                justify-content: space-between;
            }
        }

        .menu-active {
            background-color: #4338ca;
            /* Um tom mais escuro de índigo */
            border-left: 4px solid #ffffff;
            /* Uma borda branca à esquerda */
            font-weight: bold;
            color: #ffffff;
            /* Texto branco */
        }

        .menu-active i {
            color: #ffffff;
            /* Ícone branco para maior contraste */
        }

        .menu-item:hover:not(.menu-active) {
            background-color: #4f46e5;
            /* Um tom mais claro para o hover */
        }

        :root {
            --primary-color: #3730A3;
            --hover-color: #4792f5;
        }

        < !-- estilização dos componentes para evitar o loader -->
    </style>

    <!-- Scripts -->
    <tallstackui:script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-gray-100 transition-colors duration-200">
    <x-ts-toast />


    <div class="flex h-screen">

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navbar -->
            <header class="shadow-md dark:bg-gray-800 flex justify-between items-center p-4 bg-blue-800">
                <div class="flex items-center">
                    <img loading="lazy" class="h-8 w-auto" src="https://sig.unisave.ac.mz/sigeup/public/dist/img/up.png" alt="Logo">
                    <h3 class="text-xl text-center font-semibold text-white dark:text-white ml-2 ">Formulário de Pré-Inscrição</h3>
                </div>

                <div class="flex items-center mt-4 sm:mt-0">
                    <div class="relative">

                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @hasSection('content')
                @yield('content')
                @else
                {{ $slot }}
                @endif
            </main>
        </div>
    </div>

</body>

</html>
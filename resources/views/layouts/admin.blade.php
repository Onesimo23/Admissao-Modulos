<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png" />

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
    </style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <tallstackui:script />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-gray-100 transition-colors duration-200">
    <x-ts-toast />

    <div id="overlay" class="overlay"></div>
    <div class="flex h-auto">

        <!-- Sidebar -->
        <aside id="sidebar" class="bg-indigo-800 text-white w-64 min-h-screen overflow-y-auto transition-all duration-300 ease-in-out">
            <div class="p-4">
                <h3 class="text-md font-bold mb-5 sidebar-full text-center">UniSave</h3>
                <hr class="mb-4">
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2 px-4 rounded hover:bg-indigo-700 transition duration-200 menu-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                </svg>

                                <span class="sidebar-full ml-3">Início</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('schools.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-indigo-700 transition duration-200 menu-item">
                                <i class="fas fa-school"></i>
                                <span class="sidebar-full ml-3">Escolas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('class-models') }}" class="flex items-center py-2 px-4 rounded hover:bg-indigo-700 transition duration-200 menu-item">
                                <i class="fas fa-chalkboard"></i>
                                <span class="sidebar-full ml-3">Salas</span>
                            </a>
                        </li>
                      
                        <li>
                            <a href="{{ route('disciplines.index') }}" class="flex items-center py-2 px-4 rounded hover:bg-indigo-700 transition duration-200 menu-item">
                                <i class="fas fa-book"></i>
                                <span class="sidebar-full ml-3">Cursos</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jury.distributions') }}" class="flex items-center py-2 px-4 rounded hover:bg-indigo-700 transition duration-200 menu-item">
                                <i class="fas fa-gavel"></i>
                                <span class="sidebar-full ml-3">Distribuição de Júris</span>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-yellow-700 dark:yellow-gray-300 hover:bg-red-100 dark:hover:bg-red-700">
                                    SAIR
                                </button>
                            </form>

                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navbar -->
            <header class="bg-white shadow-md dark:bg-gray-800 flex justify-between items-center p-4">
                <div class="flex items-center">
                    <button id="sidebar-toggle" class="text-gray-600 dark:text-gray-400 focus:outline-none">
                        <i class="fas fa-bars mr-4"></i>
                    </button>
                    <img loading="lazy" class="h-8 w-auto" src="https://sig.unisave.ac.mz/sigeup/public/dist/img/up.png" alt="Logo">
                    <h3 class="text-xl font-semibold text-blue-700 dark:text-white ml-2 sm:block">{{ config('app.name', 'ADMISSAO_SYS') }}</h3>
                </div>

                <div class="flex items-center mt-4 sm:mt-0">
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center text-gray-600 dark:text-gray-400 focus:outline-none">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="User avatar">
                            <span class="ml-2 hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 hidden">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Sair
                                </button>
                            </form>
                        </div>
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

    @livewireScripts
    @vite('resources/js/app.js')
    <script>
        // Main initialization function
        function initializeSidebar() {
            // Get DOM elements
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('overlay');
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            // Ensure all required elements exist
            if (!sidebar || !sidebarToggle || !overlay || !userMenuButton || !userDropdown) {
                console.error('Required elements not found. Waiting for DOM...');
                return false;
            }

            // Sidebar toggle functionality
            function toggleSidebar() {
                if (window.innerWidth <= 640) {
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('active');
                } else {
                    sidebar.classList.toggle('w-64');
                    sidebar.classList.toggle('w-16');
                    sidebar.classList.toggle('sidebar-collapsed');
                }
            }

            // Attach sidebar toggle events
            sidebarToggle.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Submenu functionality
            const menuItems = document.querySelectorAll('.menu-item');
            const submenuItems = document.querySelectorAll('.submenu-item');

            function openSubmenu(submenu) {
                if (!submenu) return;
                submenu.style.maxHeight = submenu.scrollHeight + "px";
                submenu.classList.add('active');
                const parentMenuItem = submenu.closest('.menu-item');
                if (parentMenuItem) {
                    parentMenuItem.classList.add('active');
                    const chevron = parentMenuItem.querySelector('.fa-chevron-down');
                    if (chevron) {
                        chevron.classList.add('fa-rotate-180');
                    }
                }
            }

            function closeSubmenu(submenu) {
                if (!submenu) return;
                submenu.style.maxHeight = null;
                submenu.classList.remove('active');
                const parentMenuItem = submenu.closest('.menu-item');
                if (parentMenuItem) {
                    parentMenuItem.classList.remove('active');
                    const chevron = parentMenuItem.querySelector('.fa-chevron-down');
                    if (chevron) {
                        chevron.classList.remove('fa-rotate-180');
                    }
                }
            }

            function closeAllSubmenus() {
                document.querySelectorAll('.submenu.active').forEach(closeSubmenu);
            }

            function deactivateAllMenuItems() {
                menuItems.forEach(item => {
                    item.classList.remove('active', 'menu-active', 'menu-item-active');
                });
                submenuItems.forEach(item => {
                    item.classList.remove('bg-indigo-700', 'submenu-item-active');
                });
            }

            function activateMenuItem(item, isSubmenuItem = false) {
                if (!item) return;
                deactivateAllMenuItems();

                if (isSubmenuItem) {
                    item.classList.add('bg-indigo-700', 'submenu-item-active');
                    const parentSubmenu = item.closest('.submenu');
                    if (parentSubmenu) {
                        openSubmenu(parentSubmenu);
                        const parentMenuItem = parentSubmenu.previousElementSibling;
                        if (parentMenuItem) {
                            parentMenuItem.classList.add('active', 'menu-active', 'menu-item-active');
                        }
                    }
                } else {
                    item.classList.add('active', 'menu-active', 'menu-item-active');
                    const submenu = item.nextElementSibling;
                    if (submenu && submenu.classList.contains('submenu')) {
                        openSubmenu(submenu);
                    }
                }
            }

            function handleMenuItemClick(item, isSubmenuItem = false) {
                if (!item) return;
                activateMenuItem(item, isSubmenuItem);
                const href = item.getAttribute('href');
                if (href) {
                    localStorage.setItem('activeMenuItemHref', href);
                    localStorage.setItem('isSubmenuItem', isSubmenuItem);
                }
            }

            // Attach menu item click events
            menuItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    const submenu = item.nextElementSibling;
                    if (submenu && submenu.classList.contains('submenu')) {
                        e.preventDefault();
                        if (submenu.classList.contains('active')) {
                            closeSubmenu(submenu);
                        } else {
                            closeAllSubmenus();
                            openSubmenu(submenu);
                        }
                    } else {
                        e.preventDefault();
                        const href = item.getAttribute('href');
                        handleMenuItemClick(item);
                        if (href) {
                            window.location.href = href;
                        }
                    }
                });
            });

            // Attach submenu item click events
            submenuItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const href = item.getAttribute('href');
                    handleMenuItemClick(item, true);
                    if (href) {
                        window.location.href = href;
                    }
                });
            });

            // Setup sidebar dropdown functionality
            function setupSidebarDropdown() {
                menuItems.forEach(item => {
                    const dropdownContent = document.createElement('div');
                    dropdownContent.className = 'dropdown-content';

                    const menuText = document.createElement('a');
                    const href = item.getAttribute('href');
                    if (href) {
                        menuText.href = href;
                        const sidebarFull = item.querySelector('.sidebar-full');
                        if (sidebarFull) {
                            menuText.textContent = sidebarFull.textContent.trim();
                        }
                        menuText.className = 'dropdown-menu-item';
                        dropdownContent.appendChild(menuText);
                    }

                    const submenu = item.nextElementSibling;
                    if (submenu && submenu.classList.contains('submenu')) {
                        const submenuItems = submenu.querySelectorAll('a');
                        submenuItems.forEach(subItem => {
                            const subItemClone = document.createElement('a');
                            const subHref = subItem.getAttribute('href');
                            if (subHref) {
                                subItemClone.href = subHref;
                                subItemClone.textContent = subItem.textContent.trim();
                                subItemClone.className = 'dropdown-submenu-item';
                                dropdownContent.appendChild(subItemClone);
                            }
                        });
                    }

                    item.appendChild(dropdownContent);

                    // Attach click events to dropdown items
                    dropdownContent.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', (e) => {
                            e.preventDefault();
                            const href = link.getAttribute('href');
                            if (href) {
                                const isSubmenuItem = link.classList.contains('dropdown-submenu-item');
                                const targetItem = isSubmenuItem ?
                                    document.querySelector(`.submenu-item[href="${href}"]`) :
                                    item;
                                handleMenuItemClick(targetItem, isSubmenuItem);
                                window.location.href = href;
                            }
                        });
                    });
                });
            }

            // Setup sidebar hover functionality
            function setupSidebarHover() {
                menuItems.forEach(item => {
                    item.addEventListener('mouseenter', () => {
                        if (sidebar.classList.contains('sidebar-collapsed')) {
                            const dropdownContent = item.querySelector('.dropdown-content');
                            if (dropdownContent) {
                                dropdownContent.style.display = 'block';
                            }
                        }
                    });

                    item.addEventListener('mouseleave', () => {
                        if (sidebar.classList.contains('sidebar-collapsed')) {
                            const dropdownContent = item.querySelector('.dropdown-content');
                            if (dropdownContent) {
                                dropdownContent.style.display = 'none';
                            }
                        }
                    });
                });
            }

            // User dropdown functionality
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });

            // Restore active menu item from localStorage
            function setActiveMenuItem() {
                const activeMenuItemHref = localStorage.getItem('activeMenuItemHref');
                const isSubmenuItem = localStorage.getItem('isSubmenuItem') === 'true';

                if (activeMenuItemHref) {
                    const activeItem = isSubmenuItem ?
                        document.querySelector(`.submenu-item[href="${activeMenuItemHref}"]`) :
                        document.querySelector(`.menu-item[href="${activeMenuItemHref}"]`);

                    if (activeItem) {
                        activateMenuItem(activeItem, isSubmenuItem);
                    }
                }
            }

            // Initialize all functionality
            setupSidebarDropdown();
            setupSidebarHover();
            setActiveMenuItem();

            return true;
        }

        // Try to initialize immediately
        let initialized = initializeSidebar();

        // If initialization fails, wait for DOM content to be loaded
        if (!initialized) {
            document.addEventListener('DOMContentLoaded', function() {
                initializeSidebar();
            });
        }

        // Add a fallback for dynamic content loading
        window.addEventListener('load', function() {
            if (!initialized) {
                initializeSidebar();
            }
        });
    </script>
</body>

</html>
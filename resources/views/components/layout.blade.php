<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BiblioSmart' }} - Sistema de Recomendación de Lectura</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 text-gray-900 antialiased">
    {{-- Navigation --}}
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold text-indigo-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        BiblioSmart
                    </a>
                    @auth
                        @if(auth()->user()->has_completed_evaluation)
                        <div class="hidden sm:flex sm:ml-8 sm:space-x-4">
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition">Dashboard</a>
                            <a href="{{ route('books.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('books.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition">Catálogo</a>
                            <a href="{{ route('recommendations.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('recommendations.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition">Recomendaciones</a>
                            <a href="{{ route('reading-evaluations.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reading-evaluations.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition">Evaluaciones</a>
                        </div>
                        @endif
                    @endauth
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('profile.show') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">{{ auth()->user()->name }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition">Salir</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        @auth
            @if(auth()->user()->has_completed_evaluation)
            <div class="sm:hidden border-t border-gray-200 pb-3 pt-2">
                <div class="space-y-1 px-4">
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">Dashboard</a>
                    <a href="{{ route('books.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('books.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">Catálogo</a>
                    <a href="{{ route('recommendations.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('recommendations.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">Recomendaciones</a>
                    <a href="{{ route('reading-evaluations.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('reading-evaluations.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500' }}">Evaluaciones</a>
                </div>
            </div>
            @endif
        @endauth
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="rounded-lg bg-green-50 p-4 border border-green-200">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="rounded-lg bg-yellow-50 p-4 border border-yellow-200">
                <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
            </div>
        </div>
    @endif
    @if(session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="rounded-lg bg-blue-50 p-4 border border-blue-200">
                <p class="text-sm text-blue-800">{{ session('info') }}</p>
            </div>
        </div>
    @endif

    {{-- Main content --}}
    <main class="min-h-[calc(100vh-4rem)]">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} BiblioSmart - Sistema de Recomendación de Lectura</p>
            </div>
        </div>
    </footer>
</body>
</html>

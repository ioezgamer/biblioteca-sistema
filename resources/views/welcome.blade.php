<x-layout title="Bienvenido">
    {{-- Hero --}}
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-5xl font-extrabold tracking-tight sm:text-6xl">
                    Descubre tu próxima <span class="text-yellow-300">gran lectura</span>
                </h1>
                <p class="mt-6 text-xl text-indigo-100">
                    Nuestro sistema inteligente analiza tus intereses y nivel de lectura para recomendarte
                    los libros perfectos de nuestra biblioteca con más de <strong>{{ number_format($totalBooks) }}</strong> títulos.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 text-lg font-semibold rounded-xl hover:bg-indigo-50 transition shadow-lg">
                            Comenzar Ahora
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white text-lg font-semibold rounded-xl hover:bg-white/10 transition">
                            Ya tengo cuenta
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 text-lg font-semibold rounded-xl hover:bg-indigo-50 transition shadow-lg">
                            Ir al Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    {{-- How it works --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">¿Cómo funciona?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 rounded-2xl bg-white shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-bold">1</div>
                <h3 class="text-xl font-semibold mb-3">Evaluación Inicial</h3>
                <p class="text-gray-600">Completa una breve evaluación sobre tus intereses, nivel de lectura y preferencias.</p>
            </div>
            <div class="text-center p-8 rounded-2xl bg-white shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-bold">2</div>
                <h3 class="text-xl font-semibold mb-3">Recomendaciones Personalizadas</h3>
                <p class="text-gray-600">Recibe recomendaciones de libros basadas en tus intereses de nuestra biblioteca.</p>
            </div>
            <div class="text-center p-8 rounded-2xl bg-white shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-16 h-16 bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-bold">3</div>
                <h3 class="text-xl font-semibold mb-3">Evaluación de Lectura</h3>
                <p class="text-gray-600">Después de leer, completa una evaluación para demostrar tu comprensión del libro.</p>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    @if($categories->count())
    <div class="bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Explora nuestras categorías</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 text-center hover:bg-indigo-50 hover:border-indigo-200 transition cursor-default">
                        <span class="text-2xl">{{ $category->icon ?? '📖' }}</span>
                        <p class="mt-2 text-sm font-medium text-gray-700">{{ $category->name }}</p>
                        <p class="text-xs text-gray-500">{{ $category->books_count }} libros</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Stats --}}
    <div class="bg-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold">{{ number_format($totalBooks) }}+</div>
                    <div class="mt-2 text-indigo-200">Libros disponibles</div>
                </div>
                <div>
                    <div class="text-4xl font-bold">50</div>
                    <div class="mt-2 text-indigo-200">Categorías</div>
                </div>
                <div>
                    <div class="text-4xl font-bold">100%</div>
                    <div class="mt-2 text-indigo-200">Personalizado</div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

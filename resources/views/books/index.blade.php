<x-layout title="Catálogo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Catálogo de Libros</h1>
            <p class="text-gray-500">{{ $books->total() }} libros encontrados</p>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('books.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título o autor..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-full sm:w-48">
                    <select name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->books_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-40">
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todo estado</option>
                        <option value="Available" {{ request('status') === 'Available' ? 'selected' : '' }}>Disponible</option>
                        <option value="Checked Out" {{ request('status') === 'Checked Out' ? 'selected' : '' }}>Prestado</option>
                        <option value="Lost" {{ request('status') === 'Lost' ? 'selected' : '' }}>Perdido</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Buscar
                </button>
                @if(request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('books.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        {{-- Books grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($books as $book)
                <a href="{{ route('books.show', $book) }}" class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg hover:border-indigo-200 transition group">
                    <div class="flex items-center justify-center w-full h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg mb-4">
                        <span class="text-4xl">{{ $book->primaryCategory?->icon ?? '📖' }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition line-clamp-2 text-sm">{{ $book->title }}</h3>
                    @if($book->author)
                        <p class="text-xs text-gray-500 mt-1 truncate">{{ $book->author }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-3">
                        @if($book->primaryCategory)
                            <span class="text-xs text-gray-400 truncate">{{ $book->primaryCategory->name }}</span>
                        @endif
                        <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $book->status === 'Available' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $book->status === 'Checked Out' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $book->status === 'Lost' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $book->status === 'Available' ? 'Disponible' : ($book->status === 'Checked Out' ? 'Prestado' : 'Perdido') }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No se encontraron libros con esos criterios.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $books->links() }}
        </div>
    </div>
</x-layout>

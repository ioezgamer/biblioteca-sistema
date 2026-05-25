<x-layout title="{{ $book->title }}">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('books.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-6 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Volver al catálogo
        </a>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
                {{-- Book cover placeholder --}}
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-12 flex items-center justify-center">
                    <div class="text-center text-white">
                        <span class="text-6xl">{{ $book->primaryCategory?->icon ?? '📖' }}</span>
                        <p class="mt-4 text-indigo-200 text-sm">{{ $book->primaryCategory?->name ?? 'Libro' }}</p>
                    </div>
                </div>

                {{-- Book details --}}
                <div class="md:col-span-2 p-8">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $book->title }}</h1>
                    @if($book->author)
                        <p class="text-lg text-gray-600 mt-2">por {{ $book->author }}</p>
                    @endif

                    <div class="flex flex-wrap gap-2 mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $book->status === 'Available' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $book->status === 'Checked Out' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $book->status === 'Lost' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $book->status === 'Available' ? 'Disponible' : ($book->status === 'Checked Out' ? 'Prestado' : 'Perdido') }}
                        </span>
                        @foreach($book->categories as $cat)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-50 text-indigo-700">
                                {{ $cat->icon }} {{ $cat->name }}
                            </span>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-6 text-sm">
                        @if($book->call_number)
                            <div>
                                <span class="text-gray-500">Clasificación:</span>
                                <span class="font-medium text-gray-900 ml-1">{{ $book->call_number }}</span>
                            </div>
                        @endif
                        @if($book->barcode)
                            <div>
                                <span class="text-gray-500">Código:</span>
                                <span class="font-medium text-gray-900 ml-1">{{ $book->barcode }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="text-gray-500">Tipo:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $book->material_type }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Circulaciones:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $book->total_circulations }}</span>
                        </div>
                    </div>

                    @if($book->description)
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Descripción</h3>
                            <p class="text-gray-600 text-sm">{{ $book->description }}</p>
                        </div>
                    @endif

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('reading-evaluation.create', $book) }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                            Hacer Evaluación de Lectura
                        </a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.questions.index', $book) }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-sm">
                                Gestionar Preguntas ({{ $book->evaluationQuestions->count() }})
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Books --}}
        @if($relatedBooks->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Libros relacionados</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($relatedBooks as $related)
                        <a href="{{ route('books.show', $related) }}" class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
                            <h3 class="font-medium text-gray-900 text-sm line-clamp-2">{{ $related->title }}</h3>
                            @if($related->author)
                                <p class="text-xs text-gray-500 mt-1">{{ $related->author }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layout>

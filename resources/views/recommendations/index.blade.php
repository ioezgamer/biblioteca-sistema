<x-layout title="Recomendaciones">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mis Recomendaciones</h1>
                <p class="mt-1 text-gray-600">Libros seleccionados especialmente para ti</p>
            </div>
            <form method="POST" action="{{ route('recommendations.refresh') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Nuevas Recomendaciones
                </button>
            </form>
        </div>

        {{-- Status Filters --}}
        <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
            @foreach([
                ['value' => 'all', 'label' => 'Todas'],
                ['value' => 'pending', 'label' => 'Pendientes'],
                ['value' => 'reading', 'label' => 'Leyendo'],
                ['value' => 'completed', 'label' => 'Completadas'],
                ['value' => 'rejected', 'label' => 'Descartadas'],
            ] as $filter)
                <a href="{{ route('recommendations.index', ['status' => $filter['value']]) }}"
                    class="shrink-0 px-4 py-2 rounded-full text-sm font-medium transition
                    {{ $status === $filter['value'] ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                    {{ $filter['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Recommendations Grid --}}
        @if($recommendations->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <p class="text-gray-500 mt-4">No hay recomendaciones con ese filtro.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($recommendations as $rec)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition">
                        <div class="h-28 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                            <span class="text-4xl">{{ $rec->book->primaryCategory?->icon ?? '📖' }}</span>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-start gap-2">
                                <a href="{{ route('books.show', $rec->book) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition line-clamp-2">
                                    {{ $rec->book->title }}
                                </a>
                                <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $rec->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $rec->status === 'reading' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $rec->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $rec->status === 'rejected' ? 'bg-gray-100 text-gray-600' : '' }}
                                    {{ $rec->status === 'accepted' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ $rec->status === 'pending' ? 'Pendiente' : '' }}
                                    {{ $rec->status === 'reading' ? 'Leyendo' : '' }}
                                    {{ $rec->status === 'completed' ? 'Completado' : '' }}
                                    {{ $rec->status === 'rejected' ? 'Descartado' : '' }}
                                    {{ $rec->status === 'accepted' ? 'Aceptado' : '' }}
                                </span>
                            </div>
                            @if($rec->book->author)
                                <p class="text-sm text-gray-500 mt-1">{{ $rec->book->author }}</p>
                            @endif
                            @if($rec->reason)
                                <p class="text-xs text-gray-400 mt-2">{{ $rec->reason }}</p>
                            @endif

                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($rec->book->categories as $cat)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $cat->icon }} {{ $cat->name }}</span>
                                @endforeach
                            </div>

                            <div class="mt-4 flex gap-2">
                                @if($rec->status === 'pending')
                                    <form method="POST" action="{{ route('recommendations.accept', $rec) }}">
                                        @csrf
                                        <button class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">Comenzar a leer</button>
                                    </form>
                                    <form method="POST" action="{{ route('recommendations.reject', $rec) }}">
                                        @csrf
                                        <button class="px-4 py-2 bg-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-300 transition">Descartar</button>
                                    </form>
                                @elseif($rec->status === 'reading')
                                    <form method="POST" action="{{ route('recommendations.complete', $rec) }}">
                                        @csrf
                                        <button class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">Terminé de leer</button>
                                    </form>
                                @elseif($rec->status === 'completed')
                                    <a href="{{ route('reading-evaluation.create', $rec->book) }}" class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                        Hacer Evaluación
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $recommendations->links() }}
            </div>
        @endif
    </div>
</x-layout>

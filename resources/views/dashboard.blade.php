<x-layout title="Dashboard">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Hola, {{ auth()->user()->name }}!</h1>
            <p class="mt-1 text-gray-600">Aquí tienes un resumen de tu actividad de lectura.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="text-2xl font-bold text-indigo-600">{{ $stats['total_recommendations'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Recomendaciones</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="text-2xl font-bold text-amber-600">{{ $stats['books_reading'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Leyendo ahora</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="text-2xl font-bold text-green-600">{{ $stats['books_completed'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Completados</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['evaluations_passed'] }}/{{ $stats['evaluations_total'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Evaluaciones aprobadas</div>
            </div>
        </div>

        {{-- Library Stats --}}
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 text-white mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-semibold">Biblioteca</h2>
                    <p class="text-indigo-100">{{ number_format($totalBooks) }} libros en total &middot; {{ number_format($availableBooks) }} disponibles</p>
                </div>
                <a href="{{ route('books.index') }}" class="px-5 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition text-sm font-medium">
                    Explorar Catálogo
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Recommendations --}}
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Tus Recomendaciones</h2>
                    <a href="{{ route('recommendations.index') }}" class="text-sm text-indigo-600 hover:underline">Ver todas</a>
                </div>

                @if($recommendations->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                        <p class="text-gray-500">Aún no tienes recomendaciones. Estamos preparando las mejores para ti.</p>
                        <form method="POST" action="{{ route('recommendations.refresh') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                                Generar Recomendaciones
                            </button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($recommendations as $rec)
                            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ route('books.show', $rec->book) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition line-clamp-2">
                                            {{ $rec->book->title }}
                                        </a>
                                        @if($rec->book->author)
                                            <p class="text-sm text-gray-500 mt-1 truncate">{{ $rec->book->author }}</p>
                                        @endif
                                    </div>
                                    <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $rec->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $rec->status === 'reading' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $rec->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $rec->status === 'rejected' ? 'bg-gray-100 text-gray-600' : '' }}">
                                        {{ $rec->status === 'pending' ? 'Pendiente' : '' }}
                                        {{ $rec->status === 'reading' ? 'Leyendo' : '' }}
                                        {{ $rec->status === 'completed' ? 'Completado' : '' }}
                                        {{ $rec->status === 'rejected' ? 'Descartado' : '' }}
                                    </span>
                                </div>
                                @if($rec->book->primaryCategory)
                                    <span class="inline-block mt-2 text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        {{ $rec->book->primaryCategory->icon }} {{ $rec->book->primaryCategory->name }}
                                    </span>
                                @endif
                                @if($rec->reason)
                                    <p class="text-xs text-gray-400 mt-2">{{ $rec->reason }}</p>
                                @endif
                                <div class="mt-3 flex gap-2">
                                    @if($rec->status === 'pending')
                                        <form method="POST" action="{{ route('recommendations.accept', $rec) }}">
                                            @csrf
                                            <button class="px-3 py-1 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700 transition">Leer</button>
                                        </form>
                                        <form method="POST" action="{{ route('recommendations.reject', $rec) }}">
                                            @csrf
                                            <button class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded-lg hover:bg-gray-300 transition">Descartar</button>
                                        </form>
                                    @elseif($rec->status === 'reading')
                                        <form method="POST" action="{{ route('recommendations.complete', $rec) }}">
                                            @csrf
                                            <button class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">Terminé de leer</button>
                                        </form>
                                    @elseif($rec->status === 'completed')
                                        <a href="{{ route('reading-evaluation.create', $rec->book) }}" class="px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition">
                                            Hacer Evaluación
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent Evaluations --}}
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Evaluaciones Recientes</h2>
                    <a href="{{ route('reading-evaluations.index') }}" class="text-sm text-indigo-600 hover:underline">Ver todas</a>
                </div>

                @if($recentEvaluations->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                        <p class="text-gray-500 text-sm">Aún no has completado evaluaciones de lectura.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentEvaluations as $eval)
                            <a href="{{ route('reading-evaluation.result', $eval) }}" class="block bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start gap-2">
                                    <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $eval->book->title }}</p>
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $eval->passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $eval->score }}%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $eval->created_at->diffForHumans() }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>

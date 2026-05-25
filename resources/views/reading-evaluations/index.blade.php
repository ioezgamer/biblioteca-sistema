<x-layout title="Mis Evaluaciones">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mis Evaluaciones de Lectura</h1>

        @if($evaluations->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-500 mt-4">Aún no has completado ninguna evaluación.</p>
                <a href="{{ route('recommendations.index') }}" class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Ver mis recomendaciones
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($evaluations as $eval)
                    <a href="{{ route('reading-evaluation.result', $eval) }}" class="block bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900">{{ $eval->book->title }}</h3>
                                @if($eval->book->author)
                                    <p class="text-sm text-gray-500">{{ $eval->book->author }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $eval->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="text-2xl font-bold {{ $eval->passed ? 'text-green-600' : 'text-red-600' }}">{{ $eval->score }}%</div>
                                    <div class="text-xs text-gray-500">{{ $eval->correct_answers }}/{{ $eval->total_questions }} correctas</div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $eval->passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $eval->passed ? 'Aprobado' : 'No aprobado' }}
                                </span>
                            </div>
                        </div>
                        @if($eval->feedback)
                            <p class="text-sm text-gray-600 mt-3 border-t border-gray-100 pt-3">{{ $eval->feedback }}</p>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $evaluations->links() }}
            </div>
        @endif
    </div>
</x-layout>

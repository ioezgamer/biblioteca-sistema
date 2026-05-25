<x-layout title="Resultado de Evaluación">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Score Card --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-8">
            <div class="p-8 text-center {{ $readingEvaluation->passed ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-red-500 to-rose-600' }} text-white">
                <div class="text-6xl font-bold">{{ $readingEvaluation->score }}%</div>
                <div class="mt-2 text-lg font-medium">{{ $readingEvaluation->passed ? '¡Aprobado!' : 'No aprobado' }}</div>
                <div class="mt-1 text-sm opacity-80">{{ $readingEvaluation->correct_answers }} de {{ $readingEvaluation->total_questions }} respuestas correctas</div>
            </div>
            <div class="p-6">
                <h2 class="font-semibold text-gray-900">{{ $readingEvaluation->book->title }}</h2>
                @if($readingEvaluation->book->author)
                    <p class="text-sm text-gray-500">por {{ $readingEvaluation->book->author }}</p>
                @endif
                @if($readingEvaluation->feedback)
                    <div class="mt-4 p-4 rounded-lg {{ $readingEvaluation->passed ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
                        <p class="text-sm">{{ $readingEvaluation->feedback }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Answers Review --}}
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Revisión de Respuestas</h2>
        <div class="space-y-4">
            @foreach($readingEvaluation->answers as $index => $answer)
                <div class="bg-white rounded-xl border border-gray-200 p-5 {{ $answer->is_correct ? 'border-l-4 border-l-green-500' : 'border-l-4 border-l-red-500' }}">
                    <div class="flex items-start gap-3">
                        <span class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $answer->is_correct ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $answer->is_correct ? '✓' : '✗' }}
                        </span>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 text-sm">{{ $answer->question->question }}</p>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm"><span class="text-gray-500">Tu respuesta:</span> <span class="{{ $answer->is_correct ? 'text-green-700' : 'text-red-700' }} font-medium">{{ $answer->answer }}</span></p>
                                @if(!$answer->is_correct)
                                    <p class="text-sm"><span class="text-gray-500">Respuesta correcta:</span> <span class="text-green-700 font-medium">{{ $answer->question->correct_answer }}</span></p>
                                @endif
                                @if($answer->question->explanation)
                                    <p class="text-xs text-gray-400 mt-1">{{ $answer->question->explanation }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-wrap gap-4 justify-center">
            @if(!$readingEvaluation->passed)
                <a href="{{ route('reading-evaluation.create', $readingEvaluation->book) }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-medium">
                    Intentar de nuevo
                </a>
            @endif
            <a href="{{ route('recommendations.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition font-medium">
                Ver recomendaciones
            </a>
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition font-medium">
                Ir al Dashboard
            </a>
        </div>
    </div>
</x-layout>

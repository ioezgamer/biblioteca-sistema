<x-layout title="Preguntas: {{ $book->title }}">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-start mb-8">
            <div>
                <a href="{{ route('books.show', $book) }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">&larr; Volver al libro</a>
                <h1 class="text-2xl font-bold text-gray-900 mt-2">Preguntas de Evaluación</h1>
                <p class="text-gray-600">{{ $book->title }}</p>
            </div>
            <a href="{{ route('admin.questions.create', $book) }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                + Nueva Pregunta
            </a>
        </div>

        @if($questions->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <p class="text-gray-500">No hay preguntas configuradas para este libro.</p>
                <p class="text-sm text-gray-400 mt-1">Se usarán preguntas genéricas automáticamente.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($questions as $question)
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold">{{ $question->order }}</span>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                        {{ $question->type === 'multiple_choice' ? 'Opción Múltiple' : ($question->type === 'true_false' ? 'V/F' : 'Abierta') }}
                                    </span>
                                    <span class="text-xs text-gray-400">Dificultad: {{ $question->difficulty }}/5</span>
                                </div>
                                <p class="mt-2 font-medium text-gray-900">{{ $question->question }}</p>
                                <p class="text-sm text-green-600 mt-1">Respuesta: {{ $question->correct_answer }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.questions.edit', [$book, $question]) }}" class="px-3 py-1 bg-gray-200 text-gray-600 text-xs rounded-lg hover:bg-gray-300 transition">Editar</a>
                                <form method="POST" action="{{ route('admin.questions.destroy', [$book, $question]) }}" onsubmit="return confirm('¿Eliminar esta pregunta?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1 bg-red-100 text-red-600 text-xs rounded-lg hover:bg-red-200 transition">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>

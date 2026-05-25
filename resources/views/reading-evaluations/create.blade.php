<x-layout title="Evaluación: {{ $book->title }}">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('books.show', $book) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-6 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Volver al libro
        </a>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Evaluación de Lectura</h1>
            <p class="mt-2 text-gray-600">{{ $book->title }}</p>
            @if($book->author)
                <p class="text-sm text-gray-500">por {{ $book->author }}</p>
            @endif
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8">
            <p class="text-sm text-amber-800">
                <strong>Instrucciones:</strong> Responde todas las preguntas basándote en tu lectura del libro.
                Necesitas un 60% de respuestas correctas para aprobar.
            </p>
        </div>

        <form method="POST" action="{{ route('reading-evaluation.store', $book) }}" class="space-y-6">
            @csrf

            @foreach($questions as $index => $question)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <span class="shrink-0 w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-bold">{{ $index + 1 }}</span>
                        <p class="font-medium text-gray-900">{{ $question->question }}</p>
                    </div>

                    @if($question->type === 'multiple_choice')
                        <div class="space-y-2 ml-11">
                            @php $options = is_array($question->options) ? $question->options : json_decode($question->options, true); @endphp
                            @if($options)
                                @foreach($options as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" class="text-indigo-600" required>
                                        <span class="text-sm text-gray-700">{{ $option }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    @elseif($question->type === 'true_false')
                        <div class="space-y-2 ml-11">
                            @php $options = is_array($question->options) ? $question->options : json_decode($question->options, true); @endphp
                            @if($options)
                                @foreach($options as $option)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" class="text-indigo-600" required>
                                        <span class="text-sm text-gray-700">{{ $option }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    @elseif($question->type === 'open_ended')
                        <div class="ml-11">
                            <textarea name="answers[{{ $question->id }}]" rows="3" required
                                placeholder="Escribe tu respuesta aquí..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                        </div>
                    @endif

                    @error("answers.{$question->id}")
                        <p class="mt-2 ml-11 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="flex justify-center pt-4">
                <button type="submit" class="px-10 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-lg">
                    Enviar Evaluación
                </button>
            </div>
        </form>
    </div>
</x-layout>

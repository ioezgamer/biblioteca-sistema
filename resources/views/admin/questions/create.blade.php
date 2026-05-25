<x-layout title="Nueva Pregunta">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('admin.questions.index', $book) }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">&larr; Volver a preguntas</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-2">Nueva Pregunta</h1>
        <p class="text-gray-600 mb-8">{{ $book->title }}</p>

        <form method="POST" action="{{ route('admin.questions.store', $book) }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pregunta</label>
                    <textarea name="question" rows="2" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('question') }}</textarea>
                    @error('question') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select name="type" id="question-type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="multiple_choice" {{ old('type') === 'multiple_choice' ? 'selected' : '' }}>Opción Múltiple</option>
                            <option value="true_false" {{ old('type') === 'true_false' ? 'selected' : '' }}>Verdadero/Falso</option>
                            <option value="open_ended" {{ old('type') === 'open_ended' ? 'selected' : '' }}>Respuesta Abierta</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dificultad (1-5)</label>
                        <input type="number" name="difficulty" min="1" max="5" value="{{ old('difficulty', 1) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div id="options-container">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opciones (una por campo)</label>
                    <div class="space-y-2">
                        <input type="text" name="options[]" placeholder="Opción 1" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <input type="text" name="options[]" placeholder="Opción 2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <input type="text" name="options[]" placeholder="Opción 3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <input type="text" name="options[]" placeholder="Opción 4" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Respuesta Correcta</label>
                    <input type="text" name="correct_answer" value="{{ old('correct_answer') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('correct_answer') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Explicación (opcional)</label>
                    <textarea name="explanation" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('explanation') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.questions.index', $book) }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancelar</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">Guardar Pregunta</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('question-type').addEventListener('change', function() {
            document.getElementById('options-container').style.display = this.value === 'open_ended' ? 'none' : 'block';
        });
    </script>
</x-layout>

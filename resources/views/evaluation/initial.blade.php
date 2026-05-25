<x-layout title="Evaluación Inicial">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Evaluación Inicial</h1>
            <p class="mt-3 text-lg text-gray-600">Cuéntanos sobre tus gustos e intereses para recomendarte los mejores libros</p>
        </div>

        <form method="POST" action="{{ route('evaluation.initial.store') }}" class="space-y-8" id="evaluation-form">
            @csrf

            {{-- Step 1: Reading Level --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    ¿Cuál es tu nivel de lectura?
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach([
                        ['value' => 'principiante', 'label' => 'Principiante', 'desc' => 'Estoy comenzando a leer o prefiero textos simples'],
                        ['value' => 'intermedio', 'label' => 'Intermedio', 'desc' => 'Leo con regularidad textos de complejidad media'],
                        ['value' => 'avanzado', 'label' => 'Avanzado', 'desc' => 'Leo textos complejos y variados con facilidad'],
                        ['value' => 'fluido', 'label' => 'Fluido', 'desc' => 'Lector experimentado, disfruto cualquier tipo de texto'],
                    ] as $level)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="reading_level" value="{{ $level['value'] }}" class="peer sr-only" {{ old('reading_level') === $level['value'] ? 'checked' : '' }} required>
                            <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition">
                                <p class="font-semibold text-gray-900">{{ $level['label'] }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $level['desc'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('reading_level')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Step 2: Age Group --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                    ¿A qué grupo de edad perteneces?
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach([
                        ['value' => 'ninos', 'label' => 'Niños', 'desc' => 'Hasta 12 años', 'icon' => '🧒'],
                        ['value' => 'jovenes', 'label' => 'Jóvenes', 'desc' => '13 a 17 años', 'icon' => '🎒'],
                        ['value' => 'adultos', 'label' => 'Adultos', 'desc' => '18 años en adelante', 'icon' => '👤'],
                    ] as $age)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="age_group" value="{{ $age['value'] }}" class="peer sr-only" {{ old('age_group') === $age['value'] ? 'checked' : '' }} required>
                            <div class="p-5 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition text-center">
                                <span class="text-3xl">{{ $age['icon'] }}</span>
                                <p class="font-semibold text-gray-900 mt-2">{{ $age['label'] }}</p>
                                <p class="text-sm text-gray-500">{{ $age['desc'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('age_group')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Step 3: Language --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-bold">3</span>
                    ¿En qué idioma prefieres leer?
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach([
                        ['value' => 'es', 'label' => 'Español', 'icon' => '🇪🇸'],
                        ['value' => 'en', 'label' => 'Inglés', 'icon' => '🇺🇸'],
                        ['value' => 'ambos', 'label' => 'Ambos', 'icon' => '🌍'],
                    ] as $lang)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="preferred_language" value="{{ $lang['value'] }}" class="peer sr-only" {{ old('preferred_language', 'es') === $lang['value'] ? 'checked' : '' }} required>
                            <div class="p-5 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition text-center">
                                <span class="text-3xl">{{ $lang['icon'] }}</span>
                                <p class="font-semibold text-gray-900 mt-2">{{ $lang['label'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('preferred_language')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Step 4: Categories --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-bold">4</span>
                    Selecciona las categorías que te interesan
                </h2>
                <p class="text-gray-500 text-sm mb-6">Elige al menos 3 categorías (<span id="selected-count">0</span> seleccionadas)</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($categories as $category)
                        <label class="relative cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="peer sr-only category-checkbox"
                                {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                            <div class="p-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition text-center">
                                <span class="text-xl">{{ $category->icon ?? '📖' }}</span>
                                <p class="text-xs font-medium text-gray-700 mt-1 leading-tight">{{ $category->name }}</p>
                                <p class="text-xs text-gray-400">{{ $category->books_count }} libros</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('categories')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('categories.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Step 5: Goals --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-bold">5</span>
                    Objetivos de lectura
                </h2>
                <div class="space-y-4">
                    <div>
                        <label for="books_per_month" class="block text-sm font-medium text-gray-700 mb-1">¿Cuántos libros te gustaría leer al mes?</label>
                        <input type="number" name="books_per_month" id="books_per_month" min="1" max="20" value="{{ old('books_per_month', 2) }}" required
                            class="w-full max-w-xs px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('books_per_month')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="reading_goals" class="block text-sm font-medium text-gray-700 mb-1">¿Cuáles son tus metas de lectura? (opcional)</label>
                        <textarea name="reading_goals" id="reading_goals" rows="3" placeholder="Ej: Quiero mejorar mi vocabulario, explorar nuevos géneros..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('reading_goals') }}</textarea>
                        @error('reading_goals')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="px-12 py-4 bg-indigo-600 text-white text-lg font-semibold rounded-xl hover:bg-indigo-700 transition shadow-lg">
                    Completar Evaluación y Ver Recomendaciones
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.category-checkbox');
            const counter = document.getElementById('selected-count');
            function updateCount() {
                counter.textContent = document.querySelectorAll('.category-checkbox:checked').length;
            }
            checkboxes.forEach(cb => cb.addEventListener('change', updateCount));
            updateCount();
        });
    </script>
</x-layout>

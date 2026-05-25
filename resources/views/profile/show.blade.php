<x-layout title="Mi Perfil">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mi Perfil</h1>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['books_completed'] }}</div>
                <div class="text-xs text-gray-500">Completados</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['books_reading'] }}</div>
                <div class="text-xs text-gray-500">Leyendo</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-emerald-600">{{ $stats['evaluations_passed'] }}</div>
                <div class="text-xs text-gray-500">Eval. aprobadas</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['evaluations_failed'] }}</div>
                <div class="text-xs text-gray-500">Eval. reprobadas</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ number_format($stats['avg_score'], 1) }}%</div>
                <div class="text-xs text-gray-500">Puntaje promedio</div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h2>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full max-w-md px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Preferencias de Lectura</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de lectura</label>
                        <select name="reading_level" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach(['principiante' => 'Principiante', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado', 'fluido' => 'Fluido'] as $val => $label)
                                <option value="{{ $val }}" {{ old('reading_level', $profile?->reading_level) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grupo de edad</label>
                        <select name="age_group" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach(['ninos' => 'Niños', 'jovenes' => 'Jóvenes', 'adultos' => 'Adultos'] as $val => $label)
                                <option value="{{ $val }}" {{ old('age_group', $profile?->age_group) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Idioma preferido</label>
                        <select name="preferred_language" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            @foreach(['es' => 'Español', 'en' => 'Inglés', 'ambos' => 'Ambos'] as $val => $label)
                                <option value="{{ $val }}" {{ old('preferred_language', $profile?->preferred_language) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Libros por mes</label>
                        <input type="number" name="books_per_month" min="1" max="20" value="{{ old('books_per_month', $profile?->books_per_month ?? 2) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metas de lectura</label>
                    <textarea name="reading_goals" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('reading_goals', $profile?->reading_goals) }}</textarea>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Categorías de Interés</h2>
                <p class="text-sm text-gray-500 mb-4">Selecciona al menos 3 categorías</p>
                @php $selectedCats = $profile?->categories?->pluck('id')->toArray() ?? []; @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($categories as $category)
                        <label class="relative cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="peer sr-only"
                                {{ in_array($category->id, old('categories', $selectedCats)) ? 'checked' : '' }}>
                            <div class="p-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition text-center">
                                <span class="text-lg">{{ $category->icon ?? '📖' }}</span>
                                <p class="text-xs font-medium text-gray-700 mt-1">{{ $category->name }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('categories') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-layout>

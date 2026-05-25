<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/books.json');
        $books = json_decode(file_get_contents($jsonPath), true);

        $categoryDescriptions = [
            'Cuentos Ilustrados' => 'Historias con bellas ilustraciones para todas las edades',
            'Ficción' => 'Obras de ficción y narrativa creativa',
            'Novelas' => 'Novelas completas de diversos géneros',
            'Historia' => 'Libros sobre historia mundial y regional',
            'Ciencia General' => 'Temas científicos generales',
            'Animales' => 'El mundo animal y la naturaleza',
            'Cómicos' => 'Historietas y cómics',
            'Novelas Gráficas' => 'Novelas en formato gráfico e ilustrado',
            'Autoayuda' => 'Desarrollo personal y autoayuda',
            'Biografía' => 'Biografías y autobiografías',
            'Poesía' => 'Poesía y verso en diversos estilos',
            'Religión' => 'Textos religiosos y espirituales',
            'Cocina' => 'Recetas y gastronomía',
            'Música' => 'Música, instrumentos y artistas',
            'Deportes y Juegos' => 'Deportes, juegos y actividades físicas',
            'Arte y Manualidades' => 'Arte, manualidades y creatividad',
            'Tecnología' => 'Tecnología e invenciones',
            'Sociedad' => 'Temas sociales y cultura',
            'Salud' => 'Salud, bienestar y medicina',
            'Geografía' => 'Geografía y viajes',
            'Espacio y Planetas' => 'Astronomía, espacio y planetas',
            'Tierra y Naturaleza' => 'Naturaleza, ecosistemas y medio ambiente',
            'Mar y Océano' => 'Vida marina y océanos',
            'Cuerpo Humano' => 'Anatomía y el cuerpo humano',
            'Referencia' => 'Material de referencia y consulta',
            'Pre-escolar' => 'Libros para niños en edad pre-escolar',
            'Lectores Emergentes' => 'Para lectores que están comenzando (niveles AA-D)',
            'Lectores Principiantes' => 'Para lectores principiantes (niveles E-I)',
            'Lectores Transicionales' => 'Para lectores en transición (niveles J-M)',
            'Lectores Intermedios' => 'Para lectores intermedios y avanzados',
            'Lectores Adolescentes' => 'Lectura para jóvenes y adolescentes',
            'Lectores Fluidos' => 'Para lectores fluidos y avanzados',
            'Literatura' => 'Obras literarias clásicas y contemporáneas',
            'Literatura Nicaragüense' => 'Literatura de autores nicaragüenses',
            'Matemáticas' => 'Matemáticas y finanzas',
            'Política' => 'Política y gobierno',
            'Carreras' => 'Orientación profesional y carreras',
            'Crianza' => 'Crianza, familia y educación de hijos',
            'Textos Estudiantiles' => 'Material educativo y textos escolares',
            'Infomanía' => 'Datos curiosos y conocimiento general',
            'Colecciones de Cuentos' => 'Antologías y colecciones de cuentos',
            'Fábulas y Leyendas' => 'Fábulas, mitos y leyendas',
            'Aprender a Leer' => 'Material para aprender a leer',
            'Belleza y Salud' => 'Belleza, cuidado personal y salud',
            'Inglés' => 'Libros en idioma inglés',
            'Inglés Ilustrados' => 'Libros ilustrados en inglés',
            'Inglés Ficción' => 'Ficción en idioma inglés',
            'Inglés Pre-escolar' => 'Libros pre-escolares en inglés',
            'Inglés Jóvenes' => 'Lectura juvenil en inglés',
            'Inglés Adultos' => 'Lectura para adultos en inglés',
        ];

        $categoryIcons = [
            'Cuentos Ilustrados' => '📖',
            'Ficción' => '🎭',
            'Novelas' => '📕',
            'Historia' => '🏛️',
            'Ciencia General' => '🔬',
            'Animales' => '🐾',
            'Cómicos' => '💬',
            'Autoayuda' => '🌟',
            'Biografía' => '👤',
            'Poesía' => '🖊️',
            'Religión' => '🕊️',
            'Cocina' => '🍳',
            'Música' => '🎵',
            'Deportes y Juegos' => '⚽',
            'Arte y Manualidades' => '🎨',
            'Tecnología' => '💻',
            'Sociedad' => '🌍',
            'Salud' => '❤️',
            'Geografía' => '🗺️',
            'Espacio y Planetas' => '🚀',
            'Tierra y Naturaleza' => '🌿',
            'Mar y Océano' => '🌊',
            'Cuerpo Humano' => '🧬',
            'Referencia' => '📚',
            'Pre-escolar' => '🧒',
            'Lectores Emergentes' => '🌱',
            'Lectores Principiantes' => '📗',
            'Lectores Transicionales' => '📘',
            'Lectores Intermedios' => '📙',
            'Lectores Adolescentes' => '🎒',
            'Literatura' => '📜',
            'Matemáticas' => '🔢',
            'Política' => '⚖️',
            'Carreras' => '💼',
            'Crianza' => '👶',
            'Textos Estudiantiles' => '🎓',
            'Infomanía' => '💡',
            'Novelas Gráficas' => '🖼️',
            'Inglés' => '🇺🇸',
            'Inglés Ilustrados' => '🇺🇸',
            'Inglés Ficción' => '🇺🇸',
            'Inglés Pre-escolar' => '🇺🇸',
            'Inglés Jóvenes' => '🇺🇸',
        ];

        // Create categories
        $categoryModels = [];
        $allCatNames = array_unique(array_filter(array_column($books, 'category_name')));
        sort($allCatNames);

        foreach ($allCatNames as $catName) {
            $cat = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                [
                    'name' => $catName,
                    'description' => $categoryDescriptions[$catName] ?? null,
                    'icon' => $categoryIcons[$catName] ?? '📖',
                ]
            );
            $categoryModels[$catName] = $cat;
        }

        $this->command->info("Created " . count($categoryModels) . " categories");

        // Insert books in chunks
        $chunks = array_chunk($books, 500);
        $totalInserted = 0;

        foreach ($chunks as $chunk) {
            foreach ($chunk as $bookData) {
                $categoryId = null;
                if ($bookData['category_name'] && isset($categoryModels[$bookData['category_name']])) {
                    $categoryId = $categoryModels[$bookData['category_name']]->id;
                }

                $book = Book::create([
                    'title' => $bookData['title'],
                    'author' => $bookData['author'],
                    'call_number' => $bookData['call_number'],
                    'barcode' => $bookData['barcode'],
                    'status' => $bookData['status'],
                    'total_circulations' => $bookData['total_circulations'],
                    'category_id' => $categoryId,
                    'copy_price' => $bookData['copy_price'],
                    'currency_code' => $bookData['currency_code'],
                ]);

                if ($categoryId) {
                    $book->categories()->attach($categoryId);
                }

                $totalInserted++;
            }

            $this->command->info("Inserted {$totalInserted} books...");
        }

        $this->command->info("Total: {$totalInserted} books imported successfully!");
    }
}

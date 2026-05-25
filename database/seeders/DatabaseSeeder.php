<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@biblioteca.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'has_completed_evaluation' => true,
        ]);

        User::factory()->create([
            'name' => 'Participante Demo',
            'email' => 'demo@biblioteca.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
            'has_completed_evaluation' => false,
        ]);

        $this->call([
            BookSeeder::class,
        ]);
    }
}

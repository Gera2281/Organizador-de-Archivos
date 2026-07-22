<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Escuela;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $escuelas = [
/*            ['numero_escuela' => '14984984', 'ctt' => 'Ct2514c51'],
            ['numero_escuela' => '14984985', 'ctt' => 'Ct2514c52'],
            ['numero_escuela' => '14984986', 'ctt' => 'Ct2514c53'],
            ['numero_escuela' => '12345678', 'ctt' => 'Ct123456a'],
            ['numero_escuela' => '87654321', 'ctt' => 'Ct876543b'],
            ['numero_escuela' => '11112222', 'ctt' => 'Ct111122c'],
            ['numero_escuela' => '33334444', 'ctt' => 'Ct333344d'],
            ['numero_escuela' => '55556666', 'ctt' => 'Ct555566e'],
            ['numero_escuela' => '77778888', 'ctt' => 'Ct777788f'],
            ['numero_escuela' => '99990000', 'ctt' => 'Ct999900g'],
            ['numero_escuela' => '15151515', 'ctt' => 'Ct151515h'],
            ['numero_escuela' => '26262626', 'ctt' => 'Ct262626i'],*/
        ];

        foreach ($escuelas as $escuela) {
            Escuela::firstOrCreate(
                ['numero_escuela' => $escuela['numero_escuela']],
                ['ctt' => $escuela['ctt']]
            );
        }
    }
}

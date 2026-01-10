<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServiceCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ServiceCategorie::factory()->count(5)->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EstadosTableSeeder::class,
            CidadesTableSeeder::class,
            ConfigTableSeeder::class,
            UsersTableSeeder::class,                        
            EmpresasTableSeeder::class,                        
            CatPostsTableSeeder::class,
            PostsTableSeeder::class,
        ]);
    }
}

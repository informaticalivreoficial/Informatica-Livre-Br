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
            ConfigTableSeeder::class,
            UsersTableSeeder::class,
            EstadosTableSeeder::class,
            CidadesTableSeeder::class,            
            CatPostsTableSeeder::class,
            PostsTableSeeder::class,
        ]);
    }
}

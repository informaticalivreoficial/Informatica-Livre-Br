<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => env('ADMIN_NOME'),
                'email' => env('ADMIN_EMAIL'),
                'email_verified_at' => now(),
                'password' => bcrypt(env('ADMIN_PASS')),
                'code' => env('ADMIN_PASS'),
                'remember_token' => \Illuminate\Support\Str::random(10),                
                'created_at' => now(),//Data e hora Atual                
                'superadmin' => true,
                'status' => 1
            ]            
        ]);

        \App\Models\User::factory()->count(20)->create();
    }
}

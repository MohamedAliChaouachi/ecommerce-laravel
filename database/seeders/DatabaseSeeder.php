<?php

namespace Database\Seeders;

use App\Models\User;
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
    // Create admin user
    User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'is_admin' => true,
    ]);

    // Create regular test user
    User::create([
        'name' => 'Test User',
        'email' => 'user@example.com',
        'password' => bcrypt('password'),
        'is_admin' => false,
    ]);

    $this->call([
        CategorySeeder::class,
        ProductSeeder::class,
    ]);
}

}

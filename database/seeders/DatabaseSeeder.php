<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Jo',
            'email' => 'jo@mail.com',
            'password' => Hash::make('123456'),
        ]);

        // Category::factory(10)->create();

        Category::factory()->create([
            ['name' => 'Home'],
            ['name' => 'Work'],
        ]);
    }
}

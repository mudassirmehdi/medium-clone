<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Will Smith',
            'username' => 'will-smith',
            'email' => 'wsmith@gmail.com', 
        ]);

         $categories = [
            'Technology',
            'Health',
            'Science',
            'Sports',
            'Politics',
            'Entertainment',
         ];

         foreach ($categories as $category) { 
            Category::create([
                'name'=> $category, 
            ]);
         }

        //  Post::factory()->count(20)->create();    
    }
}

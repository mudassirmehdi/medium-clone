<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MediumDemoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Countries
        |--------------------------------------------------------------------------
        */

        $countries = [
            'Pakistan',
            'United States',
            'United Kingdom',
            'Canada',
            'Australia',
            'India',
            'Germany',
            'France',
            'Italy',
            'Spain',
            'Netherlands',
            'Sweden',
            'Norway',
            'Denmark',
            'Brazil',
            'Mexico',
            'Japan',
            'South Korea',
            'China',
            'Indonesia',
            'Malaysia',
            'Singapore',
            'United Arab Emirates',
            'Saudi Arabia',
            'Turkey',
            'South Africa',
            'New Zealand',
            'Ireland',
            'Switzerland',
            'Portugal',
        ];

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categoryNames = [
            'Technology',
            'Health',
            'Science',
            'Sports',
            'Politics',
            'Entertainment',
        ];

        $categoryIds = [];

        foreach ($categoryNames as $name) {
            $id = DB::table('categories')
                ->where('name', $name)
                ->value('id');

            if (!$id) {
                $id = DB::table('categories')->insertGetId([
                    'name' => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $categoryIds[] = $id;
        }

        /*
        |--------------------------------------------------------------------------
        | Create 200 demo users
        |--------------------------------------------------------------------------
        */

        $password = Hash::make('Password123!');

        for ($i = 1; $i <= 200; $i++) {
            $email = sprintf(
                'medium.demo.%04d@example.test',
                $i
            );

            $existingUser = DB::table('users')
                ->where('email', $email)
                ->first();

            if ($existingUser) {
                // Update existing demo users without replacing their passwords.
                DB::table('users')
                    ->where('id', $existingUser->id)
                    ->update([
                        'country' => $faker->randomElement($countries),
                        'updated_at' => $now,
                    ]);

                continue;
            }

            $username = sprintf('demo_writer_%04d', $i);

            while (
                DB::table('users')
                    ->where('username', $username)
                    ->exists()
            ) {
                $username = 'demo_' . Str::lower(Str::random(12));
            }

            DB::table('users')->insert([
                'name' => $faker->name(),
                'username' => $username,
                'email' => $email,
                'email_verified_at' => $now,
                'image' => null,
                'country' => $faker->randomElement($countries),
                'bio' => $faker->sentence(12),
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $demoUsers = DB::table('users')
            ->where('email', 'like', 'medium.demo.%@example.test')
            ->orderBy('email')
            ->get(['id', 'username', 'email']);

        if ($demoUsers->count() !== 200) {
            throw new \RuntimeException(
                'Expected 200 demo users. Check conflicting demo accounts.'
            );
        }

        $demoUserIds = $demoUsers->pluck('id')->all();

        /*
        |--------------------------------------------------------------------------
        | Assign existing local profile image paths
        |--------------------------------------------------------------------------
        |
        | No images are downloaded or created.
        | These paths must match your existing image filenames.
        |
        */

        foreach ($demoUsers as $user) {
            $avatarPath = "demo/avatars/user-{$user->id}.jpg";

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'image' => $avatarPath,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create posts until there are 400 demo posts
        |--------------------------------------------------------------------------
        */

        $postCount = DB::table('posts')
            ->whereIn('user_id', $demoUserIds)
            ->count();

        while ($postCount < 400) {
            $title = $faker->sentence(6);
            $slug = Str::slug($title);
            $baseSlug = $slug;

            while (
                DB::table('posts')
                    ->where('slug', $slug)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . Str::lower(Str::random(6));
            }

            DB::table('posts')->insert([
                'image' => null,
                'title' => $title,
                'slug' => $slug,
                'content' => implode(
                    "\n\n",
                    $faker->paragraphs(5)
                ),
                'category_id' => $faker->randomElement($categoryIds),
                'user_id' => $faker->randomElement($demoUserIds),
                'published_at' => $faker
                    ->dateTimeBetween('-2 years', 'now')
                    ->format('Y-m-d H:i:s'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $postCount++;
        }

        /*
        |--------------------------------------------------------------------------
        | Assign existing local post image paths
        |--------------------------------------------------------------------------
        |
        | No images are downloaded or created.
        | These paths must match your existing image filenames.
        |
        */

        $demoPosts = DB::table('posts')
            ->whereIn('user_id', $demoUserIds)
            ->orderBy('id')
            ->get(['id']);

        foreach ($demoPosts as $post) {
            $postImagePath = "demo/posts/post-{$post->id}.jpg";

            DB::table('posts')
                ->where('id', $post->id)
                ->update([
                    'image' => $postImagePath,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Add follower relationships without duplicates
        |--------------------------------------------------------------------------
        */

        $existingFollowers = DB::table('followers')
            ->whereIn('user_id', $demoUserIds)
            ->whereIn('follower_id', $demoUserIds)
            ->get(['user_id', 'follower_id']);

        $followerPairs = [];

        foreach ($existingFollowers as $row) {
            $followerPairs[$row->user_id . ':' . $row->follower_id] = true;
        }

        $followerRows = [];

        foreach ($demoUserIds as $userId) {
            $targetCount = random_int(5, 15);
            $created = 0;
            $attempts = 0;

            while ($created < $targetCount && $attempts < 200) {
                $attempts++;

                $followerId = $faker->randomElement($demoUserIds);

                if ($followerId == $userId) {
                    continue;
                }

                $key = $userId . ':' . $followerId;

                if (isset($followerPairs[$key])) {
                    continue;
                }

                $followerPairs[$key] = true;

                $followerRows[] = [
                    'user_id' => $userId,
                    'follower_id' => $followerId,
                    'created_at' => $now,
                ];

                $created++;
            }
        }

        foreach (array_chunk($followerRows, 500) as $chunk) {
            DB::table('followers')->insert($chunk);
        }

        /*
        |--------------------------------------------------------------------------
        | Add claps without duplicate user/post pairs
        |--------------------------------------------------------------------------
        */

        $demoPostIds = DB::table('posts')
            ->whereIn('user_id', $demoUserIds)
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $existingClaps = DB::table('claps')
            ->whereIn('post_id', $demoPostIds)
            ->whereIn('user_id', $demoUserIds)
            ->get(['post_id', 'user_id']);

        $clapPairs = [];

        foreach ($existingClaps as $row) {
            $clapPairs[$row->post_id . ':' . $row->user_id] = true;
        }

        $clapRows = [];

        foreach ($demoPostIds as $postId) {
            $targetCount = random_int(3, 12);
            $created = 0;
            $attempts = 0;

            while ($created < $targetCount && $attempts < 200) {
                $attempts++;

                $userId = $faker->randomElement($demoUserIds);
                $key = $postId . ':' . $userId;

                if (isset($clapPairs[$key])) {
                    continue;
                }

                $clapPairs[$key] = true;

                $clapRows[] = [
                    'post_id' => $postId,
                    'user_id' => $userId,
                    'created_at' => $now,
                ];

                $created++;
            }
        }

        foreach (array_chunk($clapRows, 500) as $chunk) {
            DB::table('claps')->insert($chunk);
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $this->command->info('Medium demo seeding completed!');
        $this->command->info('Demo users: ' . $demoUsers->count());
        $this->command->info('Demo posts: ' . count($demoPostIds));
        $this->command->info('Profile image paths assigned: ' . $demoUsers->count());
        $this->command->info('Post image paths assigned: ' . $demoPosts->count());
        $this->command->info('New follower relationships: ' . count($followerRows));
        $this->command->info('New claps: ' . count($clapRows));
        $this->command->info('Demo login: medium.demo.0001@example.test');
        $this->command->info('Demo password: Password123!');
    }
}
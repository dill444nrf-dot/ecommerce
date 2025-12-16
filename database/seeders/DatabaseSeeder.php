<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info(' Starting database seeding...');

        // 1. Buat admin user
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $this->command->info(' Admin user created: admin@example.com');

        // 2. Buat beberapa customer
        User::factory(10)->create(['role' => 'customer']);
        $this->command->info(' 10 customer users created');

        // 3. Seed categories
        $this->call(CategorySeeder::class);

        // 4. Buat produk
        Product::factory(50)->create();
        $this->command->info(' 50 products created');

        // 5. Buat beberapa produk featured
        Product::factory(8)->featured()->create();
        $this->command->info(' 8 featured products created');

        $this->command->newLine();
        $this->command->info(' Database seeding completed!');
        $this->command->info(' Admin login: admin@example.com / password');
    }
}
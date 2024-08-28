<?php

namespace Database\Seeders;

use App\Models\Bonus;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Category::factory(10)->create();
        // Product::factory(50)->create();
        // Order::factory(10)->create();
        // Customer::factory(10)->create();

        Customer::factory(2)->create();
        Bonus::factory(2)->create();

        User::factory()->create([
            'name' => 'TuanTQ',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12341234'),
        ]);

        // $this->call([
        //     ProductSeeder::class,
        // ]);

    }
}

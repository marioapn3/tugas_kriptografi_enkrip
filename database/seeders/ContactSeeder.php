<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contact::create([
            'name' => 'Walking Customer',
            'type' => 'customer',
            'default' => true,
        ]);
        for ($i = 0; $i < 10; $i++) {
            Contact::create([
                'name' => fake()->name(),
                'description' => fake()->sentence(),
                'email' => fake()->unique()->safeEmail(),
                'phone_number' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'type' => fake()->randomElement(['customer', 'supplier']),
                'city' => fake()->city(),
                'portal_code' => fake()->postcode(),
            ]);
        }
    }
}

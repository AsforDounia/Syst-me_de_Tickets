<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
 // Create categories
 Category::factory(3)->create();

 // Create users (Regular users and agents separately)
 User::factory(5)->create(); // Regular users
 $agents = User::factory(3)->create(['role' => 'agent']); // Create 3 agents

 // Ensure at least one agent exists
 if ($agents->count() == 0) {
     throw new \Exception('No agents were created! Tickets cannot be assigned.');
 }

 // Create tickets and assign a valid agent
 Ticket::factory(3)->create([
     'agent_id' => $agents->random()->id, // Pick a random valid agent ID
 ]);
    }
}

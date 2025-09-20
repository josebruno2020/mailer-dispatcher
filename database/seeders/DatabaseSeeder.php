<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $account = Account::create([
            'name' => 'Default Account',
            'document' => '12345678900',
        ]);
        User::factory()->create([
            'account_id' => $account->id,
            'name' => 'Test User',
            'email' => 'admin@teste.com',
            'password' => bcrypt('abc123'),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $admin = User::factory()->admin()->create();

        Item::factory()->count(100)->for($superAdmin)->create();
        Item::factory()->count(100)->for($admin)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Example: Create specific roles
        Role::create(['label' => RoleEnum::ADMIN]);
        Role::create(['label' => RoleEnum::BOUTIQUE]);
        Role::create(['label' => RoleEnum::CLIENT]);

        // Or generate random roles using factory
        // Role::factory()->count(10)->create();
    }
}

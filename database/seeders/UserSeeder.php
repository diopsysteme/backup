<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Database\Factories\UserFactory2Factory;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    
        public function run()
        {
            // Create a new role (if it doesn't already exist)

    
            // Create a new user
            User::create([
                'nom' => 'John Doe',
                'prenom' => 'John',
                'login' => 'john23',
                'password' => 'StrongP@ssw0rd!', // Use a secure password
                'role_id' => 2, // Associate the user with the role
            ]);
        }
    }
    



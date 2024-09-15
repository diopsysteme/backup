<?php

namespace Database\Seeders;

use Database\Factories\UserFactory2Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Client;
class ClientWithUserSeeder extends Seeder
{
    public function run()
    {
        // Crée 10 utilisateurs
        $users =  (new UserFactory2Factory())->count(10)->create();

        // Crée 10 clients et associe chacun à un utilisateur
        foreach ($users as $user) {
            // Crée un client pour chaque utilisateur
            $client = Client::factory()->create([
                'user_id' => $user->id, // Associe le client à l'utilisateur
            ]);

            // Associe également l'utilisateur au client (bidirectionnel)
            $user->client()->save($client);
        }
    }
}

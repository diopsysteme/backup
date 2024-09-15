<?php
namespace App\Services;

interface DetteServiceInterface
{
    public function enregistrerDette(array $data);
    public function getAllDettes();
    public function getAllDettes2();
    public function verifierEtEnregistrerDette($request);
    public function recupererDette($id,array $relation);

    // Autres méthodes du service
}

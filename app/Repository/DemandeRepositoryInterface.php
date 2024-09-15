<?php

namespace App\Repository;

interface DemandeRepositoryInterface
{
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function createDemandeWithArticles($clientId, $totalMontant, $validatedArticles);
    public function getDemandes();
    public function getAllDemandesForBoutiquier();

}

<?php
namespace App\Services;

interface DemandeServiceInterface
{
    public function createDemande(array $data);
    public function getDemandeById($id);
    public function updateDemande($id, array $data);
    public function handleCreateDemande(array $data);
    public function getDemandes();
    public function relance($id);
    public function notifDemande();
public function getBoutiquierNotifications();
public function getAllDemandesForBoutiquier();
}
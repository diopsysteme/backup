<?php
namespace App\Services;

interface ClientServiceInterface
{
    public function createClient(array $clientData, array $userData = null);

    public function getClientWithPhotoInBase64($id, $includeUser = false);

    public function getClientWithUser($id);
    public function findByTelephone($telephone);
}

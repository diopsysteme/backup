<?php
namespace App\Repository;

interface ClientRepositoryInterface
{
    public function all();

    public function findClientById($id);

    public function filter($request);

    public function createClient(array $clientData, array $userData = null);

    public function update($id, $data);

    public function delete($id);
    public function getClientWithUser($id);
}

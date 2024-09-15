<?php
namespace App\Repository;

interface PaymentRepositoryInterface
{
    public function findDebtById($debtId);

    public function createPayment(array $data);

    // Ajoute ici d'autres méthodes que le repository devra implémenter
}

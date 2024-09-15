<?php
namespace App\Repository;
use App\Models\Payment;
use App\Models\Dette;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function findDebtById($debtId)
    {
        return Dette::findOrFail($debtId);
    }

    public function createPayment(array $data)
    {
        return Payment::create($data);
    }

}

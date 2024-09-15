<?php
namespace App\Services;

interface PaymentServiceInterface
{
    public function storePayment(array $validatedData);
}

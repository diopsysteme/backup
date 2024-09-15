<?php
namespace App\Services;

use App\Repository\PaymentRepositoryInterface;
use Illuminate\Validation\ValidationException;

class PaymentService implements PaymentServiceInterface
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function storePayment(array $validatedData)
    {
        // dd("dd");
        $debt = $this->paymentRepository->findDebtById($validatedData['dette_id']);

        if ($validatedData['montant'] > $debt->montant_restant) {
            
        $message=$debt->montant_restant==0? "la dette est deja solde" : "Le montant maximum que vous pouvez payer est de : {$debt->montant_restant}.";
            return[
                'status' => "echec",
                
                'message' => "Le montant saisi dépasse le montant total restant.".$message
                             
            ];
        }
        $validatedData['date_paiement']=date('Y-m-d');
        $validatedData["user_id"]=auth()->id();
        // dd($debt);
        // dd($validatedData);
        if($this->paymentRepository->createPayment($validatedData)){
            $debt = $this->paymentRepository->findDebtById($validatedData['dette_id']);
            $debt->load('payement');
            return $debt;
        }
        return $this->paymentRepository->createPayment($validatedData);

    }
}

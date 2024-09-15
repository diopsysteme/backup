<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetteResource;
use App\Models\Payment;
use Illuminate\Http\Response;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentServiceInterface;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function store(StorePaymentRequest $request)
    {
        $result = $this->paymentService->storePayment($request->validated());
        if (isset($result['status']) && $result['status'] === 'echec') {
            return[
                'statut' => 'echec',
                'message' => $result['message'],
            "code"=>422];
        }
    
        return new DetteResource($result);
    }
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return new PaymentResource($payment);
    }

    public function show(Payment $payment)
    {
        return new PaymentResource($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}

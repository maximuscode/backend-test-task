<?php

namespace App\Controller;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PurchaseController extends AbstractController
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function purchase(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $errors = $validator->validate($data);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        try {
            $result = $this->paymentService->processPayment($data);
            return $this->json(['message' => 'Payment successful']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}

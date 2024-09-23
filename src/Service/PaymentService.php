<?php

// src/Service/PaymentService.php
namespace App\Service;

use App\Service\PriceCalculatorService;
use App\Interface\PaymentProcessorInterface;


class PaymentService
{
    /**
     * @param PaymentProcessorInterface[] $processors
     */
    public function __construct(
        private PriceCalculatorService $priceCalculatorService,
        private array $processors
    ) {}

    public function processPayment(array $data): bool
    {
        $price = $this->priceCalculatorService->calculatePrice($data['product'], $data['taxNumber'], $data['couponCode'] ?? null);
    
        foreach ($this->processors as $processor) {
            if ($processor->supports($data['paymentProcessor'])) {
                return $processor->processPayment($price);
            }
        }
    
        throw new \Exception('Invalid payment processor');
    }
}
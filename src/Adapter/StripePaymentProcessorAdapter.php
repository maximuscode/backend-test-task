<?php

namespace App\Adapter;

use App\Interface\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(private StripePaymentProcessor $processor) {}

    public function supports(string $processorName): bool
    {
        return $processorName === 'stripe';
    }

    public function processPayment(float $amount): bool
    {
        return $this->processor->processPayment($amount);
    }
}
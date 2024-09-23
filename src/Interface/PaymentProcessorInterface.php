<?php

namespace App\Interface;

interface PaymentProcessorInterface
{
    public function supports(string $processorName): bool;
    public function processPayment(float $amount): bool;
}
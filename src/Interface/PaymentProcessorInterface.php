<?php

namespace App\Interface;

interface PaymentProcessorInterface
{
    public function supports(string $processor): bool;
    public function processPayment(float $amount): bool;
}
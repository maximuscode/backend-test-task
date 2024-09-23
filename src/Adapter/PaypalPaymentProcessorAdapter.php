<?php

namespace App\Adapter;

use App\Interface\PaymentProcessorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(private PaypalPaymentProcessor $processor) {}

    public function supports(string $processorName): bool
    {
        return $processorName === 'paypal';
    }

    public function processPayment(float $amount): bool
    {
        try {
            $this->processor->pay((int)($amount * 100));
            return true;
        } catch (\Exception $e) {
            // Логирование ошибки
            $errorMessage = sprintf(
                "[%s] PayPal Payment Error: %s in %s at line %s",
                date('Y-m-d H:i:s'),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
            error_log($errorMessage, 3, __DIR__ . '/../../var/log/payment_errors.log');
            
            return false;
        }
    }
}
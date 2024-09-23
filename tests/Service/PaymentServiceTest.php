<?php

namespace App\Tests\Service;

use App\Service\PaymentService;
use App\Service\PriceCalculatorService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class PaymentServiceTest extends TestCase
{
    private PriceCalculatorService|MockObject $priceCalculatorService;
    private PaypalPaymentProcessor|MockObject $paypalProcessor;
    private StripePaymentProcessor|MockObject $stripeProcessor;
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        $this->priceCalculatorService = $this->createMock(PriceCalculatorService::class);
        $this->paypalProcessor = $this->createMock(PaypalPaymentProcessor::class);
        $this->stripeProcessor = $this->createMock(StripePaymentProcessor::class);

        $this->paymentService = new PaymentService(
            $this->priceCalculatorService,
            $this->paypalProcessor,
            $this->stripeProcessor
        );
    }

    public function testProcessPaymentWithPaypal(): void
    {
        $this->priceCalculatorService->method('calculatePrice')->willReturn(100.0);

        $this->paypalProcessor
            ->expects($this->once())
            ->method('pay')
            ->with(10000);

        $result = $this->paymentService->processPayment([
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'paymentProcessor' => 'paypal'
        ]);

        $this->assertTrue($result);
    }

    public function testProcessPaymentWithInvalidProcessor()
    {
        $this->priceCalculatorService
            ->expects($this->once())
            ->method('calculatePrice')
            ->willReturn(100.0);

        $result = $this->paymentService->processPayment([
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'paymentProcessor' => 'invalid'
        ]);

        $this->assertFalse($result);
    }
}

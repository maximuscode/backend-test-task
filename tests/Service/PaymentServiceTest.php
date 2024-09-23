<?php

namespace App\Tests\Service;

use App\Service\PaymentService;
use App\Service\PriceCalculatorService;
use App\Adapter\PaypalPaymentProcessorAdapter;
use App\Adapter\StripePaymentProcessorAdapter;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PaymentServiceTest extends TestCase
{
    private PriceCalculatorService|MockObject $priceCalculatorService;
    private PaypalPaymentProcessorAdapter|MockObject $paypalProcessorAdapter;
    private StripePaymentProcessorAdapter|MockObject $stripeProcessorAdapter;
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        $this->priceCalculatorService = $this->createMock(PriceCalculatorService::class);
        $this->paypalProcessorAdapter = $this->createMock(PaypalPaymentProcessorAdapter::class);
        $this->stripeProcessorAdapter = $this->createMock(StripePaymentProcessorAdapter::class);

        // Create the array of processor adapters
        $processors = [
            $this->paypalProcessorAdapter,
            $this->stripeProcessorAdapter
        ];

        $this->paymentService = new PaymentService(
            $this->priceCalculatorService,
            $processors
        );
    }

    public function testProcessPaymentWithPaypal(): void
    {
        $this->priceCalculatorService->method('calculatePrice')->willReturn(100.0);

        $this->paypalProcessorAdapter
            ->method('supports')
            ->with('paypal')
            ->willReturn(true);

        $this->paypalProcessorAdapter
            ->expects($this->once())
            ->method('processPayment')
            ->with(100.0)
            ->willReturn(true);

        $result = $this->paymentService->processPayment([
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'paymentProcessor' => 'paypal'
        ]);

        $this->assertTrue($result);
    }


    public function testProcessPaymentWithInvalidProcessor(): void
    {
        $this->priceCalculatorService->method('calculatePrice')->willReturn(100.0);
    
        $this->paypalProcessorAdapter
            ->method('supports')
            ->with('invalid')
            ->willReturn(false);
    
        $this->stripeProcessorAdapter
            ->method('supports')
            ->with('invalid')
            ->willReturn(false);
    
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid payment processor');
    
        $this->paymentService->processPayment([
            'product' => 1,
            'taxNumber' => 'DE123456789',
            'paymentProcessor' => 'invalid'
        ]);
    }
}

<?php

namespace App\Tests\Service;

use Brick\Math\BigDecimal;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\PriceCalculatorService;
use App\Service\TaxService;
use App\Service\CouponService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class PriceCalculatorServiceTest extends TestCase
{
    /**
     * @var ProductRepository&MockObject
     */
    private $productRepository;

    /**
     * @var TaxService&MockObject
     */
    private $taxService;

    /**
     * @var CouponService&MockObject
     */
    private $couponService;

    private $priceCalculatorService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->taxService = $this->createMock(TaxService::class);
        $this->couponService = $this->createMock(CouponService::class);

        $this->priceCalculatorService = new PriceCalculatorService(
            $this->productRepository,
            $this->taxService,
            $this->couponService
        );
    }

    public function testCalculatePriceWithoutCoupon()
    {
        $product = new Product();
        $product->setPrice(100);
    
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($product);
    
        $this->taxService->expects($this->once())
            ->method('getTaxRate')
            ->with('DE123456789')
            ->willReturn(0.19);
    
        $price = $this->priceCalculatorService->calculatePrice(1, 'DE123456789', null);
    
        $this->assertEquals(119.0, $price);
    }

    public function testCalculatePriceWithCoupon()
    {
        $product = new Product();
        $product->setPrice(100);
    
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($product);
    
        $this->taxService->expects($this->once())
            ->method('getTaxRate')
            ->with('DE123456789')
            ->willReturn(0.19);
    
        $this->couponService->expects($this->once())
            ->method('getDiscount')
            ->with('D15', 100.0)
            ->willReturn(15.0);
    

            $price = $this->priceCalculatorService->calculatePrice(1, 'DE123456789', 'D15');

            $expectedPrice = BigDecimal::of('101.15');
            $actualPrice = BigDecimal::of($price);
        
            $this->assertTrue($expectedPrice->isEqualTo($actualPrice), "Expected {$expectedPrice}, but got {$actualPrice}");
    }

    public function testCalculatePriceProductNotFound()
    {
        $this->productRepository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found');

        $this->priceCalculatorService->calculatePrice(999, 'DE123456789', null);
    }
}
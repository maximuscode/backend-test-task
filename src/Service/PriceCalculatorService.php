<?php 

namespace App\Service;

use Brick\Math\BigDecimal;
use App\Repository\ProductRepository;
use App\Service\TaxService;
use App\Service\CouponService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PriceCalculatorService
{
    public function __construct(
        private ProductRepository $productRepository,
        private TaxService $taxService,
        private CouponService $couponService
    ) {}

    public function calculatePrice(int $productId, string $taxNumber, ?string $couponCode): float
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new NotFoundHttpException('Product not found:' . $productId);
        }

        $price = BigDecimal::of($product->getPrice());
        $taxRate = BigDecimal::of($this->taxService->getTaxRate($taxNumber));

        if ($couponCode) {
            $discount = BigDecimal::of($this->couponService->getDiscount($couponCode, $price->toFloat()));
            $price = $price->minus($discount);
        }

        $finalPrice = $price->multipliedBy(BigDecimal::one()->plus($taxRate));

        return $finalPrice->toFloat();
    }
}
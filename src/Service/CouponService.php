<?php

namespace App\Service;

use App\Repository\CouponRepository;

class CouponService
{
    public function __construct(
        private CouponRepository $couponRepository
    ) {}

    public function getDiscount(string $couponCode, float $price): float
    {
        $coupon = $this->couponRepository->findOneBy(['code' => $couponCode]);
        
        if (!$coupon) {
            throw new \Exception('Invalid coupon code');
        }

        if ($coupon->getDiscountType() === 'fixed') {
            return min($coupon->getDiscount(), $price); // Не позволяем скидке превышать цену
        } else {
            return $price * ($coupon->getDiscount() / 100);
        }
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $coupons = [
            ['code' => 'SUMMER_SALE', 'discount' => 10, 'discountType' => 'percentage'],
            ['code' => 'FIXED_DISCOUNT', 'discount' => 5, 'discountType' => 'fixed'],
        ];

        foreach ($coupons as $couponData) {
            $coupon = new Coupon();
            $coupon->setCode($couponData['code']);
            $coupon->setDiscount($couponData['discount']);
            $coupon->setDiscountType($couponData['discountType']);
            $manager->persist($coupon);
        }

        $manager->flush();
    }
}
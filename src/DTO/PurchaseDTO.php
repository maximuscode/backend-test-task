<?php

namespace App\DTO;

use App\Validator\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public $product;

    #[Assert\NotBlank]
    #[TaxNumber]
    public $taxNumber;

    #[Assert\Type('string')]
    public $couponCode;

    #[Assert\NotBlank]
    #[Assert\Choice(['paypal', 'stripe'])]
    public $paymentProcessor;
}
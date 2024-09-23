<?php

namespace App\DTO;

use App\Validator\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public $product;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^(DE\d{9}|IT\d{11}|GR\d{9}|FR[A-Z]{2}\d{9})$/')]
    public $taxNumber;

    #[Assert\Type('string')]
    public $couponCode;
}
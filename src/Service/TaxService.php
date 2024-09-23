<?php 

namespace App\Service;

use App\Repository\TaxRateRepository;

class TaxService
{
    public function __construct(
        private TaxRateRepository $taxRateRepository
    ) {}

    public function getTaxRate(string $taxNumber): float
    {
        $countryCode = substr($taxNumber, 0, 2);
        $taxRate = $this->taxRateRepository->findOneBy(['countryCode' => $countryCode]);
        
        if (!$taxRate) {
            throw new \Exception('Invalid tax number');
        }
    
        return $taxRate->getRate();
    }
}
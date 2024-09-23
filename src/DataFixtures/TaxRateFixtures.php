<?php

namespace App\DataFixtures;

use App\Entity\TaxRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxRateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $taxRates = [
            ['countryCode' => 'DE', 'rate' => 0.19],
            ['countryCode' => 'IT', 'rate' => 0.22],
            ['countryCode' => 'GR', 'rate' => 0.24],
            ['countryCode' => 'FR', 'rate' => 0.20],
        ];

        foreach ($taxRates as $taxRateData) {
            $taxRate = new TaxRate();
            $taxRate->setCountryCode($taxRateData['countryCode']);
            $taxRate->setRate($taxRateData['rate']);
            $manager->persist($taxRate);
        }

        $manager->flush();
    }
}
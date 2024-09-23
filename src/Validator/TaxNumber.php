<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class TaxNumber extends Constraint
{
    public string $message = 'The tax number "{{ value }}" is not valid.';
}

<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class CustomEmail extends Constraint
{
    public $message = 'custom_email_validator';

    public function validatedBy()
    {
        return static::class . 'Validator';
    }
}
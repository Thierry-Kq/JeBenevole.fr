<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 *@Annotation
 */
class isFromYoutube extends Constraint
{

    public string $message ='LinkPattern_notValid';

    /**
    *return string
    */
    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}

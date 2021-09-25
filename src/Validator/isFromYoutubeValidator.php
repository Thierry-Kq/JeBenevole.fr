<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UnexpectedValueException;

class isFromYoutubeValidator extends ConstraintValidator
{
    const LIEN_VALIDE = "youtube.com";

    /**
    *@param mixed $value
    *@param Constraint $constraint
    */
    public function validate($value, Constraint $constraint): void
    {

        if(!is_string($value)){
            throw new UnexpectedValueException($value, 'string');
        }

        if(!preg_match('/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be|youtube-nocookie\.com))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/', $value, $matches)){
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{string}}', $value)
            ->addViolation();
        }
    }
}
<?php

namespace App\Validator;

use UnexpectedValueException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class isFromYoutubeValidator extends ConstraintValidator
{

    /**
    *@param mixed $value
    *@param Constraint $constraint
    */
    public function validate($value, Constraint $constraint): void
    {

        if (!$constraint instanceof isFromYoutube) {
            throw new UnexpectedTypeException($constraint, isFromYoutube::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

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

<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueThumbnailValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\UniqueThumbnailValidtor $constraint */

        if (!$constraint instanceof UniqueThumbnail) {
            throw new UnexpectedTypeException($constraint, ContainsAlphanumeric::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $thumbnailCounter = 0;

        foreach ($value->getValues() as $image) {
            if ($image->isIsThumbnail()) {
                $thumbnailCounter++;
            }
        }

        if ($thumbnailCounter < 1) {
            $constraint->message = 'Il doit y avoir au minimum une vignette';
        }

        if ($thumbnailCounter > 1) {
            $constraint->message = 'Il doit y avoir au maximum une vignette';
        }

        if ($constraint->message) {
            $this->context->buildViolation($constraint->message)
            ->addViolation();
        }
    }
}

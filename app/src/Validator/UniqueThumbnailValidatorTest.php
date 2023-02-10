<?php

namespace App\Tests\Validator;

use App\Entity\Image;
use App\Validator\UniqueThumbnail;
use App\Validator\UniqueThumbnailValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class UniqueThumbnailValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @return UniqueThumbnailValidator
     */
    protected function createValidator()
    {
        return new UniqueThumbnailValidator();
    }

    public function testUniqueThumbnailIsUnique()
    {
        $images = new ArrayCollection();

        $image = new Image();
        $image->setIsThumbnail(true);
        $images->add($image);

        $image = new Image();
        $image->setIsThumbnail(false);
        $images->add($image);

        $this->validator->validate($images, new UniqueThumbnail());

        $this->assertNoViolation();
    }

    public function testNoUniqueThumbnailIsInvalid()
    {
        $contrains = new UniqueThumbnail();

        $images = new ArrayCollection();

        $image = new Image();
        $image->setIsThumbnail(false);
        $images->add($image);

        $this->validator->validate($images, $contrains);

        $this->buildViolation($contrains->message)
            ->assertRaised();
    }

    public function testMuchUniqueThumbnailIsInvalid()
    {
        $contrains = new UniqueThumbnail();

        $images = new ArrayCollection();

        $image = new Image();
        $image->setIsThumbnail(true);
        $images->add($image);

        $image = new Image();
        $image->setIsThumbnail(true);
        $images->add($image);

        $this->validator->validate($images, $contrains);

        $this->buildViolation($contrains->message)
            ->assertRaised();
    }
}
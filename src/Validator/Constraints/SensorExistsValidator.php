<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\SensorRepository;

/**
 * @Annotation
 */
class SensorExistsValidator extends ConstraintValidator
{
    private $repo;

    public function __construct(SensorRepository $repo)
    {
        $this->repo = $repo;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (null === $this->repo->findOneById($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%uuid%', $value)
                ->addViolation()
            ;
        }
    }
}
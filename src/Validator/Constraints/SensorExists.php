<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SensorExists extends Constraint
{
    public $message = 'Sensor with id "%uuid%" not exists';

    public function validatedBy()
    {
        return SensorExistsValidator::class;
    }
}
<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Types\DateTimeType as GenuineDateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Utils\DateTime;

class DateTimeType extends GenuineDateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $dateTime = parent::convertToPHPValue($value, $platform);

        if ( ! $dateTime) {
            return $dateTime;
        }

        return new DateTime($dateTime->format(DateTime::FORMAT));
    }

    public function getName()
    {
        return 'mydatetime';
    }
}
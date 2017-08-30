<?php

namespace App\DTO;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\SensorExists;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

class SensorData
{
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @Assert\NotNull
     * @SensorExists
     * @Serializer\Accessor(setter="setSensorFromString")
     * @Serializer\Type("string")
     */
    public $sensor;

    /**
     * @var DateTimeImmuable
     *
     * @Assert\NotNull
     * @Assert\DateTime
     * @Serializer\Type("DateTimeImmutable")
     */
    public $datetime;

    /**
     * @Assert\NotNull
     * @Assert\Type("numeric")
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThanOrEqual(100)
     * @Serializer\Type("double")
     */
    public $humidity;

    /**
     * @Assert\NotNull
     * @Assert\Type("numeric")
     * @Assert\GreaterThan(-1000)
     * @Assert\LessThan(1000)
     * @Serializer\Type("double")
     */
    public $temperature;

    public function setSensorFromString(string $sensor)
    {
        try {
            $this->sensor = Uuid::fromString($sensor);
        } catch (InvalidUuidStringException $e) {
            $this->sensor = null;
        }

        return $this;
    }
}
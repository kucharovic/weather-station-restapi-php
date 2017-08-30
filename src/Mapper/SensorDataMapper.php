<?php

namespace App\Mapper;

use App\DTO\SensorData;
use App\Entity\{Sensor, Data};
use Doctrine\ORM\EntityManager;
use App\Utils\DateTime;
use DateTimeImmutable;

class SensorDataMapper
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @param Doctrine\ORM\EntityManager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createFromDTO(SensorData $dto): Data
    {
        $entity = new Data;
        $entity->sensor = $this->em->getRepository(Sensor::class)->findOneById($dto->sensor);
        $entity->datetime = new DateTime($dto->datetime->format(DateTime::FORMAT));
        $entity->humidity = $dto->humidity;
        $entity->temperature = $dto->temperature;

        return $entity;
    }

    public function createFromEntity(Data $entity): SensorData
    {
        $dto = new SensorData;

        $dto->sensor = $entity->sensor->id;
        $dto->datetime = DateTimeImmutable::createFromMutable($entity->datetime);
        $dto->humidity = $entity->humidity;
        $dto->temperature = $entity->temperature;

        return $dto;
    }
}

<?php

namespace App\Doctrine\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\{AccessToken, Data, Sensor};
use App\Utils\DateTime;

class LoadTestData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $access = new AccessToken;
        $access->username = 'Sensor #1';
        $access->token = '2xCCoW1jfqb6Ov2JSZG45qdUXWseT3rIaQzDrVrrHT7blTb78vAT';

        $sensor = new Sensor;
        $sensor->description = 'Living room';

        $data = new Data;
        $data->sensor = $sensor;
        $data->datetime = new DateTime;
        $data->humidity = 48.89;
        $data->temperature = 25.45;

        $manager->persist($access);
        $manager->persist($sensor);
        $manager->persist($data);
        $manager->flush();

        $this->addReference('access', $access);
        $this->addReference('sensor', $sensor);
        $this->addReference('data', $data);
    }
}
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="data")
 */
class Data
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Sensor")
     */
    public $sensor;

    /**
     * @ORM\Id
     * @ORM\Column(type="mydatetime")
     */
    public $datetime;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, options={"unsigned":true})
     */
    public $humidity;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    public $temperature;
}
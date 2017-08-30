<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccessTokenRepository")
 * @ORM\Table(name="access_token")
 */
class AccessToken implements UserInterface
{
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid_binary")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    public $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    public $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    public $token;

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {}

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {}

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {}
}
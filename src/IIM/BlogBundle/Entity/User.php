<?php
// src/IIM/BlogBundle/Entity/User.php

namespace IIM\BlogBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups as Groups;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"FULL","LARGE","SMALL"})
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }
}
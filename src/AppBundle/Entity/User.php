<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\ManyToMany(targetEntity="Car")
     * @ORM\JoinTable(name="users_cars",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="car_id", referencedColumnName="id")}
     *      )
     */
    protected $cars;

    /**
     * @var int
     *
     * @ORM\OneToOne(targetEntity="Car")
     * @ORM\JoinColumn(name="current_car_id", referencedColumnName="id")
     */
    protected $current_car;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    protected $role;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cars = new ArrayCollection();

        $this->roles = array('ROLE_USER');
    }
}
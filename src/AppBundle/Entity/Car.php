<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarRepository")
 * @ORM\Table(name="car")
 */
class Car
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $numbers;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="cars")
     */
    private $drivers;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNumbers()
    {
        return $this->numbers;
    }

    /**
     * @param $numbers
     */
    public function setNumbers($numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * @param User $user
     */
    public function addDriver(User $user)
    {
        $this->drivers[] = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * Car constructor.
     */
    public function __construct()
    {
        $this->drivers = new ArrayCollection();
    }
}
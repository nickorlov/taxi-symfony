<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var string
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     *     )
     */
    protected $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please enter your surname.", groups={"Registration", "Profile"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The surname is too short.",
     *     maxMessage="The surname is too long.",
     *     groups={"Registration", "Profile"}
     *     )
     */
    protected $surname;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please enter your phone.", groups={"Registration", "Profile"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The phone is too short.",
     *     maxMessage="The phone is too long.",
     *     groups={"Registration", "Profile"}
     *     )
     */
    protected $phone;

    /**
     * @var int
     *
     * @ORM\ManyToMany(targetEntity="Car", inversedBy="drivers")
     * @ORM\JoinTable(name="users_cars")
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
     * Get id
     *
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @param Car $car
     */
    public function addCar(Car $car)
    {
        $this->cars[] = $car;
    }

    /**
     * @return ArrayCollection|int
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cars = new ArrayCollection();

        $this->roles[] = 'ROLE_USER';
    }
}
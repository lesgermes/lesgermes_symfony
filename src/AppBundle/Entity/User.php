<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string")
     */
    protected $firstName;

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string")
     */
    protected $lastName;

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="coins", type="integer")
     */
    protected $coins;

    /**
     * @return integer
     */
    public function getCoins()
    {
        return $this->coins;
    }

    /**
     * @param integer $coins
     */
    public function setCoins($coins)
    {
        $this->coins = $coins;
    }

    /**
     * @var AppBundle\Entity\UserTitle
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserTitle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="title", referencedColumnName="id", nullable=true)
     * })
     */
    private $title;

    /**
     * @return UserTitle
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param UserTitle $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @var array
     *
     * @ORM\Column(name="available_titles", type="array", nullable=false)
     */
    private $availableTitles;

    /**
     * @return array
     */
    public function getAvailableTitles()
    {
        return $this->availableTitles;
    }

    /**
     * @param array $availableTitles
     */
    public function setAvailableTitles($availableTitles)
    {
        $this->availableTitles = $availableTitles;
    }

    /**
     * @var AppBundle\Entity\ProfileImage
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProfileImage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profile_image", referencedColumnName="id", nullable=true)
     * })
     */
    private $profileImage;

    /**
     * @return ProfileImage
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @param ProfileImage $profileImage
     */
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;
    }
}


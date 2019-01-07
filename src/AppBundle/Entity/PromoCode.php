<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoCode
 *
 * @ORM\Table(name="promo_code")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PromoCodeRepository")
 */
class PromoCode
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    private $role;

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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
     * @var \DateTime
     *
     * @ORM\Column(name="used_on", type="datetime", nullable=true)
     */
    private $usedOn;

    /**
     * Set usedOn
     *
     * @param \DateTime $usedOn
     */
    public function setUsedOn($usedOn)
    {
        $this->usedOn = $usedOn;
    }

    /**
     * Get usedOn
     *
     * @return \DateTime
     */
    public function getUsedOn()
    {
        return $this->usedOn;
    }

    /**
     * @var AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
     * })
     */
    private $user;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}


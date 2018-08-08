<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */
class Transaction
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
     * @var AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
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

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * Set date
     *
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string")
     */
    protected $content;

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="previousAmount", type="integer")
     */
    protected $previousAmount;

    /**
     * @return integer
     */
    public function getPreviousAmount()
    {
        return $this->previousAmount;
    }

    /**
     * @param integer $previousAmount
     */
    public function setPreviousAmount($previousAmount)
    {
        $this->previousAmount = $previousAmount;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     */
    protected $amount;

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param integer $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="newAmount", type="integer")
     */
    protected $newAmount;

    /**
     * @return integer
     */
    public function getNewAmount()
    {
        return $this->newAmount;
    }

    /**
     * @param integer $newAmount
     */
    public function setNewAmount($newAmount)
    {
        $this->newAmount = $newAmount;
    }
}


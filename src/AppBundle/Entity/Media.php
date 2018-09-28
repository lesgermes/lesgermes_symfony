<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaRepository")
 */
class Media
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
     * @var \AppBundle\Entity\MediaType
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\MediaType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @return \AppBundle\Entity\MediaType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param MediaType $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="minimum_role", type="string", length=255, nullable=false)
     */
    private $minimumRole;

    /**
     * @return string
     */
    public function getMinimumRole()
    {
        return $this->minimumRole;
    }

    /**
     * @param string $minimumRole
     */
    public function setMinimumRole($minimumRole)
    {
        $this->minimumRole = $minimumRole;
    }

    private $userCanRead;

    public function getUserCanRead() 
    {
        return $this->userCanRead;
    }

    public function setUserCanRead($userCanRead)
    {
        $this->userCanRead = $userCanRead;
    }
    
}


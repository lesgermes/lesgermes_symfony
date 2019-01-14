<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfileImage
 *
 * @ORM\Table(name="profile_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileImageRepository")
 */
class ProfileImage
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
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
}


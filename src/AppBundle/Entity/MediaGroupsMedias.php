<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MediaGroupsMedias
 *
 * @ORM\Table(name="media_groups_medias")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaGroupsMediasRepository")
 */
class MediaGroupsMedias
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
     * @var AppBundle\Entity\MediaGroup
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MediaGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_group", referencedColumnName="id", nullable=false)
     * })
     */
    private $group;

    /**
     * @return MediaGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param MediaGroup $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @var AppBundle\Entity\Media
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Media")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media", referencedColumnName="id", nullable=false)
     * })
     */
    private $media;

    /**
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }
}


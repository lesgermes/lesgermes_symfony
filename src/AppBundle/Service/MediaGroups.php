<?php

namespace AppBundle\Service;

use AppBundle\Entity\Media;
use AppBundle\Entity\MediaGroup;

class MediaGroups
{
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function createGroup($groupName) {
        $group = new MediaGroup();
        $group->setName($groupName);

        $this->em->persist($group);
        $this->em->flush();
    }

    public function getGroup($id) {
        $group = $this->em
            ->getRepository('AppBundle:MediaGroup')
            ->find($id);

        if (!$group) {
            return null;
        }

        return $group;
    }

    public function getGroupMedias($group) {
        $medias = $this->em
            ->getRepository('AppBundle:MediaGroupsMedias')
            ->findBy(
                array("group" => $group)
            );
        
        return $medias;
    }

}
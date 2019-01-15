<?php

namespace AppBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\ProfileImage;

class ProfileImageEntityListener
{
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof ProfileImage) {
            return;
        }
        $entity->setBaseUrl($this->getProfileImageBaseUrl());
    }

    private function getProfileImageBaseUrl() {
        $profileImageDirParam = $this->container->getParameter('profile_image_directory');
        $profileImageDirParamExploded = explode("/", $profileImageDirParam);
        $profileImageDir = $profileImageDirParamExploded[count($profileImageDirParamExploded) - 1];

        $profileImageBaseUrl = $this->container->get('router')->getContext()->getScheme()."://".
            $this->container->get('router')->getContext()->getHost()."/".
            $profileImageDir."/";

        return $profileImageBaseUrl;
    }
}
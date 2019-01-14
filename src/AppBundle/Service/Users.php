<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Transaction;

class Users
{
    public function __construct(\Doctrine\ORM\EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function makeTransaction($user, $amount, $content) {
        $transaction = new Transaction();
        $transaction->setUser($user);
        $transaction->setDate(new \DateTime());
        $transaction->setContent($content);
        $transaction->setPreviousAmount($user->getCoins());
        $transaction->setAmount($amount);
        $transaction->setNewAmount($user->getCoins() + $amount);

        $user->setCoins($user->getCoins() + $amount);

        $this->em->persist($transaction);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function getProfileImageUrl($image) {
        $profileImageDirParam = $this->container->getParameter('profile_image_directory');
        $profileImageDirParamExploded = explode("/", $profileImageDirParam);
        $profileImageDir = $profileImageDirParamExploded[count($profileImageDirParamExploded) - 1];

        $profileImageUrl = $this->container->get('router')->getContext()->getScheme()."://".
            $this->container->get('router')->getContext()->getHost()."/".
            $profileImageDir."/".
            $image->getImage();

        return $profileImageUrl;
    }

}
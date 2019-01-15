<?php

namespace AppBundle\Service;

use AppBundle\Entity\Transaction;

class Users
{
    public function __construct(\Doctrine\ORM\EntityManager $em)
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

}
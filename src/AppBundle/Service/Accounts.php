<?php
namespace AppBundle\Service;

use FOS\RestBundle\View\View;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class Accounts
{
    public function __construct(
        UserManager $userManager,
        EncoderFactory $encoderFactory,
        \Doctrine\ORM\EntityManager $em,
        Roles $rolesService
    )
    {
        $this->userManager = $userManager;
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;
        $this->rolesService = $rolesService;
    }

    /**
     *  200="Returned when successful",
     *  401="Returned when email not registered",
     *  402="Returned when bad email/password combination",
     *  406="Returned when user is not enabled"
     */
    public function login($email, $password, $role = null) {
        if (!$this->userManager->findUserByEmail($email)) {
            $view = array("code"=>401,"message"=>"Cette adresse email n'existe pas");
        }
        else {
            $user = $this->userManager->findUserByEmail($email);

            if (!$user->isEnabled()) {
                $view = array("code"=>406,"message"=>"Cette adresse email n'a pas été confirmée. Vérifiez vos emails");
            } else if ($role != null && !$this->rolesService->isGranted($role, $user)) {
                return array("code"=>411,"message"=>"L'utilisateur n'a pas les droits nécessaires");
            } else {
                $encoder = $this->encoderFactory->getEncoder($user);
                $salt = $user->getSalt();

                if($encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
                    $this->updateLastLogin($user);
                    $view = array("code"=>200,"user"=>$user);
                } else {
                    $view = array("code"=>402,"message"=>"Mauvaise combinaison email/mot de passe");
                }
            }
        }

        return $view;
    }

    public function updateLastLogin($user) {
        $user->setLastLogin(new \DateTime());
        $this->userManager->updateUser($user);
    }

    public function checkEmailIsFree($email) {
        $users = $this->em->getRepository('AppBundle:User');

        $user = $users->findByEmail($email);

        if ($user)
            return false;
        else
            return true;
    }
}
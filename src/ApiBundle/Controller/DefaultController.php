<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Post;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Util\TokenGenerator;

class DefaultController extends Controller
{
    /**
     * Inscription
     *
     * @Post("/register")
     * @ApiDoc(
     *  resource=true,
     *  description="Register",
     *  parameters={
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="user email"
     *      },
     *     {
     *          "name"="username",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="user username"
     *      },
     *     {
     *          "name"="password",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="user password"
     *      },
     *     {
     *          "name"="first_name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="user first name"
     *      },
     *     {
     *          "name"="last_name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="user last name"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         400="Returned when form error",
     *         401="Returned when username already exists",
     *         402="Returned when email already used"
     *  }
     * )
     *
     */
    public function registerAction(Request $request) {
        $userManager = $this->get('fos_user.user_manager');

        if ($request->getMethod() != 'POST')
            return View::create()
                ->setStatusCode(400)
                ->setData(array("code"=>400,"message"=>"form error"));
        
        $c = $request->request->all();

        if ($userManager->findUserByUsername($c['username']))
            return View::create()
                ->setStatusCode(401)
                ->setData(array("code"=>401,"message"=>"username already exists"));
        
        else if ($userManager->findUserByEmail($c['email']))
            return View::create()
                ->setStatusCode(402)
                ->setData(array("code"=>402,"message"=>"email already used"));
        
        $user = $userManager->createUser();
        $user->setUsername($c['username']);
        $user->setEmail($c['email']);
        $user->setPlainPassword($c['password']);
        $user->setFirstName($c['first_name']);
        $user->setLastName($c['last_name']);
        $user->setEnabled(true);
        //replace last line with next two commented lines for user confirmation using emails
        // $user->setEnabled(false);
        // $user->setConfirmationToken($this->get('fos_user.util.token_generator')->generateToken());
        $userManager->updateUser($user);
        return View::create()
            ->setStatusCode(200)
            ->setData(array("user"=>$user));
    }

}

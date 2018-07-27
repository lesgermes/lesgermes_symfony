<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class UserController extends Controller
{
    /**
     * Get Current User
     *
     * @Get("/")
     * @ApiDoc(
     *  resource=true,
     *  description=" Get Current User",
     *  statusCodes={
     *         200="Returned when successful"
     *  }
     * )
     *
     */
    public function getCurrentUserAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return View::create()
            ->setStatusCode(200)
            ->setData($user);
    }
}

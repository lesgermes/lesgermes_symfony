<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
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

    /**
     * Register Promo Code
     *
     * @Post("/register_promo_code")
     * @ApiDoc(
     *  resource=true,
     *  description="Register Promo Code",
     *  parameters={
     *      {
     *          "name"="promocode",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="user promo code"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         400="Returned when form error",
     *         401="Returned when user has already registered a promo code",
     *         402="Returned when promo code does not exist",
     *         403="Returned when promo code has already been used"
     *  }
     * )
     *
     */
    public function registerPromoCodeAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $c = $request->request->all();

        if ($request->getMethod() != 'POST' || !array_key_exists("promocode", $c))
            return View::create()
                ->setStatusCode(400)
                ->setData("form error");

        $em = $this
            ->getDoctrine()
            ->getManager();

        $userPromoCode = $em
            ->getRepository('AppBundle:PromoCode')
            ->findOneBy(
                array('user' => $user)    //where
            );

        if ($userPromoCode)
            return View::create()
                ->setStatusCode(401)
                ->setData("user has already registered a promo code");

        $promoCode = $em
            ->getRepository('AppBundle:PromoCode')
            ->findOneBy(
                array('code' => $c["promocode"])
            );

        if (!$promoCode)
            return View::create()
                ->setStatusCode(402)
                ->setData("promo code does not exist");
        
        if ($promoCode->getUser() != null)
            return View::create()
                ->setStatusCode(403)
                ->setData("promo code has already been used");
        
        $user->setTitle($promoCode->getTitle());
        $userAvailableTitles = $user->getAvailableTitles();
        array_push($userAvailableTitles, $promoCode->getTitle()->getId());
        $user->setAvailableTitles($userAvailableTitles);
        $user->addRole($promoCode->getRole());
        $this
            ->get('fos_user.user_manager')
            ->updateUser($user);
        $this->get('app_services.users')->makeTransaction($user, $promoCode->getCoins(), "Promo Code registered");

        $promoCode->setUser($user);
        $promoCode->setUsedOn(new \DateTime());
        $em->persist($promoCode);
        $em->flush();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        return View::create()
            ->setStatusCode(200)
            ->setData($user);
    }

    /**
     * Get Available Titles
     *
     * @Get("/get_available_titles")
     * @ApiDoc(
     *  resource=true,
     *  description=" Get Available Titles",
     *  statusCodes={
     *         200="Returned when successful"
     *  }
     * )
     *
     */
    public function setTitleAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this
            ->getDoctrine()
            ->getManager();

        $availableTitlesIds = $user->getAvailableTitles();
        $availableTitles = [];
        foreach ($availableTitlesIds as $availableTitleId) {
            $availableTitle = $em
                ->getRepository('AppBundle:UserTitle')
                ->find($availableTitleId);

            array_push($availableTitles, $availableTitle);
        }

        return View::create()
            ->setStatusCode(200)
            ->setData($availableTitles);
    }

    /**
     * Set Title
     *
     * @Post("/set_title")
     * @ApiDoc(
     *  resource=true,
     *  description="Set Title",
     *  parameters={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "required"=true,
     *          "description"="title id"
     *      },
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="title name"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         400="Returned when form error",
     *         401="Returned when title doesn't exist",
     *         402="Returned when user doesn't have access to this title"
     *  }
     * )
     *
     */
    public function getAvailableTitlesAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $c = $request->request->all();

        if ($request->getMethod() != 'POST' || !array_key_exists("id", $c))
            return View::create()
                ->setStatusCode(400)
                ->setData("form error");

        $em = $this
            ->getDoctrine()
            ->getManager();

        $title = $em
            ->getRepository('AppBundle:UserTitle')
            ->find($c["id"]);
        
        if (!$title)
            return View::create()
                ->setStatusCode(401)
                ->setData("title doesn't exist");
        
        $userAvailableTitlesIds = $user->getAvailableTitles();
        if (!in_array($c["id"], $userAvailableTitlesIds))
            return View::create()
                ->setStatusCode(402)
                ->setData("user doesn't have access to this title");

        $user->setTitle($title);
        $this
            ->get('fos_user.user_manager')
            ->updateUser($user);

        return View::create()
            ->setStatusCode(200)
            ->setData($user);
    }
}

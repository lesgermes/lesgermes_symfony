<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Entity\PromoCode;
use AppBundle\Form\PromoCodeForm;
use AppBundle\Entity\UserTitle;
use AppBundle\Form\UserTitleForm;
use AppBundle\Entity\ProfileImage;
use AppBundle\Form\ProfileImageForm;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        if ($session->has('login')) {
            return $this->render('AdminBundle:Default:index.html.twig', array(
                'session' => $session->all()
            ));
        }
        else {
            return $this->redirect($this->generateUrl('admin_login'));
        }
    }

    public function usersAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        $users = $this
            ->get('fos_user.user_manager')
            ->findUsers();

        return $this->render('AdminBundle:Default:users.html.twig', array(
            'session' => $session->all(),
            'users' => $users
        ));
    }

    public function usersTableAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        $users = $this
            ->get('fos_user.user_manager')
            ->findUsers();

        return $this->render('AdminBundle:Default:usersTable.html.twig', array(
            'users' => $users
        ));
    }

    public function editUsersRolesModalAction(Request $request, $userId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        if (!isset($userId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error userId")));
        }

        $user = $this
            ->get('fos_user.user_manager')
            ->findUserBy(array('id'=>$userId));

        if (!$user) {
            return new Response(json_encode(array("error"=>true,"message"=>"error User not found")));
        }

        $roles = $this->get('app_services.roles')->getRoles($session->get('roles'));

        if ($request->isMethod('GET')) {
            return $this->render('AdminBundle:Modals:edit_user_roles.html.twig', array(
                'user' => $user,
                'roles' => $roles
            ));
        }
        else if ($request->isMethod('POST') && isset($_POST['role']) && isset($_POST['delete']) && $_POST['delete'] == true) {
            if (!$this->get('app_services.roles')->isGranted($_POST['role'], $user))
                return new Response(json_encode(array("error"=>true,"message"=>"L'utilisateur n'a pas ce role")));

            $user->removeRole($_POST['role']);
            $this
                ->get('fos_user.user_manager')
                ->updateUser($user);

            return new Response(json_encode(array("success"=>true,"roles"=>$user->getRoles())));
        }
        else if ($request->isMethod('POST') && isset($_POST['role'])) {
            if (!in_array($_POST['role'], $roles))
                return new Response(json_encode(array("error"=>true,"message"=>"Ce role n'existe pas ou vous ne disposez pas des droits suffisants")));
            if ($this->get('app_services.roles')->isGranted($_POST['role'], $user))
                return new Response(json_encode(array("error"=>true,"message"=>"L'utilisateur a déjà ce role")));

            $user->addRole($_POST['role']);
            $this
                ->get('fos_user.user_manager')
                ->updateUser($user);

            return new Response(json_encode(array("success"=>true,"roles"=>$user->getRoles())));
        }
        else {
            return new Response(json_encode(array("error"=>true,"message"=>"error HTTP METHOD")));
        }
    }

    public function userTransactionsHistoryModalAction(Request $request, $userId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        if (!isset($userId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error userId")));
        }

        $user = $this
            ->get('fos_user.user_manager')
            ->findUserBy(array('id'=>$userId));

        if (!$user) {
            return new Response(json_encode(array("error"=>true,"message"=>"error User not found")));
        }

        $transactions = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Transaction')
            ->findBy(
                array("user" => $user),
                array('date' => 'ASC')//order
            );
        
        return $this->render('AdminBundle:Modals:userTransactionsHistoryModal.html.twig', array(
            'user' => $user,
            'transactions' => $transactions
        ));
    }

    public function addUserFundsModalAction(Request $request, $userId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        if (!isset($userId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error userId")));
        }

        $user = $this
            ->get('fos_user.user_manager')
            ->findUserBy(array('id'=>$userId));

        if (!$user) {
            return new Response(json_encode(array("error"=>true,"message"=>"error User not found")));
        }

        if ($request->isMethod('GET')) {
            return $this->render('AdminBundle:Modals:addUserFundsModal.html.twig', array(
                'user' => $user
            ));
        } else if ($request->isMethod('POST') && isset($_POST['amount'])) {
            $this->get('app_services.users')->makeTransaction($user, $_POST['amount'], "Admin Add Funds");
            return new Response(json_encode(array("success"=>true)));
        }
    }

    public function userTitlesAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        $titles = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:UserTitle')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        return $this->render('AdminBundle:Default:userTitles.html.twig', array(
            'session' => $session->all(),
            'titles' => $titles
        ));
    }

    public function userTitlesTableAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        $titles = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:UserTitle')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        return $this->render('AdminBundle:Default:userTitlesTable.html.twig', array(
            'titles' => $titles
        ));
    }

    public function editUserTitleModalAction(Request $request, $userTitleId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        if (!isset($userTitleId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error userTitleId")));
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $userTitle = new UserTitle();

        if ($userTitleId != 'new')
            $userTitle = $em
                ->getRepository('AppBundle:UserTitle')
                ->find($userTitleId);

        if (!$userTitle)
            return new Response(json_encode(array("error"=>true,"message"=>"error User Title not found")));

        $form = $this->createForm(UserTitleForm::class, $userTitle);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('AdminBundle:Modals:edit_userTitle.html.twig', array(
                'form' => $form->createView(),
                'userTitleId' => $userTitleId
            ));

        $userTitle = $form->getData();

        $em->persist($userTitle);
        $em->flush();

        return new Response(json_encode(array("success"=>true,"message"=>"User Title updated")));
    }

    public function promoCodesAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        $promoCodes = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:PromoCode')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        return $this->render('AdminBundle:Default:promocodes.html.twig', array(
            'session' => $session->all(),
            'promoCodes' => $promoCodes
        ));
    }

    public function promoCodesTableAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        $promoCodes = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:PromoCode')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        return $this->render('AdminBundle:Default:promocodesTable.html.twig', array(
            'promoCodes' => $promoCodes
        ));
    }

    public function editPromoCodeModalAction(Request $request, $promoCodeId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        if (!isset($promoCodeId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error promoCodeId")));
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $promoCode = new PromoCode();

        if ($promoCodeId != 'new')
            $promoCode = $em
                ->getRepository('AppBundle:PromoCode')
                ->find($promoCodeId);

        if (!$promoCode)
            return new Response(json_encode(array("error"=>true,"message"=>"error Promo Code not found")));

        $form = $this->createForm(PromoCodeForm::class, $promoCode);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('AdminBundle:Modals:edit_promocode.html.twig', array(
                'form' => $form->createView(),
                'promoCodeId' => $promoCodeId
            ));

        $promoCode = $form->getData();

        $em->persist($promoCode);
        $em->flush();

        return new Response(json_encode(array("success"=>true,"message"=>"Promo Code updated")));
    }

    public function profileImagesAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login'))
            return $this->redirect($this->generateUrl('admin_login'));

        $profileImages = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:ProfileImage')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

            return $this->render('AdminBundle:Default:profileimages.html.twig', array(
                'session' => $session->all(),
                'profileImages' => $profileImages
            ));
    }

    public function profileImagesTableAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login'))
            return $this->redirect($this->generateUrl('admin_login'));

        $profileImages = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:ProfileImage')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        return $this->render('AdminBundle:Default:profileimagesTable.html.twig', array(
            'profileImages' => $profileImages
        ));
    }

    public function editProfileImageModalAction(Request $request, $profileImageId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        if (!isset($profileImageId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error profileImageId")));
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $profileImage = new ProfileImage();

        if ($profileImageId != 'new')
            $profileImage = $em
                ->getRepository('AppBundle:ProfileImage')
                ->find($profileImageId);

        if (!$profileImage)
            return new Response(json_encode(array("error"=>true,"message"=>"error Profile Image not found")));

        $image = $profileImage->getImage();

        $form = $this->createForm(ProfileImageForm::class, $profileImage);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('AdminBundle:Modals:edit_profileimage.html.twig', array(
                'form' => $form->createView(),
                'profileImageId' => $profileImageId
            ));

        $profileImage = $form->getData();
        if (!is_null($profileImage->getImage())) {
            $filename = $this->saveProfileImage($profileImage);
            $profileImage->setImage($filename);
        }
        else {
            $profileImage->setImage($image);
        }

        $em->persist($profileImage);
        $em->flush();

        return new Response(json_encode(array("success"=>true,"message"=>"Profile image updated")));
    }

    private function saveProfileImage($profileImage) {
        $fs = new Filesystem();
        $fs->mkdir($this->getParameter('profile_image_directory'));
        $file = $profileImage->getImage();
        $filename = md5(uniqid()).'.png';
        $file->move(
            $this->getParameter('profile_image_directory'),
            $filename
        );
        return $filename;
    }
}

<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function editUsersRolesModalAction(Request $request, $userId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        if (!isset($userId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error userId")));
        }

        $user = $users = $this
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
}

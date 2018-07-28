<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    public $requiredRole = 'ROLE_ADMIN';

    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        if ($session->has('login'))
            return $this->redirect($this->generateUrl('admin_homepage'));

        if ($request->isMethod('POST') && !is_null($_POST['email']) && !is_null($_POST['password'])) {
            $userArray = $this->get("engine_services.accounts")->login($_POST['email'], $_POST['password'], $this->requiredRole);

            if ($userArray['code'] == 200) {
                $user = $userArray['user'];
                $session->set('login', $user->getFirstName()." ".$user->getLastName());
                $session->set('roles', $user->getRoles());
                return $this->redirect($this->generateUrl('admin_homepage'));
            }
            else {
                $session->getFlashBag()->add('error', "Cet utilisateur n'a pas les droits requis");
            }
        }

        return $this->render('AdminBundle:Account:login.html.twig');
    }

    public function logoutAction(Request $request)
    {
        $session = $request->getSession();

        if ($session->has('login')) {
            $session->clear();
        }

        return $this->redirect($this->generateUrl('admin_login'));
    }
}

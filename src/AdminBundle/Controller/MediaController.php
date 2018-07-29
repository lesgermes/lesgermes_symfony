<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\MediaType;
use AppBundle\Entity\Media;
use AppBundle\Entity\MediaGroup;
use AppBundle\Entity\MediaGroupsMedias;
use AppBundle\Form\MediaTypeForm;
use AppBundle\Form\MediaForm;
use AppBundle\Form\MediaGroupForm;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class MediaController extends Controller
{
    public function getMediaTypesListAction(Request $request) {
        $session = $request->getSession();
        if (!$session->has('login'))
            return $this->redirect($this->generateUrl('admin_login'));

        $mediaTypes = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:MediaType')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );
        return $this->render('AdminBundle:Default:mediaTypes.html.twig', array(
            'session' => $session->all(),
            'mediaTypes' => $mediaTypes
        ));
    }

    public function editMediaTypeModalAction(Request $request, $mediaTypeId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return new Response(json_encode(array("error"=>true,"message"=>"Session expired")));
        }

        if (!isset($mediaTypeId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error mediaTypeId")));
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $mediaType = new MediaType();

        if ($mediaTypeId != 'new')
            $mediaType = $em
                ->getRepository('AppBundle:MediaType')
                ->find($mediaTypeId);

        if (!$mediaType)
            return new Response(json_encode(array("error"=>true,"message"=>"error Media Type not found")));

        $form = $this->createForm(MediaTypeForm::class, $mediaType);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('AdminBundle:Modals:edit_mediaType.html.twig', array(
                'form' => $form->createView(),
                'mediaTypeId' => $mediaTypeId
            ));

        $mediaType = $form->getData();

        $em->persist($mediaType);
        $em->flush();

        return new Response(json_encode(array("success"=>true,"message"=>"Media Type updated")));
    }

    public function getMediaTypesTableAction (Request $request) {
        $session = $request->getSession();

        if (!$session->has('login'))
            return new Response(json_encode(array("error"=>true,"message"=>"Session expired")));

        $mediaTypes = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:MediaType')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        return $this->render('AdminBundle:Default:mediaTypesTable.html.twig', array(
            'mediaTypes' => $mediaTypes
        ));
    }

    public function getMediasListAction(Request $request) {
        $session = $request->getSession();
        if (!$session->has('login'))
            return $this->redirect($this->generateUrl('admin_login'));

        $medias = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Media')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );
        return $this->render('AdminBundle:Default:medias.html.twig', array(
            'session' => $session->all(),
            'medias' => $medias
        ));
    }

    public function editMediaModalAction(Request $request, $mediaId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return $this->redirect($this->generateUrl('admin_login'));
        }

        if (!isset($mediaId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error mediaId")));
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $media = new Media();

        if ($mediaId != 'new')
            $media = $em
                ->getRepository('AppBundle:Media')
                ->find($mediaId);

        if (!$media)
            return new Response(json_encode(array("error"=>true,"message"=>"error Media not found")));

        $form = $this->createForm(MediaForm::class, $media);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('AdminBundle:Modals:edit_media.html.twig', array(
                'form' => $form->createView(),
                'mediaId' => $mediaId
            ));

        $media = $form->getData();

        $em->persist($media);
        $em->flush();

        return new Response(json_encode(array("success"=>true,"message"=>"Media updated")));
    }

    public function getMediasTableAction (Request $request) {
        $session = $request->getSession();

        if (!$session->has('login'))
            return new Response(json_encode(array("error"=>true,"message"=>"Session expired")));

        $medias = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Media')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        return $this->render('AdminBundle:Default:mediasTable.html.twig', array(
            'medias' => $medias
        ));
    }

    public function getGroupsListAction(Request $request) {
        $session = $request->getSession();
        if (!$session->has('login'))
            return $this->redirect($this->generateUrl('admin_login'));

        $groups = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:MediaGroup')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );
        return $this->render('AdminBundle:Default:mediaGroups.html.twig', array(
            'session' => $session->all(),
            'groups' => $groups
        ));
    }

    public function editGroupModalAction(Request $request, $groupId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return new Response(json_encode(array("error"=>true,"message"=>"Session expired")));
        }

        if (!isset($groupId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error groupId")));
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $group = new MediaGroup();

        if ($groupId != 'new')
            $group = $this->get('app_services.media_groups')->getGroup($groupId);

        if (!$group)
            return new Response(json_encode(array("error"=>true,"message"=>"error Media Group not found")));

        $form = $this->createForm(MediaGroupForm::class, $group);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid())
            return $this->render('AdminBundle:Modals:edit_mediaGroup.html.twig', array(
                'form' => $form->createView(),
                'groupId' => $groupId
            ));

        $group = $form->getData();

        $em->persist($group);
        $em->flush();

        return new Response(json_encode(array("success"=>true,"message"=>"Group updated")));
    }

    public function getGroupsTableAction(Request $request) {
        $session = $request->getSession();

        if (!$session->has('login'))
            return new Response(json_encode(array("error"=>true,"message"=>"Session expired")));

        $groups = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:MediaGroup')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        return $this->render('AdminBundle:Default:mediaGroupsTable.html.twig', array(
            'groups' => $groups
        ));
    }

    public function editGroupMediasModalAction(Request $request, $groupId) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return new Response(json_encode(array("error"=>true,"message"=>"Session expired")));
        }

        if (!isset($groupId)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error groupId")));
        }

        $group = $this->get('app_services.media_groups')->getGroup($groupId);

        if (!$group)
            return new Response(json_encode(array("error"=>true,"message"=>"error Media Group not found")));

        $medias = $this->get('app_services.media_groups')->getGroupMedias($group);

        $formMedias = $this->createFormBuilder()
            ->add('media', EntityType::class, array(
                'class' => 'AppBundle:Media',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un Media',
                'attr' => ['id' => 'MediaDropDown']
            ))
            ->add('add', ButtonType::class, array('label' => 'Ajouter Media', 'attr' =>
                ['onclick' => 'buttonAddMediaGroupsMedias(this, '.$groupId.')']
            ))
            ->getForm();


        return $this->render('AdminBundle:Modals:edit_mediaGroupsMedias.html.twig', array(
            'group' => $group,
            'medias' => $medias,
            'formMedias' => $formMedias->createView()
        ));
    }

    public function doEditGroupMediasModalAction(Request $request, $groupId, $action, $id) {
        $session = $request->getSession();

        if (!$session->has('login')) {
            return new Response(json_encode(array("error"=>true,"message"=>"Session expired")));
        }

        if (!isset($groupId) || !isset($action) || !isset($id)) {
            return new Response(json_encode(array("error"=>true,"message"=>"error form")));
        }

        $em = $this
            ->getDoctrine()
            ->getManager();

        $group = $this->get('app_services.media_groups')->getGroup($groupId);

        if (!$group)
            return new Response(json_encode(array("error"=>true,"message"=>"error Media Group not found")));

        if ($action == 'add') {
            $media = $em->getRepository('AppBundle:Media')->find($id);
            $link = new MediaGroupsMedias();
            $link->setGroup($group);
            $link->setMedia($media);
            $em->persist($link);
            $em->flush();
            return new Response(json_encode(array("success"=>true,"message"=>"Media Group Updated")));
        }
        else if ($action == 'delete') {
            $media = $em->getRepository('AppBundle:Media')->find($id);
            $links = $em
                ->getRepository('AppBundle:MediaGroupsMedias')
                ->findBy(array(
                    'group' => $group,
                    'media' => $media
                ));
            foreach ($links as $link)
                $em->remove($link);
            $em->flush();
            return new Response(json_encode(array("success"=>true,"message"=>"Media Group Updated")));
        }
        else {
            return new Response(json_encode(array("error"=>true,"message"=>"error action")));
        }
    }
}

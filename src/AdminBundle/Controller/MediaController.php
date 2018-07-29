<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\MediaType;
use AppBundle\Form\MediaTypeForm;
use Symfony\Component\HttpFoundation\Response;

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
}

<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class MediaController extends Controller
{
    /**
     * Get Media Groups List
     *
     * @Get("/mediaGroupsList")
     * @ApiDoc(
     *  resource=true,
     *  description=" Get Media Groups List",
     *  statusCodes={
     *         200="Returned when successful"
     *  }
     * )
     *
     */
    public function getMediaGroupsListAction(Request $request) {
        $em = $this
            ->getDoctrine()
            ->getManager();
        
        $mediaGroups = $em
            ->getRepository('AppBundle:MediaGroup')
            ->findBy(
                array(),    //where
                array('id' => 'ASC')//order
            );

        foreach ($mediaGroups as $mediaGroup) {
            $groupMediasRefs = $em
                ->getRepository('AppBundle:MediaGroupsMedias')
                ->findBy(
                    array(//where
                        'group' => $mediaGroup
                    ),
                    array('id' => 'ASC')//order
                );
            
            $groupMedias = [];

            foreach ($groupMediasRefs as $groupMediasRef) {
                array_push($groupMedias, $groupMediasRef->getMedia());
            }

            $mediaGroup->setMedias($groupMedias);
        }

        return View::create()
            ->setStatusCode(200)
            ->setData($mediaGroups);
    }
}

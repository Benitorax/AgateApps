<?php

namespace CorahnRin\MapsBundle\Controller;

use CorahnRin\MapsBundle\Entity\Markers;
use CorahnRin\MapsBundle\Entity\MarkersTypes;
use CorahnRin\MapsBundle\Form\MarkersType;
use CorahnRin\MapsBundle\Form\MarkersTypesType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MarkersController extends Controller {

    /**
     * @Route("/admin/maps/markers/")
     * @Template()
     */
    public function adminListAction() {
        return array(
            'markers' => $this->getDoctrine()->getManager()->getRepository('CorahnRinMapsBundle:Markers')->findAll(),
            'markersTypes' => $this->getDoctrine()->getManager()->getRepository('CorahnRinMapsBundle:MarkersTypes')->findAll()
        );
    }

    /**
     * @Route("/admin/maps/markers/add/")
     * @Template("CorahnRinAdminBundle:Form:add.html.twig")
     */
    public function addAction() {
        $marker = new Markers;
        $form = $this->createForm(new MarkersType, $marker);

        $request = $this->get('request');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($marker);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Marqueur ajouté : <strong>'.$marker->getName().'</strong>');
            return $this->redirect($this->generateUrl('corahnrin_maps_markers_adminlist'));
        }

        return array(
            'form' => $form->createView(),
            'marker' => $marker,
            'title' => 'Ajouter un marqueur',
            'breadcrumbs' => array(
                'Accueil' => array('route' => 'corahnrin_pages_pages_index',),
                'Marqueurs' => array('route'=>'corahnrin_maps_markers_adminlist'),
            ),
        );
    }

    /**
     * @Route("/admin/maps/markers/types/add")
     * @Template("CorahnRinAdminBundle:Form:add.html.twig")
     */
    public function addTypeAction() {
        $markerType = new \CorahnRin\MapsBundle\Entity\MarkersTypes;
        $form = $this->createForm(new MarkersTypesType, $markerType);

        $request = $this->get('request');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($markerType);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Type de marqueur ajouté : <strong>'.$markerType->getName().'</strong>');
            return $this->redirect($this->generateUrl('corahnrin_maps_markers_adminlist'));
        }

        return array(
            'form' => $form->createView(),
            'title' => 'Ajouter un type de marqueur',
            'breadcrumbs' => array(
                'Accueil' => array('route' => 'corahnrin_pages_pages_index',),
                'Marqueurs' => array('route'=>'corahnrin_maps_markers_adminlist'),
            ),
        );
    }

    /**
     * @Route("/admin/maps/markers/edit/{id}")
     * @Template("CorahnRinAdminBundle:Form:add.html.twig")
     */
    public function editAction(Markers $marker) {

        $form = $this->createForm(new MarkersType, $marker);

        $request = $this->get('request');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($marker);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Marqueur modifié : <strong>'.$marker->getName().'</strong>');
            return $this->redirect($this->generateUrl('corahnrin_maps_markers_adminlist'));
        }

        return array(
            'form' => $form->createView(),
            'marker' => $marker,
            'title' => 'Modifier un marqueur',
            'breadcrumbs' => array(
                'Accueil' => array('route' => 'corahnrin_pages_pages_index',),
                'Marqueurs' => array('route'=>'corahnrin_maps_markers_adminlist'),
            ),
        );
    }

    /**
     * @Route("/admin/maps/markers/types/edit/{id}")
     * @Template("CorahnRinAdminBundle:Form:add.html.twig")
     */
    public function editTypeAction(MarkersTypes $markerType) {

        $form = $this->createForm(new MarkersTypesType, $markerType);

        $request = $this->get('request');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($markerType);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Type de marqueur modifié : <strong>'.$markerType->getName().'</strong>');
            return $this->redirect($this->generateUrl('corahnrin_maps_markers_adminlist'));
        }

        return array(
            'form' => $form->createView(),
//            'marker' => $markerType,
            'title' => 'Modifier un type de marqueur',
            'breadcrumbs' => array(
                'Accueil' => array('route' => 'corahnrin_pages_pages_index',),
                'Marqueurs' => array('route'=>'corahnrin_maps_markers_adminlist'),
            ),
        );
    }

    /**
     * @Route("/admin/maps/markers/delete/{id}")
     * @Template()
     */
    public function deleteAction($id)
    {
    }


}

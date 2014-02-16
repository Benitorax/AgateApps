<?php

namespace CorahnRin\CharactersBundle\Controller;

use \CorahnRin\CharactersBundle\Entity\Artifacts;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ArtifactsController extends Controller
{
    /**
     * @Route("/admin/generator/artifacts/")
     * @Template()
     */
    public function adminListAction() {
        $name = str_replace('Controller','',preg_replace('#^([a-zA-Z]+\\\)*#isu', '', __CLASS__));
        return array(
            strtolower($name) => $this->getDoctrine()->getManager()->getRepository('CorahnRinCharactersBundle:'.$name)->findAll(),
        );
    }

    /**
     * @Route("/admin/generator/artifacts/add/")
     * @Template("CorahnRinAdminBundle:Form:add.html.twig")
     */
    public function addAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN_GENERATOR_SUPER')) {
            throw new AccessDeniedException();
        }
        return $this->handle_request(new Artifacts);
    }

    /**
     * @Route("/admin/generator/artifacts/edit/{id}")
     * @Template("CorahnRinAdminBundle:Form:add.html.twig")
     */
    public function editAction(Artifacts $artifact)
    {
        return $this->handle_request($artifact);
    }

    /**
     * @Route("/admin/generator/artifacts/delete/{id}")
     * @Template()
     */
    public function deleteAction(Artifacts $element)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN_GENERATOR_SUPER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $element->setDeleted(1);
        $em->persist($element);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'Artefact supprimé : <strong>'.$element->getName().'</strong>');
        return $this->redirect($this->generateUrl('corahnrin_characters_artifacts_adminlist'));
    }

    private function handle_request(Artifacts $element) {
        $method = preg_replace('#^'.str_replace('\\','\\\\',__CLASS__).'::([a-zA-Z]+)Action$#isUu', '$1', $this->getRequest()->get('_controller'));

        $form = $this->createForm(new \CorahnRin\CharactersBundle\Form\ArtifactsType(), $element);

        $request = $this->get('request');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($element);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Artefact '.($method=='add'?'ajouté':'modifié').' : <strong>'.$element->getName().'</strong>');
            return $this->redirect($this->generateUrl('corahnrin_characters_artifacts_adminlist'));
        }

        return array(
            'form' => $form->createView(),
            'title' => ($method=='add'?'Ajouter':'Modifier').' un Artefact',
            'breadcrumbs' => array(
                'Accueil' => array('route' => 'corahnrin_admin_admin_index',),
                'Artefacts' => array('route'=>'corahnrin_characters_artifacts_adminlist'),
            ),
        );
    }
}
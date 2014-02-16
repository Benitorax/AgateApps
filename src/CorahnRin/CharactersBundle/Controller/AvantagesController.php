<?php

namespace CorahnRin\CharactersBundle\Controller;

use \CorahnRin\CharactersBundle\Entity\Avantages;
use \CorahnRin\CharactersBundle\Form\AvantagesType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AvantagesController extends Controller
{
    /**
     * @Route("/admin/generator/avantages/")
     * @Template()
     */
    public function adminListAction() {
        return $this->getDoctrine()->getManager()->getRepository('CorahnRinCharactersBundle:Avantages')->findAllDifferenciated();
    }

    /**
     * @Route("/admin/generator/avantages/add/")
     * @Template("CorahnRinAdminBundle:Form:add.html.twig")
     */
    public function addAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN_GENERATOR_SUPER')) {
            throw new AccessDeniedException();
        }
        return $this->handle_request(new Avantages);
    }

    /**
     * @Route("/admin/generator/avantages/edit/{id}")
     * @Template("CorahnRinAdminBundle:Form:add.html.twig")
     */
    public function editAction(Avantages $avantage)
    {
        return $this->handle_request($avantage);
    }

    /**
     * @Route("/admin/generator/avantages/delete/{id}")
     * @Template()
     */
    public function deleteAction(Avantages $element)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN_GENERATOR_SUPER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $element->setDeleted(1);
        $em->persist($element);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'Avantage supprimé : <strong>'.$element->getName().'</strong>');
        return $this->redirect($this->generateUrl('corahnrin_characters_avantages_adminlist'));
    }

    private function handle_request(Avantages $element) {
        $method = preg_replace('#^'.str_replace('\\','\\\\',__CLASS__).'::([a-zA-Z]+)Action$#isUu', '$1', $this->getRequest()->get('_controller'));

        if ($element->getBonusdisc()) {
            $bonuses = explode(',', $element->getBonusdisc());
            $bonuses = array_combine($bonuses, $bonuses);
            $element->setBonusDisc($bonuses);
        } else {
            $element->setBonusDisc(null);
        }

        $bonuses = $this->getDoctrine()->getManager()->getRepository('CorahnRinCharactersBundle:Domains')->findAllSortedByName();

        $bonuses['100g'] = '100 Daols de Givre';
        $bonuses['50g'] = '50 Daols de Givre';
        $bonuses['20g'] = '20 Daols de Givre';
        $bonuses['50a'] = '50 Daols d\'Azur';
        $bonuses['20a'] = '20 Daols d\'Azur';
        $bonuses['resm'] = 'Résistance mentale';
        $bonuses['bless'] = 'Blessures';
        $bonuses['vig'] = 'Vigueur';
        $bonuses['trau'] = 'Traumatismes';
        $bonuses['def'] = 'Défense';
        $bonuses['rap'] = 'Rapidité';
        $bonuses['sur'] = 'Survie';

//        exit('<pre>'.print_r($bonuses,true).print_r($element->getBonusdisc(),true));

        $form = $this->createForm(new AvantagesType(), $element);

        $form->add('bonusdisc', 'choice', array(
            'label'=>'Bonus (+1)',
            'choices' => $bonuses,
            'multiple' => true,
            'required'=>false,
            'attr' => array('size'=>10),
        ));

        $request = $this->get('request');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $element->setBonusDisc(implode(',', array_keys($element->getBonusdisc())));

            $em = $this->getDoctrine()->getManager();
            $em->persist($element);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', ($element->getIsDesv() ? 'Désavantage' : 'Avantage').' '.($method=='add'?'ajouté':'modifié').' : <strong>'.$element->getName().'</strong>');
            return $this->redirect($this->generateUrl('corahnrin_characters_avantages_adminlist'));
        }

        return array(
            'form' => $form->createView(),
            'title' => ($method=='add'?'Ajouter':'Modifier').' un '.($element->getIsDesv() ? 'Désavantage' : 'Avantage'),
            'breadcrumbs' => array(
                'Accueil' => array('route' => 'corahnrin_admin_admin_index',),
                'Avantages' => array('route'=>'corahnrin_characters_avantages_adminlist'),
            ),
        );
    }

}
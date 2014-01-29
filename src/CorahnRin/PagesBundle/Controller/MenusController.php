<?php

namespace CorahnRin\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MenusController extends Controller {

	/**
	 * @Template()
	 */
	public function menuAction($route = '') {
        if (preg_match('#^corahnrin_maps#isUu', $route)) {
            $method_name = 'EsterenMaps';
            $brandTitle = 'Esteren Maps';
            $brandRoute = 'corahnrin_maps_maps_index';
        } else {
            $method_name = 'CorahnRin';
            $brandTitle = 'Corahn-Rin';
            $brandRoute = 'corahnrin_pages_pages_index';
        }
        $links = $this->{'menu'.$method_name}();
        $langs = $this->get('translator')->getLangs();
		$username = $this->get('fos_user.user_provider.username');
		return array(
            'links' => $links,
            'brandTitle' => $brandTitle,
            'brandRoute' => $brandRoute,
            'route_name' => $route,
            'locale'=>$this->get('session')->get('_locale'),
            'langs' => $langs
        );
	}

    protected function menuCorahnRin(){
		return array(
			'corahnrin_characters_generator_index' => 'Générateur',
			'corahnrin_characters_viewer_list' => 'Personnages',
			'corahnrin_maps_maps_index' => 'Esteren Maps',
		);
    }

    protected function menuEsterenMaps(){
        $maps = $this->getDoctrine()->getManager()->getRepository('CorahnRinMapsBundle:Maps')->findAll(true);
        $maps_list = array();
        foreach ($maps as $id => $map) {
            $maps_list[$id] = array(
                'route' => 'corahnrin_maps_maps_view',
                'name' => $map->getName(),
                'attributes' => array(
                    'id' => $map->getId(),
                    'nameSlug' => $map->getNameSlug(),
                ),
            );
        }
		$links = array(
			'dropdown_list_1' => array(
                'name' => 'Voir une carte',
                'list' => $maps_list,
            ),
			'corahnrin_pages_pages_index' => 'Corahn-Rin',
		);
        return $links;
    }
}

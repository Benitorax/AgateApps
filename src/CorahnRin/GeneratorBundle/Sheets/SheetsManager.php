<?php

namespace CorahnRin\GeneratorBundle\Sheets;

use CorahnRin\CharactersBundle\Entity\Characters;

/**
 * Class ServiceManager
 * Project corahn_rin
 *
 * @author Pierstoval
 * @version 1.0 20/02/2014
 */
abstract class SheetsManager implements ManagerInterface {

    /**
     * @var \CorahnRin\GeneratorBundle\Sheets\SheetsService
     */
    private $service;
    private $locale;

    function __construct(SheetsService $service) {
        $this->service = $service;
        $this->locale = $service->getTranslator()->getLocale();
        $this->folder = $service->getFolder();
    }

    /**
     * Exécute la fonction utilisateur du SheetsManager qui crée une (ou plusieurs) feuille(s) de personnage.<br />
     * Le format est le suivant :<br />
     *  {type}_{locale}_{page}_{printerFriendly}_{extension}<br />
     * L'extension et la locale sont automatiquement récupérée depuis le traducteur injecté.
     *
     * @param \CorahnRin\CharactersBundle\Entity\Characters $character
     * @param string $type Le type de feuille
     * @param boolean $printer_friendly
     * @param integer $page
     * @throws \Exception
     */
    function generateSheet(Characters $character, $type = 'original', $printer_friendly = false, $page = 0) {

        $method_name = $type.'Sheet';

        if (method_exists($this, $method_name)) {
            return $this->$method_name($character, $printer_friendly, $page);
        } else {
            throw new \Exception('La méthode "'.$type.'Sheet" n\'existe pas dans la classe "'.__CLASS__.'".');
        }
    }

    /**
     * Renvoie le SheetsService
     *
     * @return \CorahnRin\GeneratorBundle\Sheets\SheetsService
     */
    function getService() {
        return $this->service;
    }

    /**
     * Retourne le gestionnaire de feuille de personnage du type demandé
     *
     * @param string $type
     * @return \CorahnRin\GeneratorBundle\Sheets\SheetsManager
     */
    function getManager($type) {
        return $this->service->getManager($type);
    }

    /**
     * Retourne le chemin complet du dossier des feuilles de personnage source
     * @return string
     */
    function getFolder() {
        return $this->folder;
    }

    /**
     * Renvoie la locale actuelle
     * @return string
     */
    function getLocale() {
        return $this->locale;
    }

    /**
     * Convertit un chemin relatif à un bundle en un chemin absolu (via le kernel)
     * @param string $resource
     * @param string $dir
     * @param boolean $first
     * @return string
     */
    function locateResource($resource, $dir = null, $first = true) {
        return $this->service->locateResource($resource, $dir, $first);
    }
}

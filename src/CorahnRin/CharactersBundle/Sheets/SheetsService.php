<?php

namespace CorahnRin\CharactersBundle\Sheets;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class SheetsService
 * Project corahn_rin
 *
 * @author Pierstoval
 * @version 1.0 20/02/2014
 */
class SheetsService {

    private $managers;
    private $sheets_folder;
    private $translator;
    private $kernel;

    function __construct($sheets_folder, TranslatorInterface $translator, Kernel $kernel) {
        $this->sheets_folder = $sheets_folder;
        $this->translator = $translator;
        $this->kernel = $kernel;
    }

    /**
     * Retourne le gestionnaire de feuille de personnage du type demandé
     *
     * @param string $type Le type de manager à récupérer
     * @return ManagerInterface
     */
    function getManager($type) {
        return isset($this->manager[$type]) ? $this->manager[$type] : $this->createManager($type);
    }

    /**
     *
     * @return Symfony\Component\Translation\TranslatorInterface
     */
    function getTranslator(){
        return $this->translator;
    }

    /**
     * Retourne le dossier source des feuilles de personnage
     * @return string
     */
    function getFolder() {
        return $this->sheets_folder;
    }

    function locateResource($resource, $dir = null, $first = true) {
        return $this->kernel->locateResource($resource, $dir, $first);
    }

    private function createManager($type) {
        $type = ucfirst(strtolower($type));
        $className = '\CorahnRin\CharactersBundle\Sheets\Managers\\'.$type.'Manager';

        $manager = new $className($this);

        $this->manager[$type] = $manager;

        return $manager;
    }
}
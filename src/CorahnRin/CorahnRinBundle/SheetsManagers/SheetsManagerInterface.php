<?php

namespace CorahnRin\CorahnRinBundle\SheetsManagers;

use CorahnRin\CorahnRinBundle\Entity\Characters;
use CorahnRin\CorahnRinBundle\Services\Sheets;

/**
 * Interface ManagerInterface
 * Project corahn_rin
 *
 * @author Pierstoval
 * @version 1.0 20/02/2014
 */
interface SheetsManagerInterface {

    function __construct(Sheets $manager);

    function generateSheet(Characters $character, $type, $printer_friendly, $page);

}
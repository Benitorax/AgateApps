<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Step;

use EsterenMaps\Entity\Map;
use EsterenMaps\Repository\MapsRepository;
use EsterenMaps\Repository\ZonesRepository;
use Symfony\Component\HttpFoundation\Response;

class Step03Birthplace extends AbstractStepAction
{
    /**
     * @var int
     */
    private $tileSize;

    private $zonesRepository;
    private $mapsRepository;

    public function __construct(
        int $tileSize,
        ZonesRepository $zonesRepository,
        MapsRepository $mapsRepository
    ) {
        $this->tileSize = $tileSize;
        $this->zonesRepository = $zonesRepository;
        $this->mapsRepository = $mapsRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $regions = $this->zonesRepository->findAll('id');

        // FIXME: Find a way to not have to hardcode this.
        // Hardcoded here, it's base esteren map.
        $map = $this->mapsRepository->find(1);

        if ($this->request->isMethod('POST')) {
            $regionValue = (int) $this->request->request->get('region_value');
            if (isset($regions[$regionValue])) {
                $this->updateCharacterStep($regionValue);

                return $this->nextStep();
            }
            $this->flashMessage('Veuillez choisir une région de naissance correcte.');
        }

        return $this->renderCurrentStep([
            'map' => $map,
            'tile_size' => $this->tileSize,
            'regions_list' => $regions,
            'region_value' => $this->getCharacterProperty(),
        ], 'corahn_rin/Steps/03_birthplace.html.twig');
    }
}

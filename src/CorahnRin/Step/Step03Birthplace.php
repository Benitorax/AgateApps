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
use EsterenMaps\Entity\Zone;
use Symfony\Component\HttpFoundation\Response;

class Step03Birthplace extends AbstractStepAction
{
    /**
     * @var int
     */
    private $tileSize;

    public function __construct(int $tileSize)
    {
        $this->tileSize = $tileSize;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $regions = $this->em->getRepository(Zone::class)->findAll(true);

        // Hardcoded here, it's base esteren map.
        $map = $this->em->getRepository(Map::class)->find(1);

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

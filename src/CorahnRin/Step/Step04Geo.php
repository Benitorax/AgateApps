<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Step;

use CorahnRin\Entity\GeoEnvironment;
use Symfony\Component\HttpFoundation\Response;

class Step04Geo extends AbstractStepAction
{
    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $geoEnvironments = $this->em->getRepository(GeoEnvironment::class)->findAll(true);

        if ($this->request->isMethod('POST')) {
            $geoEnvironmentId = (int) $this->request->request->get('gen-div-choice');
            if (isset($geoEnvironments[$geoEnvironmentId])) {
                $this->updateCharacterStep($geoEnvironmentId);

                return $this->nextStep();
            }
            $this->flashMessage('Veuillez indiquer un lieu de vie géographique correct.');
        }

        return $this->renderCurrentStep([
            'geoEnvironments' => $geoEnvironments,
            'geoEnvironment_value' => $this->getCharacterProperty(),
        ], 'corahn_rin/Steps/04_geo.html.twig');
    }
}

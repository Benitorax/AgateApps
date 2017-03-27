<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\CorahnRinBundle\Step;

use CorahnRin\CorahnRinBundle\Entity\Ways;

class Step08Ways extends AbstractStepAction
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $ways = $this->em->getRepository('CorahnRinBundle:Ways')->findAll(true);

        $waysValues = $this->getCharacterProperty();

        if (!$waysValues) {
            $waysValues = $this->resetWays($ways);
        }

        if ($this->request->isMethod('POST')) {
            $waysValues = (array) $this->request->request->get('ways');

            $error                = false;
            $errorWayNotExists    = false;
            $errorValueNotInRange = false;
            $sum                  = 0;
            $has1or5              = false;

            foreach ($waysValues as $id => $value) {
                $value = (int) $value;

                // Make sure every way clearly exists.
                if (!array_key_exists($id, $ways) && false === $errorWayNotExists) {
                    $error             = true;
                    $errorWayNotExists = true;
                    $this->flashMessage('Erreur dans le formulaire. Merci de vérifier les valeurs soumises.');
                }

                // Make sure values are in proper ranges.
                if (($value <= 0 || $value > 5) && false === $errorValueNotInRange) {
                    $error                = true;
                    $errorValueNotInRange = true;
                    $this->flashMessage('Les voies doivent être comprises entre 1 et 5.');
                }

                // To be correct, we need the character to have at least 1 or 5 to at least one Way.
                if ($value === 1 || $value === 5) {
                    $has1or5 = true;
                }

                // Force integer value
                $waysValues[$id] = (int) $value;

                $sum += $value;
            }

            if ($sum !== 15) {
                $error = true;
                if ($sum > 5) {
                    $this->flashMessage('La somme des voies doit être égale à 15. Merci de corriger les valeurs de certaines voies.', 'warning');
                } else {
                    $this->flashMessage('Veuillez indiquer vos scores de Voies.');
                }
            }

            if (!$has1or5) {
                $error = true;
                $this->flashMessage('Au moins une des voies doit avoir un score de 1 ou de 5.', 'warning');
            }

            if (false === $error) {
                $this->updateCharacterStep($waysValues);

                return $this->nextStep();
            }

            $waysValues = $this->resetWays($ways);
        }

        return $this->renderCurrentStep([
            'ways_values' => $waysValues,
            'ways_list'   => $ways,
        ]);
    }

    /**
     * @param Ways[] $ways
     * @return array
     */
    private function resetWays(array $ways = [])
    {
        $values = [];

        foreach ($ways as $way) {
            $values[$way->getId()] = 1;
        }

        return $values;
    }
}

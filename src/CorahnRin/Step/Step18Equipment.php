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

use CorahnRin\Entity\Armors;
use CorahnRin\Entity\Weapon;
use Symfony\Component\HttpFoundation\Response;

class Step18Equipment extends AbstractStepAction
{
    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $weapons = $this->em->getRepository(Weapon::class)->findAllSortedByName();
        $armors = $this->em->getRepository(Armors::class)->findAllSortedByName();
        $setbacks = $this->getCharacterProperty('07_setbacks');
        $isPoor = isset($setbacks[9]) && !$setbacks[9]['avoided'];

        $currentStep = $this->getCharacterProperty() ?: $this->resetEquipment();

        if ($this->request->isMethod('POST')) {
            $postedArmors = (array) $this->request->get('armors', []);
            $postedWeapons = (array) $this->request->get('weapons', []);
            $postedEquipment = (array) $this->request->get('equipment', []);

            // Remove all entries
            $currentStep['armors'] = [];
            $currentStep['weapons'] = [];

            $errors = false;

            foreach ($postedArmors as $id) {
                if (!\array_key_exists($id, $armors)) {
                    $errors = true;
                    $this->flashMessage('errors.incorrect_values');
                    continue;
                }
                $currentStep['armors'][(int) $id] = true;
            }

            foreach ($postedWeapons as $id) {
                if (!\array_key_exists($id, $weapons)) {
                    $errors = true;
                    $this->flashMessage('errors.incorrect_values');
                    continue;
                }
                $currentStep['weapons'][(int) $id] = true;
            }

            $postedEquipment = \array_map(function ($e) {
                return \trim(\preg_replace('~\s\s+~', ' ', \strip_tags($e))); // Escape entries
            }, $postedEquipment);
            $postedEquipment = \array_unique($postedEquipment); // Remove duplicate entries
            $postedEquipment = \array_filter($postedEquipment, function ($e) {return !empty($e); }); // Remove empty entries

            $currentStep['equipment'] = $postedEquipment;

            if (false === $errors) {
                $this->updateCharacterStep($currentStep);

                return $this->nextStep();
            }
        }

        return $this->renderCurrentStep([
            'armors' => $armors,
            'weapons' => $weapons,
            'is_poor' => $isPoor,
            'selected_armors' => $currentStep['armors'],
            'selected_weapons' => $currentStep['weapons'],
            'equipment' => $currentStep['equipment'],
        ], 'corahn_rin/Steps/18_equipment.html.twig');
    }

    /**
     * @return array[]
     */
    private function resetEquipment()
    {
        return [
            'armors' => [],
            'weapons' => [],
            'equipment' => [],
        ];
    }
}

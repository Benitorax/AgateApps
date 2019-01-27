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

use CorahnRin\Entity\Setbacks;
use CorahnRin\Repository\SetbacksRepository;
use Symfony\Component\HttpFoundation\Response;

class Step07Setbacks extends AbstractStepAction
{
    /**
     * @var int
     */
    private $setbacksNumber = 0;

    /**
     * @var Setbacks[]
     */
    private $setbacks = [];

    private $setbacksRepository;

    public function __construct(SetbacksRepository $setbacksRepository)
    {
        $this->setbacksRepository = $setbacksRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        /* @var Setbacks[] $setbacks */
        $this->setbacks = $this->setbacksRepository->findAll(true);

        $setbacksValue = $this->getCharacterProperty() ?: [];

        $age = $this->getCharacterProperty('06_age');

        // The user should be able to determine setbacks automatically OR manually.
        $chooseStepsManually = $this->request->query->has('manual') ?: $this->request->request->has('manual');

        // Setbacks number depends on the age, according to the books.
        $this->setbacksNumber = 0;
        if ($age >= 21) {
            ++$this->setbacksNumber;
        }
        if ($age >= 26) {
            ++$this->setbacksNumber;
        }
        if ($age >= 31) {
            ++$this->setbacksNumber;
        }

        // Determine setbacks' specific calculation.
        if (!$this->setbacksNumber) {
            // No setback if the character is less than 21 years old.
            $setbacksValue = [];
            $this->updateCharacterStep([]);
        } elseif (!$chooseStepsManually && $this->setbacksNumber && !\count($setbacksValue)) {
            // Automatic calculation (roll dices, etc.)
            $setbacksValue = $this->determineSetbacksAutomatically();
            $this->updateCharacterStep($setbacksValue);
        } elseif ($chooseStepsManually && !$this->request->isMethod('POST')) {
            // Reset setbacks only for the view when user clicked "Choose setbacks manually".
            $setbacksValue = [];
        }

        if ($this->request->isMethod('POST')) {
            if ($chooseStepsManually) {
                $setbacksValue = $this->request->request->get('setbacks_value');

                // Make sure every setback sent in POST are valid
                $anyWrongSetbackId = false;

                foreach ($setbacksValue as $id) {
                    if (
                        !\array_key_exists((int) $id, $this->setbacks) // Setback has to exist
                        || \in_array((int) $id, [1, 10], true)        // If manually set, setback cannot be good/bad luck
                    ) {
                        $anyWrongSetbackId = true;
                    }
                }

                if (!$anyWrongSetbackId) {
                    $finalSetbacks = [];
                    foreach ($setbacksValue as $id) {
                        $finalSetbacks[$id] = ['id' => (int) $id, 'avoided' => false];
                    }
                    $this->updateCharacterStep($finalSetbacks);

                    return $this->nextStep();
                }

                $this->flashMessage('Veuillez entrer des revers correct(s).');
            } else {
                return $this->nextStep();
            }
        }

        return $this->renderCurrentStep([
            'age' => $age,
            'setbacks_number' => $this->setbacksNumber,
            'setbacks_value' => $setbacksValue,
            'setbacks_list' => $this->setbacks,
            'choice_available' => $chooseStepsManually,
        ], 'corahn_rin/Steps/07_setbacks.html.twig');
    }

    /**
     * @return array
     */
    private function determineSetbacksAutomatically()
    {
        $setbacksValue = [];

        // If the user does not choose to specify setbacks manually,
        // they will be determined automatically with dice throws.

        // Get the whole list in a special var so it can be modified.
        /** @var Setbacks[] $setbacksDiceList */
        $setbacksDiceList = \array_values($this->setbacks);

        // A loop is made through all steps until enough setbacks have been set.
        $loopIterator = $this->setbacksNumber;
        while ($loopIterator > 0) {
            // Roll the dice. (shuffle all setbacks and get the first found)
            \shuffle($setbacksDiceList);

            // Disable setback so we don't have it twice
            /** @var Setbacks $diceResult */
            $diceResult = \array_shift($setbacksDiceList);

            if (1 === $diceResult->getId()) {
                // Unlucky!

                // When character is unlucky, we add two setbacks instead of one

                // Add it to character's setbacks
                $setbacksValue[$diceResult->getId()] = ['id' => $diceResult->getId(), 'avoided' => false];

                // This will make another setback to be added automatically to the list.
                $loopIterator += 2;

                // We also need to remove the "lucky" setback from the list,
                // you can't be both lucky and unlucky, unfortunately ;).
                foreach ($setbacksDiceList as $k => $setback) {
                    if (10 === $setback->getId()) {
                        unset($setbacksDiceList[$k]);
                    }
                }
            } elseif (10 === $diceResult->getId()) {
                // Lucky!

                // When character is lucky, we add another setback, but mark it as "avoided".

                // Add "lucky" to list
                $setbacksValue[$diceResult->getId()] = ['id' => $diceResult->getId(), 'avoided' => false];

                // We also need to remove the "unlucky" setback from the list,
                // you can't be both lucky and unlucky, unfortunately ;).
                foreach ($setbacksDiceList as $k => $setback) {
                    if (1 === $setback->getId()) {
                        unset($setbacksDiceList[$k]);
                    }
                }

                // Now we determine which setback was avoided
                \shuffle($setbacksDiceList);
                $diceResult = \array_shift($setbacksDiceList);

                // Then add it and mark it as avoided
                $setbacksValue[$diceResult->getId()] = ['id' => $diceResult->getId(), 'avoided' => true];
            } else {
                // If not a specific setback (lucky or unlucky),
                // We add it totally normally
                $setbacksValue[$diceResult->getId()] = ['id' => $diceResult->getId(), 'avoided' => false];
            }
            --$loopIterator;
        }

        return $setbacksValue;
    }
}

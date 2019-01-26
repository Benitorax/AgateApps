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

use CorahnRin\Entity\Advantage;
use CorahnRin\Entity\Setbacks;
use Symfony\Component\HttpFoundation\Response;

class Step11Advantages extends AbstractStepAction
{
    private $hasError = false;
    /**
     * @var Advantage[][]
     */
    private $globalList;
    /**
     * @var Advantage[]
     */
    private $advantages;

    /**
     * @var Advantage[]
     */
    private $disadvantages;

    /**
     * @var int
     */
    private $experience;

    /**
     * @var string[]
     */
    private $indications;

    /**
     * @var Setbacks[]
     */
    private $setbacks = [];

    /**
     * @var Advantage[]
     */
    private $advantagesDisabledBySetbacks = [];

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $this->globalList = $this->em->getRepository(Advantage::class)->findAllDifferenciated();

        $currentStepValue = $this->getCharacterProperty();
        $this->advantages = $currentStepValue['advantages'] ?? [];
        $this->disadvantages = $currentStepValue['disadvantages'] ?? [];
        $characterSetbacks = $this->getCharacterProperty('07_setbacks');
        $nonAvoidedSetbacks = [];
        foreach ($characterSetbacks as $id => $values) {
            if ($values['avoided']) {
                continue;
            }
            $nonAvoidedSetbacks[] = $id;
        }
        if (0 !== \count($nonAvoidedSetbacks)) {
            $this->setbacks = $this->em->getRepository(Setbacks::class)->findWithDisabledAdvantages($nonAvoidedSetbacks);
        }

        // Disable advantages that must not be chosen based on setbacks
        foreach ($this->setbacks as $setback) {
            if (\count($disabledDisadvantages = $setback->getDisabledAdvantages()) > 0) {
                foreach ($disabledDisadvantages as $advantage) {
                    $this->advantagesDisabledBySetbacks[$advantage->getId()] = [
                        'setback' => $setback,
                        'advantage' => $advantage,
                    ];
                    unset(
                        $this->globalList['advantages'][$advantage->getId()],
                        $this->globalList['disadvantages'][$advantage->getId()]
                    );
                }
            }
        }

        $this->indications = \array_filter($currentStepValue['advantages_indications'] ?? []);

        $this->calculateExperience();

        if ($response = $this->handlePost()) {
            return $response;
        }

        return $this->renderCurrentStep([
            'experience' => $this->experience,
            'indication_type_single_choice' => Advantage::INDICATION_TYPE_SINGLE_CHOICE,
            'indication_type_single_value' => Advantage::INDICATION_TYPE_SINGLE_VALUE,
            'character_indications' => $this->indications,
            'advantages' => $this->advantages,
            'disadvantages' => $this->disadvantages,
            'advantages_list' => $this->globalList['advantages'],
            'disadvantages_list' => $this->globalList['disadvantages'],
        ], 'corahn_rin/Steps/11_advantages.html.twig');
    }

    private function calculateExperience(): void
    {
        $this->experience = $experience = 100;

        foreach ($this->disadvantages as $id => $value) {
            /** @var Advantage $disadvantage */
            $disadvantage = $this->globalList['disadvantages'][$id];
            if (1 === $value) {
                $experience += $disadvantage->getXp();
            } elseif (2 === $value && 1 === $disadvantage->getBonusCount()) {
                $experience += \floor($disadvantage->getXp() * 1.5);
            } elseif (3 === $value && 2 === $disadvantage->getBonusCount()) {
                $experience += $value * $disadvantage->getXp();
            } elseif ($value) {
                $this->hasError = true;
                $this->flashMessage('Une valeur incorrecte a été donnée à un désavantage.');

                return;
            }
            if ($experience > 180) {
                $this->hasError = true;
                $this->flashMessage('Vos désavantages vous donnent un gain d\'expérience supérieur à 80.');

                return;
            }
        }

        foreach ($this->advantages as $id => $value) {
            /** @var Advantage $advantage */
            $advantage = $this->globalList['advantages'][$id];
            if (1 === $value) {
                $experience -= $advantage->getXp();
            } elseif (2 === $value && 1 === $advantage->getBonusCount()) {
                $experience -= \floor($advantage->getXp() * 1.5);
            } elseif (3 === $advantage->getBonusCount()) {
                // It's not used, but maybe one day...
                $experience -= $value * $advantage->getXp();
            } elseif ($value) {
                $this->hasError = true;
                $this->flashMessage('Une valeur incorrecte a été donnée à un avantage.');

                return;
            }
        }

        if ($experience < 0) {
            $this->hasError = true;
            $this->flashMessage('Vous n\'avez pas assez d\'expérience.');

            return;
        }

        $this->experience = $experience;
    }

    private function handlePost(): ?Response
    {
        if (!$this->request->isMethod('POST')) {
            return null;
        }

        $intval = function ($e) { return (int) $e; };
        $this->indications = \array_filter($this->request->request->get('advantages_indications') ?: []);
        $advantages = \array_map($intval, $this->request->request->get('advantages') ?: []);
        $disadvantages = \array_map($intval, $this->request->request->get('disadvantages') ?: []);

        $numberOfAdvantages = 0;
        $numberOfUpgradedAdvantages = 0;
        $numberOfUpgradedDisadvantages = 0;
        $numberOfDisadvantages = 0;

        // First, validate all IDs
        foreach ($advantages as $id => $value) {
            if (!\array_key_exists($id, $this->globalList['advantages'])) {
                $this->hasError = true;
                if (isset($this->advantagesDisabledBySetbacks[$id])) {
                    $this->flashMessage('L\'avantage "%adv%" a été désactivé par le revers "%setback%".', 'error', [
                        '%adv%' => $this->advantagesDisabledBySetbacks[$id]['advantage']->getName(),
                        '%setback%' => $this->advantagesDisabledBySetbacks[$id]['setback']->getName(),
                    ]);
                } else {
                    $this->flashMessage('Les avantages soumis sont incorrects.');
                }
                break;
            }
            if (0 === $value) {
                // Dont put zero values in session, it's useless
                unset($advantages[$id]);
                continue;
            }

            if (2 === $value) {
                if ($numberOfUpgradedAdvantages + 1 > 1) {
                    $this->hasError = true;
                    $this->flashMessage('Vous ne pouvez pas améliorer plus d\'un avantage');
                }
                $numberOfUpgradedAdvantages++;
            }

            $advantage = $this->globalList['advantages'][$id];

            if (null === $advantage) {
                $this->hasError = true;
                $this->flashMessage('Les avantages soumis sont incorrects.');
                break;
            }

            if ($value > 0 && $advantage->getRequiresIndication()) {
                $indication = \trim($this->indications[$id] ?? '');
                if (!$indication) {
                    $this->hasError = true;
                    $this->flashMessage('L\'avantage "%advtg%" nécessite une indication supplémentaire.', 'error', ['%advtg%' => $advantage->getName()]);
                    break;
                }
                if (Advantage::INDICATION_TYPE_SINGLE_CHOICE === $advantage->getIndicationType()) {
                    $choices = $advantage->getBonusesFor();
                    if (!\in_array($indication, $choices, true)) {
                        $this->hasError = true;
                        $this->flashMessage('L\'indication pour l\'avantage "%advtg%" n\'est pas correcte, veuillez vérifier.', 'error', ['%advtg%' => $advantage->getName()]);
                        break;
                    }
                }
            }

            if ($numberOfAdvantages + 1 > 4) {
                $this->hasError = true;
                $this->flashMessage('Vous ne pouvez pas avoir plus de 4 avantages.');
            }
            $numberOfAdvantages++;
        }

        foreach ($disadvantages as $id => $value) {
            if (!\array_key_exists($id, $this->globalList['disadvantages'])) {
                $this->hasError = true;
                if (isset($this->advantagesDisabledBySetbacks[$id])) {
                    $this->flashMessage('Le désavantage "%adv%" a été désactivé par le revers "%setback%".', 'error', [
                        '%adv%' => $this->advantagesDisabledBySetbacks[$id]['advantage']->getName(),
                        '%setback%' => $this->advantagesDisabledBySetbacks[$id]['setback']->getName(),
                    ]);
                } else {
                    $this->flashMessage('Les désavantages soumis sont incorrects.');
                }
                break;
            }
            if (0 === $value) {
                // Dont put zero values in session, it's useless
                unset($disadvantages[$id]);
                continue;
            }

            if (2 === $value) {
                if ($numberOfUpgradedDisadvantages + 1 > 1) {
                    $this->hasError = true;
                    $this->flashMessage('Vous ne pouvez pas améliorer plus d\'un désavantage');
                }
                $numberOfUpgradedDisadvantages++;
            }

            $disadvantage = $this->globalList['disadvantages'][$id];

            if (null === $disadvantage) {
                $this->hasError = true;
                $this->flashMessage('Les désavantages soumis sont incorrects.');
                break;
            }

            if ($value > 0 && $disadvantage->getRequiresIndication()) {
                $indication = \trim($this->indications[$id] ?? '');
                if (!$indication) {
                    $this->hasError = true;
                    $this->flashMessage('Le désavantage "%advtg%" nécessite une indication supplémentaire.', 'error', ['%advtg%' => $disadvantage->getName()]);
                    break;
                }
                if (Advantage::INDICATION_TYPE_SINGLE_CHOICE === $disadvantage->getIndicationType()) {
                    $choices = $disadvantage->getBonusesFor();
                    if (!\in_array($indication, $choices, true)) {
                        $this->hasError = true;
                        $this->flashMessage('L\'indication pour le désavantage "%advtg%" n\'est pas correcte, veuillez vérifier.', 'error', ['%advtg%' => $disadvantage->getName()]);
                        break;
                    }
                }
            }

            if ($numberOfDisadvantages + 1 > 4) {
                $this->hasError = true;
                $this->flashMessage('Vous ne pouvez pas avoir plus de 4 désavantages.');
            }
            $numberOfDisadvantages++;
        }

        // Validate advantages groups
        $advantagesByGroup = [];
        foreach ($this->globalList['advantages'] as $advantage) {
            if (!$advantage->getGroup()) {
                continue;
            }
            $advantagesByGroup[$advantage->getGroup()][] = $advantage;
        }
        foreach ($this->globalList['disadvantages'] as $advantage) {
            if (!$advantage->getGroup()) {
                continue;
            }
            $advantagesByGroup[$advantage->getGroup()][] = $advantage;
        }

        foreach ($advantagesByGroup as $groupId => $groupedAdvantages) {
            /** @var Advantage[] $groupedAdvantages */
            $numberForGroup = 0;
            foreach ($groupedAdvantages as $advantage) {
                $id = $advantage->getId();
                if (!empty($advantages[$id]) || !empty($disadvantages[$id])) {
                    if ($numberForGroup > 0) {
                        $this->hasError = true;
                        $this->flashMessage('Vous ne pouvez pas combiner plusieurs avantages ou désavantages de type "%advantage_group%".', 'error', [
                            '%advantage_group%' => $advantage->getGroup(),
                        ]);
                        break;
                    }
                    $numberForGroup++;
                }
            }
        }

        if (false === $this->hasError) {
            $this->advantages = $advantages;
            $this->disadvantages = $disadvantages;

            $this->calculateExperience();
        }

        if (false === $this->hasError && $this->experience >= 0) {
            $this->updateCharacterStep([
                'advantages' => $this->advantages,
                'disadvantages' => $this->disadvantages,
                'advantages_indications' => $this->indications,
                'remainingExp' => $this->experience,
            ]);

            return $this->nextStep();
        }

        return null;
    }
}

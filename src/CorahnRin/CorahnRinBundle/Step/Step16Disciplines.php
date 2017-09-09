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

use CorahnRin\CorahnRinBundle\Entity\Disciplines;
use CorahnRin\CorahnRinBundle\Entity\Domains;
use CorahnRin\CorahnRinBundle\GeneratorTools\DomainsCalculator;

class Step16Disciplines extends AbstractStepAction
{
    /**
     * @var Disciplines[]
     */
    private $availableDisciplines;

    /**
     * @var int[]
     */
    private $disciplinesSpentWithExp;

    /**
     * @var int
     */
    private $expRemainingFromDomains;

    /**
     * @var DomainsCalculator
     */
    private $domainsCalculator;

    /**
     * @var Domains[]
     */
    private $allDomains;

    /**
     * @var int
     */
    private $remainingBonusPoints;

    public function __construct(DomainsCalculator $domainsCalculator)
    {
        $this->domainsCalculator = $domainsCalculator;
    }

    /**
     * {@inheritdoc}
     *
     * @internal int[] $socialClassValues
     */
    public function execute()
    {
        $this->allDomains = $this->em->getRepository('CorahnRinBundle:Domains')->findAllSortedByName();

        $primaryDomains                = $this->getCharacterProperty('13_primary_domains');
        $useDomainBonuses              = $this->getCharacterProperty('14_use_domain_bonuses');
        $this->remainingBonusPoints    = $useDomainBonuses['remaining'];
        $this->expRemainingFromDomains = $this->getCharacterProperty('15_domains_spend_exp')['remainingExp'];

        // Can only have disciplines if have bonuses to spend OR remaining experience.
        $canHaveDisciplines = $this->remainingBonusPoints > 0 || $this->expRemainingFromDomains >= 25;

        $availableDomainsForDisciplines = [];

        if ($canHaveDisciplines) {
            $socialClassValues = $this->getCharacterProperty('05_social_class')['domains'];
            $domainBonuses     = $this->getCharacterProperty('14_use_domain_bonuses');
            $geoEnvironment    = $this->em->find('CorahnRinBundle:GeoEnvironments', $this->getCharacterProperty('04_geo'));

            // Calculate final values from previous steps
            $domainsBaseValues = $this->domainsCalculator->calculateFromGeneratorData(
                $this->allDomains,
                $socialClassValues,
                $primaryDomains['ost'],
                $primaryDomains['scholar'] ?: null,
                $geoEnvironment,
                $primaryDomains['domains'],
                $domainBonuses['domains']
            );

            $finalDomainsValues = $this->domainsCalculator->calculateFinalValues(
                $this->allDomains,
                $domainsBaseValues,
                array_map(function ($e) { return (int) $e; }, $this->getCharacterProperty('15_domains_spend_exp')['domains'])
            );

            // Disciplines can be acquired only for domains with 5 points, and only for primary or secondary domains.
            // Of course, the user can rise up domains after character creation, but hey, this respects the book rules!
            $availableDomainsForDisciplines = array_filter($finalDomainsValues, function ($domainValue, $domainId) use ($primaryDomains) {
                return $domainValue === 5 && ($primaryDomains['domains'][$domainId] === 5 || $primaryDomains['domains'][$domainId] === 3);
            }, ARRAY_FILTER_USE_BOTH);

            // Only keep the ids for search purpose
            $availableDomainsForDisciplines = array_keys($availableDomainsForDisciplines);

            $this->availableDisciplines = $this->em->getRepository('CorahnRinBundle:Disciplines')->findAllByDomains($availableDomainsForDisciplines);
        }

        /** @var int[] $currentDisciplinesSpentWithExp */
        $this->disciplinesSpentWithExp = $this->getCharacterProperty() ?: $this->resetDisciplines();

        // To be used in POST data
        $remainingBonusPoints = $this->remainingBonusPoints;

        // Manage form submit
        if ($this->request->isMethod('POST')) {
            if (!$canHaveDisciplines) {
                $this->updateCharacterStep($this->disciplinesSpentWithExp);

                return $this->nextStep();
            }

            /** @var int[][] $disciplinesValues */
            $disciplinesValues = $this->request->get('disciplines_spend_exp', []);

            if (!is_array($disciplinesValues)) {
                $this->flashMessage('errors.incorrect_values');
            } else {
                $remainingExp = $this->expRemainingFromDomains;

                $errors = false;

                // First, check the ids
                foreach ($disciplinesValues as $domainId => $values) {
                    if (!is_array($values) || !array_key_exists($domainId, $this->allDomains)) {
                        $errors = true;
                        $this->flashMessage('errors.incorrect_values');
                        break;
                    }

                    foreach ($values as $disciplineId => $v) {
                        if (!array_key_exists($disciplineId, $this->availableDisciplines)) {
                            $errors = true;
                            $this->flashMessage('errors.incorrect_values');
                            break;
                        }
                        $disciplinesValues[$domainId][$disciplineId] = true;

                        if ($remainingBonusPoints > 0) {
                            $remainingBonusPoints--;
                        } else {
                            if ($remainingExp - 25 < 0) {
                                $errors = true;
                                $this->flashMessage('errors.incorrect_values');
                                break;
                            }

                            $remainingExp -= 25;
                        }
                    }
                }

                if (true === $errors) {
                    $this->disciplinesSpentWithExp = $this->resetDisciplines();
                } else {
                    $this->updateCharacterStep([
                        'disciplines'          => $disciplinesValues,
                        'remainingExp'         => $remainingExp,
                        'remainingBonusPoints' => $remainingBonusPoints,
                    ]);

                    return $this->nextStep();
                }
            }
        }

        // Get disciplines sorted by domain name
        $disciplinesSortedByDomains = [];
        foreach ($this->availableDisciplines as $discipline) {
            foreach ($discipline->getDomains() as $domain) {
                if (!in_array($domain->getId(), $availableDomainsForDisciplines, true)) {
                    continue;
                }
                $domainId = $domain->getId();
                if (!array_key_exists($domainId, $disciplinesSortedByDomains)) {
                    $disciplinesSortedByDomains[$domainId] = [];
                }
                $disciplinesSortedByDomains[$domainId][$discipline->getId()] = $discipline;
            }
        }

        return $this->renderCurrentStep([
            'all_domains'                => $this->allDomains,
            'available_domains'          => $availableDomainsForDisciplines,
            'all_disciplines'            => $disciplinesSortedByDomains,
            'disciplines_spent_with_exp' => $this->disciplinesSpentWithExp['disciplines'],
            'bonus_max'                  => $this->remainingBonusPoints,
            'bonus_value'                => $this->disciplinesSpentWithExp['remainingBonusPoints'],
            'exp_max'                    => $this->expRemainingFromDomains,
            'exp_value'                  => $this->disciplinesSpentWithExp['remainingExp'],
        ]);
    }

    private function resetDisciplines()
    {
        return $this->disciplinesSpentWithExp = [
            'disciplines'          => [],
            'remainingExp'         => $this->expRemainingFromDomains,
            'remainingBonusPoints' => $this->remainingBonusPoints,
        ];
    }
}

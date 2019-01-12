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

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\GeoEnvironment;
use CorahnRin\GeneratorTools\DomainsCalculator;
use Symfony\Component\HttpFoundation\Response;

class Step14UseDomainBonuses extends AbstractStepAction
{
    /**
     * @var DomainsData[]
     */
    private $allDomains;

    /**
     * Here we'll store all final values for all domains.
     * As some advantages/properties can give more points to domains,
     *  they might be different than the values set at step 13_primary_domains.
     *
     * @var array
     */
    private $domainsCalculatedValues = [];

    /**
     * Bonuses will be calculated based on primary domains,
     *  and all other advantages/properties of the character can
     *  add bonuses depending on their values.
     *
     * @var int
     */
    private $bonus = 0;

    /**
     * @var DomainsCalculator
     */
    private $domainsCalculator;

    public function __construct(DomainsCalculator $domainsCalculator)
    {
        $this->domainsCalculator = $domainsCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $this->allDomains = DomainsData::allAsObjects();

        $step13Domains = $this->getCharacterProperty('13_primary_domains');
        $socialClassValues = $this->getCharacterProperty('05_social_class')['domains'];
        $geoEnvironment = $this->em->find(GeoEnvironment::class, $this->getCharacterProperty('04_geo'));

        $this->domainsCalculatedValues = $this->domainsCalculator->calculateFromGeneratorData(
            $this->allDomains,
            $socialClassValues,
            $step13Domains['ost'],
            $geoEnvironment,
            $step13Domains['domains']
        );

        $this->bonus = $this->domainsCalculator->getBonus();

        /** @var int[][] $characterBonuses */
        $characterBonuses = $this->getCharacterProperty();

        if (null === $characterBonuses) {
            $characterBonuses = $this->resetBonuses();
        }

        // If "mentor ally" is selected, then the character has a bonus to one domain.
        // Thanks to him! :D
        $advantages = $this->getCharacterProperty('11_advantages');
        $mentor = $advantages['advantages'][2] ?? 0;
        $this->bonus += $mentor; // $mentor can be 0 or 1 only so no problem with this operation.

        /** @var int $age */
        $age = $this->getCharacterProperty('06_age');
        if ($age > 20) {
            $this->bonus++;
        }
        if ($age > 25) {
            $this->bonus++;
        }
        if ($age > 30) {
            $this->bonus++;
        }

        $bonusValue = $this->bonus;

        // Manage form submit
        if ($this->request->isMethod('POST')) {
            if (0 === $this->bonus) {
                $finalArray = $this->resetBonuses();
                $finalArray['remaining'] = $this->bonus;
                $this->updateCharacterStep($finalArray);

                return $this->nextStep();
            }

            $postedValues = $this->request->request->get('domains_bonuses');

            $remainingPoints = $bonusValue;
            $spent = 0;

            $error = false;

            foreach (\array_keys($characterBonuses['domains']) as $id) {
                $value = (int) ($postedValues[$id] ?? 0);
                if (
                    !\array_key_exists($id, $postedValues)
                    || ($value && 5 === $this->domainsCalculatedValues[$id])
                    || !\in_array($value, [0, 1], true)
                ) {
                    // If there is any error, we do nothing.
                    $this->flashMessage('errors.incorrect_values');
                    $error = true;
                    break;
                }

                if (1 === $value) {
                    $remainingPoints--;
                    $spent++;
                }

                $characterBonuses['domains'][$id] = $value;
            }

            if ($remainingPoints < 0) {
                $this->flashMessage('domains_bonuses.errors.too_many_points', null, ['%base%' => $this->bonus, '%spent%' => $spent]);
                $error = true;
            }

            if (false === $error) {
                if ($remainingPoints > 2) {
                    $this->flashMessage('domains_bonuses.errors.more_than_two', null, ['%count%' => $remainingPoints]);
                } elseif ($remainingPoints >= 0) {
                    $characterBonuses['remaining'] = $remainingPoints;
                    $this->updateCharacterStep($characterBonuses);

                    return $this->nextStep();
                }
            } else {
                $characterBonuses = $this->resetBonuses();
                $this->updateCharacterStep(null);
                $bonusValue = $this->bonus;
            }
        }

        return $this->renderCurrentStep([
            'all_domains' => $this->allDomains,
            'domains_values' => $this->domainsCalculatedValues,
            'domains_bonuses' => $characterBonuses,
            'bonus_max' => $this->bonus,
            'bonus_value' => $bonusValue,
        ], 'corahn_rin/Steps/14_use_domain_bonuses.html.twig');
    }

    /**
     * @return int[]
     */
    private function resetBonuses()
    {
        $domainsBonuses = [
            'domains' => [],
            'remaining' => 0,
        ];

        foreach ($this->allDomains as $id => $domain) {
            $domainsBonuses['domains'][$id] = 0;
        }

        return $domainsBonuses;
    }
}

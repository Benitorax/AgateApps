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

class Step15DomainsSpendExp extends AbstractStepAction
{
    /**
     * @var \Generator|\CorahnRin\Data\DomainsData[]
     */
    private $allDomains;

    /**
     * @var DomainsCalculator
     */
    private $domainsCalculator;

    /**
     * @var int[]
     */
    private $domainsSpentWithExp;

    /**
     * @var int
     */
    private $expRemainingFromAdvantages;

    public function __construct(DomainsCalculator $domainsCalculator)
    {
        $this->domainsCalculator = $domainsCalculator;
    }

    /**
     * {@inheritdoc}
     *
     * @internal int[] $socialClassValues
     */
    public function execute(): Response
    {
        $this->allDomains = DomainsData::allAsObjects();

        $primaryDomains = $this->getCharacterProperty('13_primary_domains');
        $socialClassValues = $this->getCharacterProperty('05_social_class')['domains'];
        $domainBonuses = $this->getCharacterProperty('14_use_domain_bonuses');
        $geoEnvironment = $this->em->find(GeoEnvironment::class, $this->getCharacterProperty('04_geo'));

        $domainsBaseValues = $this->domainsCalculator->calculateFromGeneratorData(
            $this->allDomains,
            $socialClassValues,
            $primaryDomains['ost'],
            $geoEnvironment,
            $primaryDomains['domains'],
            $domainBonuses['domains']
        );

        $this->expRemainingFromAdvantages = $this->getCharacterProperty('11_advantages')['remainingExp'];

        /* @var int[] $currentDomainsSpentWithExp */
        $this->domainsSpentWithExp = $this->getCharacterProperty();

        if (null === $this->domainsSpentWithExp) {
            $this->resetDomains();
        }

        // Manage form submit
        if ($this->request->isMethod('POST')) {
            /** @var int[] $domainsValues */
            $domainsValues = $this->request->get('domains_spend_exp');

            if (!\is_array($domainsValues)) {
                $this->flashMessage('errors.incorrect_values');
            } else {
                $remainingExp = $this->expRemainingFromAdvantages;

                $errors = false;

                // First, check the ids
                foreach ($domainsValues as $id => $value) {
                    if (
                        !\array_key_exists($id, $this->allDomains)
                        || !\is_numeric($value)
                        || $value < 0
                        || ($remainingExp - ($value * 10)) < 0
                        || $value + $domainsBaseValues[$id] > 5
                    ) {
                        $errors = true;
                        $this->flashMessage('errors.incorrect_values');
                        break;
                    }

                    $domainsValues[$id] = '0' === $value ? null : (int) $value;

                    $remainingExp -= ($value * 10);
                }

                if (false === $errors) {
                    $this->domainsSpentWithExp = [
                        'domains' => $domainsValues,
                        'remainingExp' => $remainingExp,
                    ];

                    $this->updateCharacterStep($this->domainsSpentWithExp);

                    return $this->nextStep();
                }
            }
        }

        return $this->renderCurrentStep([
            'all_domains' => $this->allDomains,
            'domains_base_values' => $domainsBaseValues,
            'domains_spent_with_exp' => $this->domainsSpentWithExp['domains'],
            'exp_max' => $this->expRemainingFromAdvantages,
            'exp_value' => $this->domainsSpentWithExp['remainingExp'],
        ], 'corahn_rin/Steps/15_domains_spend_exp.html.twig');
    }

    private function resetDomains()
    {
        $this->domainsSpentWithExp = [
            'domains' => [],
            'remainingExp' => $this->expRemainingFromAdvantages,
        ];

        foreach ($this->allDomains as $id => $value) {
            $this->domainsSpentWithExp['domains'][$id] = null;
        }
    }
}

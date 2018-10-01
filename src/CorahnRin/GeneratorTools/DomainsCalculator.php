<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainItem;
use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\GeoEnvironments;

final class DomainsCalculator
{
    /**
     * @var int[]
     */
    private $finalCalculatedDomains = [];

    /**
     * @var int
     */
    private $bonus = 0;

    /**
     * @return int
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * If $domainsBonuses IS NOT provided, then it will return calculated domains values with an array of this type:
     * [ 'bonus'=>0, 'domains'=>[...] ]
     * Mostly used for step 14.
     *
     * If $domainsBonuses IS provided, then it will add the correct bonuses if some domains exceed 5 points.
     *
     * @param DomainItem[] $allDomains
     * @param array        $domainsBonuses
     *
     * @return int[]
     */
    public function calculateFromGeneratorData(
        $allDomains,
        array $socialClasses,
        string $ost,
        GeoEnvironments $geoEnv,
        array $primaryDomains,
        array $domainsBonuses = null
    ): array {
        $this->bonus = 0;
        $this->finalCalculatedDomains = [];

        /*
         * Step 13 primary domains and step 14 bonuses
         */
        foreach ($allDomains as $id => $domain) {
            // First, validate arguments.
            if (!($domain instanceof DomainItem)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Invalid %s argument sent. It must be an array of %s instances, %s given.',
                    '$allDomains', DomainItem::class, \is_object($domain) ? \get_class($domain) : \gettype($domain)
                ));
            }

            if (!isset($primaryDomains[$id])) {
                throw new \InvalidArgumentException(\sprintf(
                    'Invalid %s argument sent. It must be an array of %s, and the array key must correspond to the "%s" property.',
                    '$primaryDomains', 'integers', 'domain id'
                ));
            }

            /*
             * Step 13 primary domains
             */
            $this->finalCalculatedDomains[$id] = $primaryDomains[$id];

            /*
             * Step 14 domain bonuses (if set)
             */
            if (null !== $domainsBonuses) {
                if (!\array_key_exists($id, $domainsBonuses)) {
                    throw new \InvalidArgumentException(\sprintf(
                        'Invalid %s argument sent. It must be an array of %s, and the array key must correspond to the "%s" property.',
                        '$domainsBonuses', 'integers', 'domain id'
                    ));
                }
                $this->addValueToDomain($id, $domainsBonuses[$id]);
            }
        }

        /*
         * Ost service
         */
        if (!\array_key_exists($ost, $allDomains)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid %s argument sent. It must be a valid %s.',
                '$ost', 'domain id'
            ));
        }
        $this->addValueToDomain($ost);

        /*
         * Geo environment
         */
        $this->addValueToDomain($geoEnv->getDomain());

        /*
         * Social classes
         */
        foreach ($socialClasses as $id) {
            if (!\array_key_exists($id, $allDomains)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Invalid %s argument sent. It must be an array of %s, and the array values must correspond to the "%s" property.',
                    '$socialClasses', 'integers', 'domain id'
                ));
            }

            $this->addValueToDomain($id);
        }

        if (null !== $domainsBonuses) {
            foreach ($this->finalCalculatedDomains as $id => $value) {
                if ($value > 5) {
                    $this->finalCalculatedDomains[$id] = 5;
                }
            }
        }

        return $this->finalCalculatedDomains;
    }

    /**
     * Based on all three specified steps, will calculate final domains values.
     * Mostly used in step 16 to calculate disciplines and in step 17 to check if combat arts are available.
     *
     * @param DomainsData[] $allDomains
     * @param int[]         $domainsBaseValues
     * @param int[]         $domainsSpendExp
     *
     * @return int[]
     */
    public function calculateFinalValues(array $allDomains, array $domainsBaseValues, array $domainsSpendExp)
    {
        $finalValues = [];

        foreach ($allDomains as $id => $domain) {
            // First, validate arguments.
            if (!($domain instanceof DomainItem)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Invalid %s argument sent. It must be an array of %s instances, %s given.',
                    '$allDomains', DomainItem::class, \is_object($domain) ? \get_class($domain) : \gettype($domain)
                ));
            }

            if (!isset($domainsBaseValues[$id], $domainsSpendExp[$id])) {
                throw new \InvalidArgumentException('Invalid arguments sent for step domains. It must be an array of integers, and the array key must correspond to the domain id.');
            }

            $finalValues[$id] = $domainsBaseValues[$id] + $domainsSpendExp[$id];
        }

        return $finalValues;
    }

    private function addValueToDomain(string $domainId, int $value = 1): void
    {
        if (1 === $value) {
            if ($this->finalCalculatedDomains[$domainId] < 5) {
                $this->finalCalculatedDomains[$domainId]++;
            } else {
                $this->bonus++;
            }
        } else {
            for ($i = 1; $i <= $value; $i++) {
                $this->addValueToDomain($domainId);
            }
        }
    }
}

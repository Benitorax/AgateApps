<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity\CharacterProperties;

use CorahnRin\Data\Character\DomainScore;
use CorahnRin\Data\DomainsData as DomainsData;
use CorahnRin\Entity\Character;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domains.
 *
 * @ORM\Embeddable
 */
class CharacterDomains
{
    /**
     * @ORM\Column(name="craft", type="smallint")
     */
    private $craft;

    /**
     * @ORM\Column(name="craft_bonus", type="smallint")
     */
    private $craftBonus;

    /**
     * @ORM\Column(name="craft_malus", type="smallint")
     */
    private $craftMalus;

    /**
     * @ORM\Column(name="close_combat", type="smallint")
     */
    private $closeCombat;

    /**
     * @ORM\Column(name="close_combat_bonus", type="smallint")
     */
    private $closeCombatBonus;

    /**
     * @ORM\Column(name="close_combat_malus", type="smallint")
     */
    private $closeCombatMalus;

    /**
     * @ORM\Column(name="stealth", type="smallint")
     */
    private $stealth;

    /**
     * @ORM\Column(name="stealth_bonus", type="smallint")
     */
    private $stealthBonus;

    /**
     * @ORM\Column(name="stealth_malus", type="smallint")
     */
    private $stealthMalus;

    /**
     * @ORM\Column(name="magience", type="smallint")
     */
    private $magience;

    /**
     * @ORM\Column(name="magience_bonus", type="smallint")
     */
    private $magienceBonus;

    /**
     * @ORM\Column(name="magience_malus", type="smallint")
     */
    private $magienceMalus;

    /**
     * @ORM\Column(name="natural_environment", type="smallint")
     */
    private $naturalEnvironment;

    /**
     * @ORM\Column(name="natural_environment_bonus", type="smallint")
     */
    private $naturalEnvironmentBonus;

    /**
     * @ORM\Column(name="natural_environment_malus", type="smallint")
     */
    private $naturalEnvironmentMalus;

    /**
     * @ORM\Column(name="demorthen_mysteries", type="smallint")
     */
    private $demorthenMysteries;

    /**
     * @ORM\Column(name="demorthen_mysteries_bonus", type="smallint")
     */
    private $demorthenMysteriesBonus;

    /**
     * @ORM\Column(name="demorthen_mysteries_malus", type="smallint")
     */
    private $demorthenMysteriesMalus;

    /**
     * @ORM\Column(name="occultism", type="smallint")
     */
    private $occultism;

    /**
     * @ORM\Column(name="occultism_bonus", type="smallint")
     */
    private $occultismBonus;

    /**
     * @ORM\Column(name="occultism_malus", type="smallint")
     */
    private $occultismMalus;

    /**
     * @ORM\Column(name="perception", type="smallint")
     */
    private $perception;

    /**
     * @ORM\Column(name="perception_bonus", type="smallint")
     */
    private $perceptionBonus;

    /**
     * @ORM\Column(name="perception_malus", type="smallint")
     */
    private $perceptionMalus;

    /**
     * @ORM\Column(name="prayer", type="smallint")
     */
    private $prayer;

    /**
     * @ORM\Column(name="prayer_bonus", type="smallint")
     */
    private $prayerBonus;

    /**
     * @ORM\Column(name="prayer_malus", type="smallint")
     */
    private $prayerMalus;

    /**
     * @ORM\Column(name="feats", type="smallint")
     */
    private $feats;

    /**
     * @ORM\Column(name="feats_bonus", type="smallint")
     */
    private $featsBonus;

    /**
     * @ORM\Column(name="feats_malus", type="smallint")
     */
    private $featsMalus;

    /**
     * @ORM\Column(name="relation", type="smallint")
     */
    private $relation;

    /**
     * @ORM\Column(name="relation_bonus", type="smallint")
     */
    private $relationBonus;

    /**
     * @ORM\Column(name="relation_malus", type="smallint")
     */
    private $relationMalus;

    /**
     * @ORM\Column(name="performance", type="smallint")
     */
    private $performance;

    /**
     * @ORM\Column(name="performance_bonus", type="smallint")
     */
    private $performanceBonus;

    /**
     * @ORM\Column(name="performance_malus", type="smallint")
     */
    private $performanceMalus;

    /**
     * @ORM\Column(name="science", type="smallint")
     */
    private $science;

    /**
     * @ORM\Column(name="science_bonus", type="smallint")
     */
    private $scienceBonus;

    /**
     * @ORM\Column(name="science_malus", type="smallint")
     */
    private $scienceMalus;

    /**
     * @ORM\Column(name="shooting_and_throwing", type="smallint")
     */
    private $shootingAndThrowing;

    /**
     * @ORM\Column(name="shooting_and_throwing_bonus", type="smallint")
     */
    private $shootingAndThrowingBonus;

    /**
     * @ORM\Column(name="shooting_and_throwing_malus", type="smallint")
     */
    private $shootingAndThrowingMalus;

    /**
     * @ORM\Column(name="travel", type="smallint")
     */
    private $travel;

    /**
     * @ORM\Column(name="travel_bonus", type="smallint")
     */
    private $travelBonus;

    /**
     * @ORM\Column(name="travel_malus", type="smallint")
     */
    private $travelMalus;

    /**
     * @ORM\Column(name="erudition", type="smallint")
     */
    private $erudition;

    /**
     * @ORM\Column(name="erudition_bonus", type="smallint")
     */
    private $eruditionBonus;

    /**
     * @ORM\Column(name="erudition_malus", type="smallint")
     */
    private $eruditionMalus;

    public function getDomainValue(string $domain, string $suffix = ''): int
    {
        $propertyName = DomainsData::getCamelizedTitle($domain, $suffix);

        return $this->$propertyName;
    }

    public function setDomainValue(string $domain, int $value): void
    {
        $this->setDomainPropertyValue($domain, '', $value);
    }

    public function setDomainBonusValue(string $domain, int $value): void
    {
        $this->setDomainPropertyValue($domain, 'Bonus', $value);
    }

    public function setDomainMalusValue(string $domain, int $value): void
    {
        $this->setDomainPropertyValue($domain, 'Malus', $value);
    }

    /**
     * Keys are domain names, values are domain scores.
     *
     * @return DomainScore[]
     */
    public function toArray(Character $character): array
    {
        $data = [];

        foreach (DomainsData::allAsObjects() as $domain) {
            $propertyName = $domain->getCamelizedTitle();
            $data[$domain->getTitle()] = new DomainScore(
                $character->getWay($domain->getWay()),
                $this->{$propertyName},
                $this->{$propertyName.'Bonus'},
                $this->{$propertyName.'Malus'}
            );
        }

        return $data;
    }

    private function setDomainPropertyValue(string $domain, string $suffix, int $value): void
    {
        DomainsData::validateDomainBaseValue($domain, $value);

        $propertyName = DomainsData::getCamelizedTitle($domain, $suffix);

        $this->$propertyName = $value;
    }

    /*
     * Getters...
     */

    public function getCraft()
    {
        return $this->craft;
    }

    public function getCraftBonus()
    {
        return $this->craftBonus;
    }

    public function getCraftMalus()
    {
        return $this->craftMalus;
    }

    public function getCloseCombat()
    {
        return $this->closeCombat;
    }

    public function getCloseCombatBonus()
    {
        return $this->closeCombatBonus;
    }

    public function getCloseCombatMalus()
    {
        return $this->closeCombatMalus;
    }

    public function getStealth()
    {
        return $this->stealth;
    }

    public function getStealthBonus()
    {
        return $this->stealthBonus;
    }

    public function getStealthMalus()
    {
        return $this->stealthMalus;
    }

    public function getMagience()
    {
        return $this->magience;
    }

    public function getMagienceBonus()
    {
        return $this->magienceBonus;
    }

    public function getMagienceMalus()
    {
        return $this->magienceMalus;
    }

    public function getNaturalEnvironment()
    {
        return $this->naturalEnvironment;
    }

    public function getNaturalEnvironmentBonus()
    {
        return $this->naturalEnvironmentBonus;
    }

    public function getNaturalEnvironmentMalus()
    {
        return $this->naturalEnvironmentMalus;
    }

    public function getDemorthenMysteries()
    {
        return $this->demorthenMysteries;
    }

    public function getDemorthenMysteriesBonus()
    {
        return $this->demorthenMysteriesBonus;
    }

    public function getDemorthenMysteriesMalus()
    {
        return $this->demorthenMysteriesMalus;
    }

    public function getOccultism()
    {
        return $this->occultism;
    }

    public function getOccultismBonus()
    {
        return $this->occultismBonus;
    }

    public function getOccultismMalus()
    {
        return $this->occultismMalus;
    }

    public function getPerception()
    {
        return $this->perception;
    }

    public function getPerceptionBonus()
    {
        return $this->perceptionBonus;
    }

    public function getPerceptionMalus()
    {
        return $this->perceptionMalus;
    }

    public function getPrayer()
    {
        return $this->prayer;
    }

    public function getPrayerBonus()
    {
        return $this->prayerBonus;
    }

    public function getPrayerMalus()
    {
        return $this->prayerMalus;
    }

    public function getFeats()
    {
        return $this->feats;
    }

    public function getFeatsBonus()
    {
        return $this->featsBonus;
    }

    public function getFeatsMalus()
    {
        return $this->featsMalus;
    }

    public function getRelation()
    {
        return $this->relation;
    }

    public function getRelationBonus()
    {
        return $this->relationBonus;
    }

    public function getRelationMalus()
    {
        return $this->relationMalus;
    }

    public function getPerformance()
    {
        return $this->performance;
    }

    public function getPerformanceBonus()
    {
        return $this->performanceBonus;
    }

    public function getPerformanceMalus()
    {
        return $this->performanceMalus;
    }

    public function getScience()
    {
        return $this->science;
    }

    public function getScienceBonus()
    {
        return $this->scienceBonus;
    }

    public function getScienceMalus()
    {
        return $this->scienceMalus;
    }

    public function getShootingAndThrowing()
    {
        return $this->shootingAndThrowing;
    }

    public function getShootingAndThrowingBonus()
    {
        return $this->shootingAndThrowingBonus;
    }

    public function getShootingAndThrowingMalus()
    {
        return $this->shootingAndThrowingMalus;
    }

    public function getTravel()
    {
        return $this->travel;
    }

    public function getTravelBonus()
    {
        return $this->travelBonus;
    }

    public function getTravelMalus()
    {
        return $this->travelMalus;
    }

    public function getErudition()
    {
        return $this->erudition;
    }

    public function getEruditionBonus()
    {
        return $this->eruditionBonus;
    }

    public function getEruditionMalus()
    {
        return $this->eruditionMalus;
    }
}

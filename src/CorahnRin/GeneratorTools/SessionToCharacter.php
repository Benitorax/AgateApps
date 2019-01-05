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

use Behat\Transliterator\Transliterator;
use CorahnRin\Data\DomainItem;
use CorahnRin\Data\DomainsData;
use CorahnRin\Data\Ways as WaysData;
use CorahnRin\Entity\Armors;
use CorahnRin\Entity\Avantages;
use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Entity\CharacterProperties\CharacterDomains;
use CorahnRin\Entity\CharacterProperties\CharAdvantages;
use CorahnRin\Entity\CharacterProperties\CharDisciplines;
use CorahnRin\Entity\CharacterProperties\CharSetbacks;
use CorahnRin\Entity\CharacterProperties\HealthCondition;
use CorahnRin\Entity\CharacterProperties\Money;
use CorahnRin\Entity\CharacterProperties\Ways;
use CorahnRin\Entity\Characters;
use CorahnRin\Entity\CombatArt;
use CorahnRin\Entity\Discipline;
use CorahnRin\Entity\MentalDisorder;
use CorahnRin\Entity\GeoEnvironment;
use CorahnRin\Entity\Job;
use CorahnRin\Entity\People;
use CorahnRin\Entity\PersonalityTrait;
use CorahnRin\Entity\Setbacks;
use CorahnRin\Entity\SocialClass;
use CorahnRin\Entity\Weapon;
use CorahnRin\Exception\CharactersException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use EsterenMaps\Entity\Zone;
use Pierstoval\Bundle\CharacterManagerBundle\Resolver\StepResolverInterface;

final class SessionToCharacter
{
    private $resolver;
    private $em;
    private $domainsCalculator;
    private $corahnRinManagerName;

    /**
     * @var DomainItem[]
     */
    private $domains;

    /**
     * @var Setbacks[]
     */
    private $setbacks;

    /**
     * @var Avantages[]
     */
    private $advantages;

    /**
     * @var EntityRepository[]
     */
    private $repositories;

    public function __construct(
        StepResolverInterface $resolver,
        DomainsCalculator $domainsCalculator,
        ObjectManager $em,
        string $corahnRinManagerName
    ) {
        $this->resolver = $resolver;
        $this->em = $em;
        $this->domainsCalculator = $domainsCalculator;
        $this->corahnRinManagerName = $corahnRinManagerName;
    }

    /**
     * @throws CharactersException
     */
    public function createCharacterFromGeneratorValues(array $values): Characters
    {
        $steps = $this->resolver->getManagerSteps($this->corahnRinManagerName);

        $this->prepareNecessaryVariables();

        $generatorKeys = \array_keys($values);
        $stepsKeys = \array_keys($steps);

        \sort($generatorKeys);
        \sort($stepsKeys);

        if ($generatorKeys !== $stepsKeys) {
            throw new CharactersException('Generator seems not to be fully finished');
        }

        $character = new Characters();

        $character->setCreated(new \DateTime());
        $this->setPeople($character, $values);
        $this->setJob($character, $values);
        $this->setBirthPlace($character, $values);
        $this->setGeoLiving($character, $values);
        $this->setSocialClass($character, $values);
        $this->setAge($character, $values);
        $this->setSetbacks($character, $values);
        $this->setWays($character, $values);
        $this->setTraits($character, $values);
        $this->setOrientation($character, $values);
        $this->setAdvantages($character, $values);
        $this->setMentalDisorder($character, $values);
        $this->setDisciplines($character, $values);
        $this->setCombatArts($character, $values);
        $this->setEquipment($character, $values);
        $this->setDescription($character, $values);
        $this->setExp($character, $values);
        $this->setMoney($character);
        $this->setDomains($character, $values);
        $this->setHealthCondition($character);
        $this->setPrecalculatedValues($character);

        return $character;
    }

    /**
     * @param string $class
     *
     * @return EntityRepository|ObjectRepository
     */
    private function getRepository($class)
    {
        if (isset($this->repositories[$class])) {
            return $this->repositories[$class];
        }

        return $this->repositories[$class] = $this->em->getRepository($class);
    }

    /**
     * Add some properties that will be used in other steps validators.
     * As a reminder, base repository is Orbitale's one, so using "_primary" will automatically index all objects by their id.
     */
    private function prepareNecessaryVariables(): void
    {
        $this->setbacks = $this->getRepository(Setbacks::class)->findAll('_primary');
        $this->domains = DomainsData::allAsObjects();
        $this->advantages = $this->getRepository(Avantages::class)->findAll('_primary');
    }

    private function setPeople(Characters $character, array $values): void
    {
        $character->setPeople($this->getRepository(People::class)->find($values['01_people']));
    }

    private function setJob(Characters $character, array $values): void
    {
        $character->setJob($this->getRepository(Job::class)->find($values['02_job']));
    }

    private function setBirthPlace(Characters $character, array $values): void
    {
        $character->setBirthPlace($this->getRepository(Zone::class)->find($values['03_birthplace']));
    }

    private function setGeoLiving(Characters $character, array $values): void
    {
        $character->setGeoLiving($this->getRepository(GeoEnvironment::class)->find($values['04_geo']));
    }

    private function setSocialClass(Characters $character, array $values): void
    {
        $character->setSocialClass($this->getRepository(SocialClass::class)->find($values['05_social_class']['id']));

        $domains = $values['05_social_class']['domains'];
        $character->setSocialClassDomain1($domains[0]);
        $character->setSocialClassDomain2($domains[1]);
    }

    private function setAge(Characters $character, array $values): void
    {
        $character->setAge($values['06_age']);
    }

    private function setSetbacks(Characters $character, array $values): void
    {
        foreach ($values['07_setbacks'] as $id => $details) {
            $charSetback = new CharSetbacks();
            $charSetback->setCharacter($character);
            $charSetback->setSetback($this->setbacks[$id]);
            $charSetback->setAvoided($details['avoided']);
            $character->addSetback($charSetback);
        }
    }

    private function setWays(Characters $character, array $values): void
    {
        $character->setWay(new Ways(
            $values['08_ways'][WaysData::COMBATIVENESS],
            $values['08_ways'][WaysData::CREATIVITY],
            $values['08_ways'][WaysData::EMPATHY],
            $values['08_ways'][WaysData::REASON],
            $values['08_ways'][WaysData::CONVICTION]
        ));
    }

    private function setTraits(Characters $character, array $values): void
    {
        $character->setQuality($this->getRepository(PersonalityTrait::class)->find($values['09_traits']['quality']));
        $character->setFlaw($this->getRepository(PersonalityTrait::class)->find($values['09_traits']['flaw']));
    }

    private function setOrientation(Characters $character, array $values): void
    {
        $character->setOrientation($values['10_orientation']);
    }

    private function setAdvantages(Characters $character, array $values): void
    {
        foreach ($values['11_advantages']['advantages'] as $id => $value) {
            if (!$value) {
                continue;
            }
            $charAdvantage = CharAdvantages::create(
                $character,
                $this->advantages[$id],
                $value,
                $values['11_advantages']['advantages_indications'][$id] ?? ''
            );
            $character->addCharAdvantage($charAdvantage);
        }

        foreach ($values['11_advantages']['disadvantages'] as $id => $value) {
            if (!$value) {
                continue;
            }
            $charAdvantage = CharAdvantages::create(
                $character,
                $this->advantages[$id],
                $value,
                $values['11_advantages']['advantages_indications'][$id] ?? ''
            );
            $character->addCharAdvantage($charAdvantage);
        }
    }

    private function setMentalDisorder(Characters $character, array $values): void
    {
        $character->setMentalDisorder($this->getRepository(MentalDisorder::class)->find($values['12_mental_disorder']));
    }

    private function setDisciplines(Characters $character, array $values): void
    {
        foreach ($values['16_disciplines']['disciplines'] as $domainId => $disciplines) {
            foreach ($disciplines as $id => $v) {
                $character->addDiscipline(new CharDisciplines(
                    $character,
                    $this->getRepository(Discipline::class)->find($id),
                    $domainId,
                    6
                ));
            }
        }
    }

    private function setCombatArts(Characters $character, array $values): void
    {
        foreach ($values['17_combat_arts']['combatArts'] as $id => $v) {
            $character->addCombatArt($this->getRepository(CombatArt::class)->find($id));
        }
    }

    private function setEquipment(Characters $character, array $values): void
    {
        $character->setInventory($values['18_equipment']['equipment']);

        foreach ($values['18_equipment']['armors'] as $id => $value) {
            $character->addArmor($this->getRepository(Armors::class)->find($id));
        }
        foreach ($values['18_equipment']['weapons'] as $id => $value) {
            $character->addWeapon($this->getRepository(Weapon::class)->find($id));
        }
    }

    private function setDescription(Characters $character, array $values): void
    {
        $details = $values['19_description'];
        $character->setName($details['name']);
        $character->setPlayerName($details['player_name']);
        $character->setSex($details['sex']);
        $character->setDescription($details['description']);
        $character->setStory($details['story']);
        $character->setFacts($details['facts']);

        // Make sure slug is unique by just adding a number to it
        $charRepo = $this->getRepository(Characters::class);

        $i = 0;
        do {
            $slug = Transliterator::transliterate($details['name']).($i ? '_'.$i : '');
            $i++;
        } while ($charRepo->findOneBy(['nameSlug' => $slug]));
        $character->setNameSlug($slug);
    }

    private function setExp(Characters $character, array $values): void
    {
        $character->setExperienceActual((int) $values['17_combat_arts']['remainingExp']);
        $character->setExperienceSpent(0);
    }

    private function setMoney(Characters $character): void
    {
        $money = new Money();

        $salary = $character->getJob()->getDailySalary();
        if ($salary > 0) {
            if (!$character->hasSetback(9)) {
                // Use salary only if job defines one AND character is not poor
                $money->addEmber(30 * $salary);
                $money->reallocate();
            }
        } else {
            // If salary is not set in job, character has 2d10 azure daols
            $azure = \random_int(1, 10) + \random_int(1, 10);
            if ($character->hasSetback(9)) {
                // If character is poor, he has half money
                $azure = (int) \floor($azure / 2);
            }
            $money->addAzure($azure);
            $money->reallocate();
        }

        $character->setMoney($money);
    }

    private function setDomains(Characters $character, array $values): void
    {
        $domainsBaseValues = $this->domainsCalculator->calculateFromGeneratorData(
            $this->domains,
            $values['05_social_class']['domains'],
            $values['13_primary_domains']['ost'],
            $character->getGeoLiving(),
            $values['13_primary_domains']['domains'],
            $values['14_use_domain_bonuses']['domains']
        );

        $finalDomainsValues = $this->domainsCalculator->calculateFinalValues(
            $this->domains,
            $domainsBaseValues,
            \array_map(function ($e) { return (int) $e; }, $values['15_domains_spend_exp']['domains'])
        );

        $bonuses = \array_fill_keys(\array_keys($this->domains), 0);
        $maluses = \array_fill_keys(\array_keys($this->domains), 0);

        $charDomain = new CharacterDomains();
        foreach ($this->domains as $domain) {
            $domainName = $domain->getTitle();
            $charDomain->setDomainValue($domainName, $finalDomainsValues[$domainName]);
            $charDomain->setDomainBonusValue($domainName, $bonuses[$domainName]);
            $charDomain->setDomainMalusValue($domainName, $maluses[$domainName]);
        }

        $character->setDomains($charDomain);
    }

    private function setHealthCondition(Characters $character): void
    {
        $health = new HealthCondition();
        $good = $health->getGood();
        $okay = $health->getOkay();
        $bad = $health->getBad();
        $critical = $health->getCritical();

        foreach ($character->getCharAdvantages() as $charAdvantage) {
            $adv = $charAdvantage->getAdvantage();

            foreach ($adv->getBonusesFor() as $bonus) {
                if (isset($this->domains[$bonus])) {
                    continue;
                }

                $disadvantageRatio = $adv->isDesv() ? -1 : 1;
                switch ($bonus) {
                    case Bonuses::MENTAL_RESISTANCE:
                        $character->setMentalResistanceBonus($character->getMentalResistanceBonus() + ($charAdvantage->getScore() * $disadvantageRatio));
                        break;
                    case Bonuses::HEALTH:
                        $score = $charAdvantage->getScore();
                        if ($score >= 1) {
                            $bad++;
                            $critical++;
                        }
                        if ($score >= 2) {
                            $critical++;
                        }
                        if ($score <= -1) {
                            $okay--;
                            $critical--;
                        }
                        if ($score <= -2) {
                            $critical--;
                        }
                        break;
                    case Bonuses::STAMINA:
                        $character->setStamina($character->getStamina() + ($charAdvantage->getScore() * $disadvantageRatio));
                        break;
                    case Bonuses::TRAUMA:
                        $character->setPermanentTrauma($character->getPermanentTrauma() + $charAdvantage->getScore());
                        break;
                    case Bonuses::DEFENSE:
                        $character->setDefenseBonus($character->getDefenseBonus() + ($charAdvantage->getScore() * $disadvantageRatio));
                        break;
                    case Bonuses::SPEED:
                        $character->setSpeedBonus($character->getSpeedBonus() + ($charAdvantage->getScore() * $disadvantageRatio));
                        break;
                    case Bonuses::SURVIVAL:
                        $character->setSurvival($character->getSurvival() + ($charAdvantage->getScore() * $disadvantageRatio));
                        break;
                    case Bonuses::MONEY_100G:
                        $character->getMoney()->addFrost(100);
                        break;
                    case Bonuses::MONEY_20G:
                        $character->getMoney()->addFrost(20);
                        break;
                    case Bonuses::MONEY_50G:
                        $character->getMoney()->addFrost(50);
                        break;
                    case Bonuses::MONEY_50A:
                        $character->getMoney()->addAzure(50);
                        break;
                    case Bonuses::MONEY_20A:
                        $character->getMoney()->addAzure(20);
                        break;
                    default:
                        throw new \RuntimeException("Invalid bonus $bonus");
                }
            }
        }

        $health = new HealthCondition($good, $okay, $bad, $critical);
        $character->setHealth($health);
        $character->setMaxHealth(clone $health);
    }

    private function setPrecalculatedValues(Characters $character): void
    {
        // Rindath
        $rindathMax =
            $character->getCombativeness()
            + $character->getCreativity()
            + $character->getEmpathy()
        ;
        if ($sigilRann = $character->getDiscipline('Sigil Rann')) {
            $rindathMax += (($sigilRann->getScore() - 5) * 5);
        }
        $character->setRindathMax($rindathMax);
        $character->setRindath($rindathMax);

        // Exaltation
        $exaltationMax = $character->getConviction() * 3;
        if ($miracles = $character->getDiscipline('Miracles')) {
            $exaltationMax += (($miracles->getScore() - 5) * 5);
        }
        $character->setExaltationMax($exaltationMax);
        $character->setExaltation($exaltationMax);
    }
}

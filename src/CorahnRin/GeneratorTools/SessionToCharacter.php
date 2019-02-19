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

namespace CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainItem;
use CorahnRin\Data\DomainsData;
use CorahnRin\Data\Ways as WaysData;
use CorahnRin\DTO\CharacterFromSessionDTO;
use CorahnRin\DTO\SessionAdvantageDTO;
use CorahnRin\DTO\SessionSetbackDTO;
use CorahnRin\Entity\Advantage;
use CorahnRin\Entity\Armor;
use CorahnRin\Entity\Character;
use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Entity\CharacterProperties\CharacterDomains;
use CorahnRin\Entity\CharacterProperties\HealthCondition;
use CorahnRin\Entity\CharacterProperties\Money;
use CorahnRin\Entity\CharacterProperties\Ways;
use CorahnRin\Entity\CombatArt;
use CorahnRin\Entity\Discipline;
use CorahnRin\Entity\GeoEnvironment;
use CorahnRin\Entity\Job;
use CorahnRin\Entity\MentalDisorder;
use CorahnRin\Entity\People;
use CorahnRin\Entity\PersonalityTrait;
use CorahnRin\Entity\Setback;
use CorahnRin\Entity\SocialClass;
use CorahnRin\Entity\Weapon;
use CorahnRin\Exception\CharacterException;
use CorahnRin\Exception\InvalidSessionToCharacterValue;
use CorahnRin\Repository\CharacterAdvantageRepository;
use CorahnRin\Repository\SetbacksRepository;
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
     * @var Setback[]
     */
    private $setbacks;

    /**
     * @var Advantage[]
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
     * @throws CharacterException
     */
    public function createCharacterFromGeneratorValues(array $values): Character
    {
        $steps = $this->resolver->getManagerSteps($this->corahnRinManagerName);

        $this->prepareNecessaryVariables();

        $generatorKeys = \array_keys($values);
        $stepsKeys = \array_keys($steps);

        \sort($generatorKeys);
        \sort($stepsKeys);

        if ($generatorKeys !== $stepsKeys) {
            throw new CharacterException('Generator seems not to be fully finished');
        }

        $characterDTO = new CharacterFromSessionDTO();

        $characterDTO->setName($values['19_description']['name']);
        $this->setPeople($characterDTO, $values);
        $this->setJob($characterDTO, $values);
        $this->setBirthPlace($characterDTO, $values);
        $this->setGeoLiving($characterDTO, $values);
        $this->setSocialClass($characterDTO, $values);
        $this->setAge($characterDTO, $values);
        $this->setSetbacks($characterDTO, $values);
        $this->setWays($characterDTO, $values);
        $this->setTraits($characterDTO, $values);
        $this->setOrientation($characterDTO, $values);
        $this->setAdvantages($characterDTO, $values);
        $this->setMentalDisorder($characterDTO, $values);
        $this->setDisciplines($characterDTO, $values);
        $this->setCombatArts($characterDTO, $values);
        $this->setEquipment($characterDTO, $values);
        $this->setDescription($characterDTO, $values);
        $this->setExp($characterDTO, $values);
        $this->setMoney($characterDTO);
        $this->setDomains($characterDTO, $values);
        $this->setHealthCondition($characterDTO);
        $this->setPrecalculatedValues($characterDTO);

        return Character::createFromSession($characterDTO);
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
        /** @var SetbacksRepository $setbacksRepo */
        $setbacksRepo = $this->getRepository(Setback::class);
        $this->setbacks = $setbacksRepo->findAll('id');

        /** @var CharacterAdvantageRepository $advantagesRepo */
        $advantagesRepo = $this->getRepository(Advantage::class);
        $this->advantages = $advantagesRepo->findAll('id');

        $this->domains = DomainsData::allAsObjects();
    }

    private function setPeople(CharacterFromSessionDTO $character, array $values): void
    {
        $people = $this->getRepository(People::class)->find($values['01_people']);

        if (!$people instanceof People) {
            throw new InvalidSessionToCharacterValue('people', $people, People::class);
        }

        $character->setPeople($people);
    }

    private function setJob(CharacterFromSessionDTO $character, array $values): void
    {
        $job = $this->getRepository(Job::class)->find($values['02_job']);

        if (!$job instanceof Job) {
            throw new InvalidSessionToCharacterValue('job', $job, Job::class);
        }

        $character->setJob($job);
    }

    private function setBirthPlace(CharacterFromSessionDTO $character, array $values): void
    {
        $zone = $this->getRepository(Zone::class)->find($values['03_birthplace']);

        if (!$zone instanceof Zone) {
            throw new InvalidSessionToCharacterValue('birthplace', $zone, Zone::class);
        }

        $character->setBirthPlace($zone);
    }

    private function setGeoLiving(CharacterFromSessionDTO $character, array $values): void
    {
        $geoEnv = $this->getRepository(GeoEnvironment::class)->find($values['04_geo']);

        if (!$geoEnv instanceof GeoEnvironment) {
            throw new InvalidSessionToCharacterValue('geo_environment', $geoEnv, GeoEnvironment::class);
        }

        $character->setGeoLiving($geoEnv);
    }

    private function setSocialClass(CharacterFromSessionDTO $character, array $values): void
    {
        $socialClass = $this->getRepository(SocialClass::class)->find($values['05_social_class']['id']);

        if (!$socialClass instanceof SocialClass) {
            throw new InvalidSessionToCharacterValue('social_class', $socialClass, SocialClass::class);
        }

        $character->setSocialClass($socialClass);

        $domains = $values['05_social_class']['domains'];
        $character->setSocialClassDomain1($domains[0]);
        $character->setSocialClassDomain2($domains[1]);
    }

    private function setAge(CharacterFromSessionDTO $character, array $values): void
    {
        $character->setAge($values['06_age']);
    }

    private function setSetbacks(CharacterFromSessionDTO $character, array $values): void
    {
        foreach ($values['07_setbacks'] as $id => $details) {
            $character->addSetback(SessionSetbackDTO::create($this->setbacks[$id], $details['avoided']));
        }
    }

    private function setWays(CharacterFromSessionDTO $character, array $values): void
    {
        $character->setWays(new Ways(
            $values['08_ways'][WaysData::COMBATIVENESS],
            $values['08_ways'][WaysData::CREATIVITY],
            $values['08_ways'][WaysData::EMPATHY],
            $values['08_ways'][WaysData::REASON],
            $values['08_ways'][WaysData::CONVICTION]
        ));
    }

    private function setTraits(CharacterFromSessionDTO $character, array $values): void
    {
        $quality = $this->getRepository(PersonalityTrait::class)->find($values['09_traits']['quality']);

        if (!$quality instanceof PersonalityTrait) {
            throw new InvalidSessionToCharacterValue('quality', $quality, PersonalityTrait::class);
        }

        $flaw = $this->getRepository(PersonalityTrait::class)->find($values['09_traits']['flaw']);

        if (!$flaw instanceof PersonalityTrait) {
            throw new InvalidSessionToCharacterValue('flaw', $flaw, PersonalityTrait::class);
        }

        $character->setQuality($quality);
        $character->setFlaw($flaw);
    }

    private function setOrientation(CharacterFromSessionDTO $character, array $values): void
    {
        $character->setOrientation($values['10_orientation']);
    }

    private function setAdvantages(CharacterFromSessionDTO $character, array $values): void
    {
        foreach ($values['11_advantages']['advantages'] as $id => $value) {
            if (!$value) {
                continue;
            }
            $this->addAdvantageToCharacter(
                $character,
                $this->advantages[$id],
                $value,
                $values['11_advantages']['advantages_indications'][$id] ?? ''
            );
        }

        foreach ($values['11_advantages']['disadvantages'] as $id => $value) {
            if (!$value) {
                continue;
            }
            $this->addAdvantageToCharacter(
                $character,
                $this->advantages[$id],
                $value,
                $values['11_advantages']['advantages_indications'][$id] ?? ''
            );
        }
    }

    private function addAdvantageToCharacter(CharacterFromSessionDTO $character, Advantage $advantage, int $score, string $indication): void
    {
        $character->addAdvantage(SessionAdvantageDTO::create($advantage, $score, $indication));
    }

    private function setMentalDisorder(CharacterFromSessionDTO $character, array $values): void
    {
        $mentalDisorder = $this->getRepository(MentalDisorder::class)->find($values['12_mental_disorder']);

        if (!$mentalDisorder instanceof MentalDisorder) {
            throw new InvalidSessionToCharacterValue('mental_disorder', $mentalDisorder, MentalDisorder::class);
        }

        $character->setMentalDisorder($mentalDisorder);
    }

    private function setDisciplines(CharacterFromSessionDTO $character, array $values): void
    {
        foreach ($values['16_disciplines']['disciplines'] as $domainId => $disciplines) {
            foreach ($disciplines as $id => $v) {
                $discipline = $this->getRepository(Discipline::class)->find($id);

                if (!$discipline instanceof Discipline) {
                    throw new InvalidSessionToCharacterValue('discipline', $discipline, Discipline::class);
                }

                $character->addDiscipline($discipline);
            }
        }
    }

    private function setCombatArts(CharacterFromSessionDTO $character, array $values): void
    {
        foreach ($values['17_combat_arts']['combatArts'] as $id => $v) {
            $combatArt = $this->getRepository(CombatArt::class)->find($id);

            if (!$combatArt instanceof CombatArt) {
                throw new InvalidSessionToCharacterValue('combat_art', $combatArt, CombatArt::class);
            }

            $character->addCombatArt($combatArt);
        }
    }

    private function setEquipment(CharacterFromSessionDTO $character, array $values): void
    {
        $character->setInventory($values['18_equipment']['equipment']);

        foreach ($values['18_equipment']['armors'] as $id => $value) {
            $armor = $this->getRepository(Armor::class)->find($id);

            if (!$armor instanceof Armor) {
                throw new InvalidSessionToCharacterValue('armor', $armor, Armor::class);
            }

            $character->addArmor($armor);
        }
        foreach ($values['18_equipment']['weapons'] as $id => $value) {
            $weapon = $this->getRepository(Weapon::class)->find($id);

            if (!$weapon instanceof Weapon) {
                throw new InvalidSessionToCharacterValue('weapon', $weapon, Weapon::class);
            }

            $character->addWeapon($weapon);
        }
    }

    private function setDescription(CharacterFromSessionDTO $character, array $values): void
    {
        $details = $values['19_description'];
        $character->setPlayerName(\trim($details['player_name']));
        $character->setSex($details['sex']);
        $character->setDescription(\trim($details['description']));
        $character->setStory(\trim($details['story']));
        $character->setFacts(\trim($details['facts']));
    }

    private function setExp(CharacterFromSessionDTO $character, array $values): void
    {
        $character->setExperienceActual((int) $values['17_combat_arts']['remainingExp']);
    }

    private function setMoney(CharacterFromSessionDTO $character): void
    {
        $money = new Money();

        $salary = $character->getJob()->getDailySalary();

        if ($salary > 0) {
            foreach ($character->getSetbacks() as $setback) {
                if ($setback->getSetback()->getMalus() === Bonuses::MONEY_0) {
                    // Use salary only if job defines one AND character is not poor
                    $money->addEmber(30 * $salary);
                    $money->reallocate();
                    break;
                }
            }
        } else {
            // If salary is not set in job, character has 2d10 azure daols
            $azure = \random_int(1, 10) + \random_int(1, 10);
            foreach ($character->getSetbacks() as $setback) {
                if ($setback->getSetback()->getMalus() === Bonuses::MONEY_0) {
                    // If character is poor, he has half money
                    $azure = (int) \floor($azure / 2);
                    break;
                }
            }
            $money->addAzure($azure);
            $money->reallocate();
        }

        $character->setMoney($money);
    }

    private function setDomains(CharacterFromSessionDTO $character, array $values): void
    {
        $domainsBaseValues = $this->domainsCalculator->calculateFromGeneratorData(
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

        $character->setOstService($values['13_primary_domains']['ost']);
    }

    private function setHealthCondition(CharacterFromSessionDTO $character): void
    {
        $health = new HealthCondition();
        $good = $health->getGood();
        $okay = $health->getOkay();
        $bad = $health->getBad();
        $critical = $health->getCritical();

        // TODO

        foreach ($character->getAdvantages() as $charAdvantage) {
            $adv = $charAdvantage->getAdvantage();

            foreach ($adv->getBonusesFor() as $bonus) {
                if (isset($this->domains[$bonus])) {
                    continue;
                }

                $disadvantageRatio = $adv->isDisadvantage() ? -1 : 1;
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
                        $character->setStaminaBonus($character->getStaminaBonus() + ($charAdvantage->getScore() * $disadvantageRatio));
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
        $character->setHealthCondition($health);
    }

    private function setPrecalculatedValues(CharacterFromSessionDTO $character): void
    {
        // Rindath
        $rindathMax =
            $character->getWays()->getCombativeness()
            + $character->getWays()->getCreativity()
            + $character->getWays()->getEmpathy()
        ;

        // FIXME: This uses the discipline by name, this should be removed for proper abstraction

        $sigilRann = false;
        foreach ($character->getDisciplines() as $discipline) {
            if ($discipline->getName() === 'Sigil Rann') {
                $sigilRann = true;
                break;
            }
        }
        if ($sigilRann) {
            // Default discipline can only be a score of 6.
            // Rindath is increased by sigil rann according to this formula:
            // Bonus = (Score - 5) * 5
            // With a score of 6, well, it's 1*5, so... 5.
            // That's all for me, thanks.
            // (same calculation for Miracles & Exaltation by the way)
            $rindathMax += 5;
        }
        $character->setRindathMax($rindathMax);

        // Exaltation
        $exaltationMax = $character->getWays()->getConviction() * 3;
        $miracles = false;
        foreach ($character->getDisciplines() as $discipline) {
            if ($discipline->getName() === 'Miracles') {
                $miracles = true;
                break;
            }
        }
        if ($miracles) {
            // See "sigil rann" formula a few lines above to know why it's 5.
            $exaltationMax += 5;
        }
        $character->setExaltationMax($exaltationMax);
    }
}

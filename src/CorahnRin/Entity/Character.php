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

namespace CorahnRin\Entity;

use Behat\Transliterator\Transliterator;
use CorahnRin\Data\DomainsData;
use CorahnRin\DTO\CharacterFromSessionDTO;
use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Entity\CharacterProperties\CharacterAdvantageItem;
use CorahnRin\Entity\CharacterProperties\CharacterDomains;
use CorahnRin\Entity\CharacterProperties\CharDisciplines;
use CorahnRin\Entity\CharacterProperties\CharFlux;
use CorahnRin\Entity\CharacterProperties\CharSetbacks;
use CorahnRin\Entity\CharacterProperties\HealthCondition;
use CorahnRin\Entity\CharacterProperties\Money;
use CorahnRin\Entity\CharacterProperties\Ways;
use CorahnRin\Exception\CharacterException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Entity\Zone;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Pierstoval\Bundle\CharacterManagerBundle\Entity\Character as BaseCharacter;
use User\Entity\User;

/**
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\CharactersRepository")
 * @ORM\Table(name="characters",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="idcUnique", columns={"name_slug", "user_id"})
 *     }
 * )
 */
class Character extends BaseCharacter
{
    use TimestampableEntity;

    public const FEMALE = 'character.sex.female';
    public const MALE = 'character.sex.male';

    public const COMBAT_ATTITUDE_STANDARD = 'character.combat_attitude.standard';
    public const COMBAT_ATTITUDE_OFFENSIVE = 'character.combat_attitude.offensive';
    public const COMBAT_ATTITUDE_DEFENSIVE = 'character.combat_attitude.defensive';
    public const COMBAT_ATTITUDE_QUICK = 'character.combat_attitude.quick';
    public const COMBAT_ATTITUDE_MOVEMENT = 'character.combat_attitude.movement';

    public const COMBAT_ATTITUDES = [
        self::COMBAT_ATTITUDE_STANDARD,
        self::COMBAT_ATTITUDE_OFFENSIVE,
        self::COMBAT_ATTITUDE_DEFENSIVE,
        self::COMBAT_ATTITUDE_QUICK,
        self::COMBAT_ATTITUDE_MOVEMENT,
    ];

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_slug", type="string", length=255, nullable=false)
     * @Gedmo\Slug(fields={"name"}, unique=false)
     */
    protected $nameSlug;

    /**
     * @var string
     *
     * @ORM\Column(name="player_name", type="string", length=255, nullable=false)
     */
    protected $playerName;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=30, nullable=false)
     */
    protected $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description = '';

    /**
     * @var string
     *
     * @ORM\Column(name="story", type="text")
     */
    protected $story;

    /**
     * @var string
     *
     * @ORM\Column(name="facts", type="text")
     */
    protected $facts;

    /**
     * @var array
     *
     * @ORM\Column(name="inventory", type="simple_array")
     */
    protected $inventory = [];

    /**
     * @var array
     *
     * @ORM\Column(name="treasures", type="simple_array", nullable=true)
     */
    protected $treasures = [];

    /**
     * @var Money
     *
     * @ORM\Embedded(class="CorahnRin\Entity\CharacterProperties\Money", columnPrefix="daol_")
     */
    protected $money;

    /**
     * @var string
     *
     * @ORM\Column(name="orientation", type="string", length=40)
     */
    protected $orientation;

    /**
     * @var GeoEnvironment
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\GeoEnvironment")
     */
    protected $geoLiving;

    /**
     * @var int
     *
     * @ORM\Column(name="temporary_trauma", type="smallint", options={"default" = 0})
     */
    protected $temporaryTrauma = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="permanent_trauma", type="smallint", options={"default" = 0})
     */
    protected $permanentTrauma = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="hardening", type="smallint", options={"default" = 0})
     */
    protected $hardening = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="smallint", nullable=false)
     */
    protected $age = 16;

    /**
     * @var int
     *
     * @ORM\Column(name="mental_resistance_bonus", type="smallint")
     */
    protected $mentalResistanceBonus = 0;

    /**
     * @var Ways
     *
     * @ORM\Embedded(class="CorahnRin\Entity\CharacterProperties\Ways", columnPrefix="way_")
     */
    protected $ways;

    /**
     * @var HealthCondition
     *
     * @ORM\Embedded(class="CorahnRin\Entity\CharacterProperties\HealthCondition", columnPrefix="health_")
     */
    protected $health;

    /**
     * @var HealthCondition
     *
     * @ORM\Embedded(class="CorahnRin\Entity\CharacterProperties\HealthCondition", columnPrefix="max_health_")
     */
    protected $maxHealth;

    /**
     * @var int
     *
     * @ORM\Column(name="stamina", type="smallint")
     */
    protected $stamina = 10;

    /**
     * @var int
     *
     * @ORM\Column(name="stamina_bonus", type="smallint")
     */
    protected $staminaBonus = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="survival", type="smallint")
     */
    protected $survival = 3;

    /**
     * @var int
     *
     * @ORM\Column(name="speed", type="smallint")
     */
    protected $speed = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="speed_bonus", type="smallint")
     */
    protected $speedBonus = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="defense", type="smallint")
     */
    protected $defense = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="defense_bonus", type="smallint")
     */
    protected $defenseBonus = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="rindath", type="smallint")
     */
    protected $rindath = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="rindathMax", type="smallint")
     */
    protected $rindathMax = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="exaltation", type="smallint")
     */
    protected $exaltation = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="exaltation_max", type="smallint")
     */
    protected $exaltationMax = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="experience_actual", type="smallint")
     */
    protected $experienceActual = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="experience_spent", type="smallint")
     */
    protected $experienceSpent = 0;

    /**
     * @var People
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\People")
     */
    protected $people;

    /**
     * @var Armor[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Armor")
     * @ORM\JoinTable(name="characters_armors",
     *     joinColumns={@ORM\JoinColumn(name="characters_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="armors_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $armors;

    /**
     * @var MagienceArtifact[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\MagienceArtifact")
     * @ORM\JoinTable(name="characters_artifacts",
     *     joinColumns={@ORM\JoinColumn(name="characters_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="artifacts_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $artifacts;

    /**
     * @var Miracle[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Miracle")
     * @ORM\JoinTable(name="characters_miracles",
     *     joinColumns={@ORM\JoinColumn(name="characters_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="miracles_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $miracles;

    /**
     * @var Ogham[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Ogham")
     * @ORM\JoinTable(name="characters_ogham",
     *     joinColumns={@ORM\JoinColumn(name="characters_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="ogham_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $ogham;

    /**
     * @var Weapon[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Weapon")
     * @ORM\JoinTable(name="characters_weapons",
     *     joinColumns={@ORM\JoinColumn(name="characters_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="weapons_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $weapons;

    /**
     * @var CombatArt[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\CombatArt")
     * @ORM\JoinTable(name="characters_combat_arts",
     *     joinColumns={@ORM\JoinColumn(name="characters_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="combat_arts_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $combatArts;

    /**
     * @var SocialClass
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\SocialClass")
     */
    protected $socialClass;

    /**
     * @var string
     *
     * @ORM\Column(name="social_class_domain1", type="string", length=100)
     */
    protected $socialClassDomain1;

    /**
     * @var string
     *
     * @ORM\Column(name="social_class_domain2", type="string", length=100)
     */
    protected $socialClassDomain2;

    /**
     * @var string
     *
     * @ORM\Column(name="ost_service", type="string", length=100)
     */
    protected $ostService;

    /**
     * @var MentalDisorder
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\MentalDisorder")
     */
    protected $mentalDisorder;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Job")
     */
    protected $job;

    /**
     * @var Zone
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\Zone")
     */
    protected $birthPlace;

    /**
     * @var PersonalityTrait
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\PersonalityTrait")
     * @ORM\JoinColumn(name="trait_flaw_id")
     */
    protected $flaw;

    /**
     * @var PersonalityTrait
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\PersonalityTrait")
     * @ORM\JoinColumn(name="trait_quality_id")
     */
    protected $quality;

    /**
     * @var CharacterAdvantageItem[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharacterAdvantageItem", mappedBy="character", cascade={"persist", "remove"})
     */
    protected $advantages;

    /**
     * @var CharacterDomains
     *
     * @ORM\Embedded(class="CorahnRin\Entity\CharacterProperties\CharacterDomains", columnPrefix="domain_")
     */
    protected $domains;

    /**
     * @var CharDisciplines[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharDisciplines", mappedBy="character", cascade={"persist", "remove"})
     */
    protected $disciplines;

    /**
     * @var CharFlux[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharFlux", mappedBy="character")
     */
    protected $flux;

    /**
     * @var CharSetbacks[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharSetbacks", mappedBy="character")
     */
    protected $setbacks;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     */
    protected $user;

    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Game", inversedBy="characters")
     */
    protected $game;

    public static function createFromSession(CharacterFromSessionDTO $characterFromSession): self
    {
        $self = new self($characterFromSession->getName());
    }

    private function __construct(string $name)
    {
        parent::__construct(\trim($name), Transliterator::urlize($name));
        $this->maxHealth = new HealthCondition();
        $this->armors = new ArrayCollection();
        $this->artifacts = new ArrayCollection();
        $this->miracles = new ArrayCollection();
        $this->ogham = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->combatArts = new ArrayCollection();
        $this->advantages = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
        $this->flux = new ArrayCollection();
        $this->setbacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStory(): string
    {
        return $this->story;
    }

    public function getFacts(): string
    {
        return $this->facts;
    }

    /**
     * @return string[]|iterable
     */
    public function getInventory(): iterable
    {
        return $this->inventory;
    }

    /**
     * @return string[]|iterable
     */
    public function getTreasures(): iterable
    {
        return $this->treasures ?: [];
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getOrientation(): string
    {
        return $this->orientation;
    }

    public function getGeoLiving(): GeoEnvironment
    {
        return $this->geoLiving;
    }

    public function getTemporaryTrauma(): int
    {
        return $this->temporaryTrauma;
    }

    public function getPermanentTrauma(): int
    {
        return $this->permanentTrauma;
    }

    public function getHardening(): int
    {
        return $this->hardening;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getMentalResistanceBonus(): int
    {
        return $this->mentalResistanceBonus;
    }

    public function getCombativeness(): int
    {
        return $this->ways->getCombativeness();
    }

    public function getCreativity(): int
    {
        return $this->ways->getCreativity();
    }

    public function getEmpathy(): int
    {
        return $this->ways->getEmpathy();
    }

    public function getReason(): int
    {
        return $this->ways->getReason();
    }

    public function getConviction(): int
    {
        return $this->ways->getConviction();
    }

    public function getWay(string $way): int
    {
        return $this->ways->getWay($way);
    }

    public function getWays(): Ways
    {
        return $this->ways;
    }

    public function getHealth(): HealthCondition
    {
        return $this->health;
    }

    public function getMaxHealth(): HealthCondition
    {
        return $this->maxHealth;
    }

    public function getStamina(): int
    {
        return $this->stamina;
    }

    public function getStaminaBonus(): int
    {
        return $this->staminaBonus;
    }

    public function getSurvival(): int
    {
        return $this->survival;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function getSpeedBonus(): int
    {
        return $this->speedBonus;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function getDefenseBonus(): int
    {
        return $this->defenseBonus;
    }

    public function getRindath(): int
    {
        return $this->rindath;
    }

    public function getRindathMax(): int
    {
        return $this->rindathMax;
    }

    public function getExaltation(): int
    {
        return $this->exaltation;
    }

    public function getExaltationMax(): int
    {
        return $this->exaltationMax;
    }

    public function getExperienceActual(): int
    {
        return $this->experienceActual;
    }

    public function getExperienceSpent(): int
    {
        return $this->experienceSpent;
    }

    public function getPeople(): People
    {
        return $this->people;
    }

    /**
     * @return Armor[]|iterable
     */
    public function getArmors(): iterable
    {
        return $this->armors;
    }

    /**
     * @return MagienceArtifact[]|iterable
     */
    public function getArtifacts(): iterable
    {
        return $this->artifacts;
    }

    /**
     * @return Miracle[]|iterable
     */
    public function getMiracles(): iterable
    {
        return $this->miracles;
    }

    /**
     * @return Ogham[]|iterable
     */
    public function getOgham(): iterable
    {
        return $this->ogham;
    }

    /**
     * @return Weapon[]|iterable
     */
    public function getWeapons(): iterable
    {
        return $this->weapons;
    }

    /**
     * @return CombatArt[]|iterable
     */
    public function getCombatArts(): iterable
    {
        return $this->combatArts;
    }

    public function getSocialClass(): SocialClass
    {
        return $this->socialClass;
    }

    public function getSocialClassDomain1(): string
    {
        return $this->socialClassDomain1;
    }

    public function getSocialClassDomain2(): string
    {
        return $this->socialClassDomain2;
    }

    public function getOstService(): string
    {
        return $this->ostService;
    }

    public function getMentalDisorder(): MentalDisorder
    {
        return $this->mentalDisorder;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function getBirthPlace(): Zone
    {
        return $this->birthPlace;
    }

    public function getFlaw(): PersonalityTrait
    {
        return $this->flaw;
    }

    public function getQuality(): PersonalityTrait
    {
        return $this->quality;
    }

    /**
     * @return CharacterAdvantageItem[]|iterable
     */
    public function getAllAdvantages(): iterable
    {
        return $this->advantages;
    }

    public function getDomains(): CharacterDomains
    {
        return $this->domains;
    }

    /**
     * @return CharDisciplines[]|iterable
     */
    public function getDisciplines(): iterable
    {
        return $this->disciplines;
    }

    /**
     * @return CharFlux[]|iterable
     */
    public function getFlux(): iterable
    {
        return $this->flux;
    }

    /**
     * @return CharSetbacks[]|ArrayCollection
     */
    public function getSetbacks()
    {
        return $this->setbacks;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    /*-------------------------------------------------*/
    /*-------------------------------------------------*/
    /*--------- Methods used for entity logic ---------*/
    /*-------------------------------------------------*/
    /*-------------------------------------------------*/

    /**
     * @return CharacterAdvantageItem[]
     */
    public function getAdvantages(): array
    {
        $advantages = [];

        foreach ($this->advantages as $charAdvantage) {
            if (!$charAdvantage->getAdvantage()->isDisadvantage()) {
                $advantages[] = $charAdvantage;
            }
        }

        return $advantages;
    }

    /**
     * @return CharacterAdvantageItem[]
     */
    public function getDisadvantages(): array
    {
        $advantages = [];

        foreach ($this->advantages as $charAdvantage) {
            if ($charAdvantage->getAdvantage()->isDisadvantage()) {
                $advantages[] = $charAdvantage;
            }
        }

        return $advantages;
    }

    /**
     * Conscience is determined by "Reason" and "Conviction" ways.
     */
    public function getConsciousness(): int
    {
        return $this->getReason() + $this->getConviction();
    }

    /**
     * Conscience is determined by "Creativity" and "Combativity" ways.
     */
    public function getInstinct(): int
    {
        return $this->getCreativity() + $this->getCombativeness();
    }

    public function getDomain(string $name): int
    {
        return $this->domains->getDomainValue($name);
    }

    /**
     * @return CharDisciplines[]
     */
    public function getDisciplineFromDomain(string $domain): array
    {
        $disciplines = [];

        foreach ($this->disciplines as $discipline) {
            if ($discipline->getDomain() === $domain) {
                $disciplines[] = $discipline;
            }
        }

        return $disciplines;
    }

    public function getDiscipline($id): ?CharDisciplines
    {
        foreach ($this->disciplines as $charDiscipline) {
            $discipline = $charDiscipline->getDiscipline();
            if (
                $charDiscipline instanceof CharDisciplines &&
                (($discipline->getId() === (int) $id) || $discipline->getName() === $id)
            ) {
                return $charDiscipline;
            }
        }

        return null;
    }

    /**
     * Base defense is calculated from "Reason" and "Empathy".
     */
    public function getBaseDefense(): int
    {
        return $this->getReason() + $this->getEmpathy() + 5;
    }

    public function getTotalDefense(string $attitude = self::COMBAT_ATTITUDE_STANDARD): int
    {
        self::validateCombatAttitude($attitude);

        $defense = $this->getBaseDefense() + $this->defense + $this->defenseBonus;

        switch ($attitude) {
            case self::COMBAT_ATTITUDE_DEFENSIVE:
            case self::COMBAT_ATTITUDE_MOVEMENT:
                $defense += $this->getPotential();
                break;
            case self::COMBAT_ATTITUDE_OFFENSIVE:
                $defense -= $this->getPotential();
                break;
        }

        return $defense;
    }

    /**
     * Base speed is calculated from "Combativity" and "Empathy".
     */
    public function getBaseSpeed(): int
    {
        return $this->getCombativeness() + $this->getEmpathy();
    }

    public function getTotalSpeed($attitude = self::COMBAT_ATTITUDE_STANDARD): int
    {
        self::validateCombatAttitude($attitude);

        $speed = $this->getBaseSpeed() + $this->speed + $this->speedBonus;

        if (self::COMBAT_ATTITUDE_QUICK === $attitude) {
            $speed += $this->getPotential();
        }

        return $speed;
    }

    public function getBaseMentalResistance(): int
    {
        return $this->getConviction() + 5;
    }

    public function getTotalMentalResistance(): int
    {
        $value = $this->getBaseMentalResistance() + $this->mentalResistanceBonus;

        foreach ($this->getAdvantages() as $disadvantage) {
            if (Bonuses::MENTAL_RESISTANCE === $disadvantage->getAdvantage()->getBonusesFor()) {
                $value += $disadvantage->getScore();
            }
        }

        foreach ($this->getDisadvantages() as $disadvantage) {
            if (Bonuses::MENTAL_RESISTANCE === $disadvantage->getAdvantage()->getBonusesFor()) {
                $value -= $disadvantage->getScore();
            }
        }

        return $value;
    }

    public function getPotential(): int
    {
        $creativity = $this->getCreativity();

        switch ($creativity) {
            case 1:
                return 1;
                break;
            case 2:
            case 3:
            case 4:
                return 2;
            break;
            case 5:
                return 3;
                break;
            default:
                throw new CharacterException('Wrong creativity value to calculate potential');
        }
    }

    public function getMeleeAttackScore(int $discipline = null, string $potentialOperator = ''): int
    {
        return $this->getAttackScore('melee', $discipline, $potentialOperator);
    }

    public function getAttackScore(
        string $type = 'melee',
        int $discipline = null,
        string $attitude = self::COMBAT_ATTITUDE_STANDARD
    ): int {
        self::validateCombatAttitude($attitude);

        // Récupération du score de voie
        $way = $this->getCombativeness();

        if ('melee' === $type) {
            $domain_id = DomainsData::CLOSE_COMBAT['title'];
        } elseif ('ranged' === $type) {
            $domain_id = DomainsData::SHOOTING_AND_THROWING['title'];
        } else {
            throw new CharacterException('Attack can only be "melee" or "ranged".');
        }

        // Récupération du score du domaine
        $domain = $this->getDomain($domain_id);

        // Si on indique une discipline, le score du domaine sera remplacé par le score de discipline
        if (null !== $discipline) {
            $charDiscipline = $this->getDiscipline($discipline);

            // Il faut impérativement que la discipline soit associée au même domaine
            if ($charDiscipline->getDomain() === $domain_id) {
                // Remplacement du nouveau score
                $domain = $charDiscipline->getScore();
            }
        }

        $attack = $way + $domain;

        switch ($attitude) {
            case self::COMBAT_ATTITUDE_OFFENSIVE:
                $attack += $this->getPotential();
                break;
            case self::COMBAT_ATTITUDE_DEFENSIVE:
                $attack -= $this->getPotential();
                break;
            case self::COMBAT_ATTITUDE_MOVEMENT:
                $attack = 0;
                break;
        }

        return $attack;
    }

    public function hasAdvantage($id): bool
    {
        $id = (int) $id;

        foreach ($this->advantages as $advantage) {
            if ($advantage->getAdvantage()->getId() === $id) {
                return true;
            }
        }

        return false;
    }

    public function hasSetback($id, $falseIfAvoided = true): bool
    {
        $id = (int) $id;

        foreach ($this->setbacks as $setback) {
            if ($setback->getSetback()->getId() === $id) {
                if ($falseIfAvoided && $setback->isAvoided()) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    private static function validateCombatAttitude(string $attitude): void
    {
        if (!\in_array($attitude, self::COMBAT_ATTITUDES, true)) {
            throw new \InvalidArgumentException("Combat attitude is invalid, $attitude given.");
        }
    }
}

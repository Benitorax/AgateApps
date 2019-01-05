<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity;

use CorahnRin\Data\DomainsData;
use CorahnRin\Data\Orientation;
use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Entity\CharacterProperties\CharacterDomains;
use CorahnRin\Entity\CharacterProperties\CharAdvantages;
use CorahnRin\Entity\CharacterProperties\CharDisciplines;
use CorahnRin\Entity\CharacterProperties\CharFlux;
use CorahnRin\Entity\CharacterProperties\CharSetbacks;
use CorahnRin\Entity\CharacterProperties\HealthCondition;
use CorahnRin\Entity\CharacterProperties\Money;
use CorahnRin\Entity\CharacterProperties\Ways;
use CorahnRin\Exception\CharactersException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Entity\Zone;
use Gedmo\Mapping\Annotation as Gedmo;
use Pierstoval\Bundle\CharacterManagerBundle\Entity\Character as BaseCharacter;
use User\Entity\User;

/**
 * Characters.
 *
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\CharactersRepository")
 * @ORM\Table(name="characters", uniqueConstraints={@ORM\UniqueConstraint(name="idcUnique", columns={"name_slug", "user_id"})})
 */
class Characters extends BaseCharacter
{
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
     * @ORM\Column(name="sex", type="string", length=1, nullable=false)
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
     * @ORM\Column(name="treasures", type="simple_array")
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
     * @ORM\Column(name="orientation", type="string", length=30)
     */
    protected $orientation;

    /**
     * @var GeoEnvironment
     *
     * @ORM\ManyToOne(targetEntity="GeoEnvironment")
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
     * @ORM\ManyToOne(targetEntity="People")
     */
    protected $people;

    /**
     * @var Armors[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Armors")
     */
    protected $armors;

    /**
     * @var Artifacts[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Artifacts")
     */
    protected $artifacts;

    /**
     * @var Miracle[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Miracle")
     */
    protected $miracles;

    /**
     * @var Ogham[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Ogham")
     */
    protected $ogham;

    /**
     * @var Weapon[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Weapon")
     */
    protected $weapons;

    /**
     * @var CombatArt[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CombatArt")
     */
    protected $combatArts;

    /**
     * @var SocialClass
     *
     * @ORM\ManyToOne(targetEntity="SocialClass")
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
     * @var MentalDisorder
     *
     * @ORM\ManyToOne(targetEntity="MentalDisorder")
     */
    protected $mentalDisorder;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Job")
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
     * @ORM\ManyToOne(targetEntity="PersonalityTrait")
     * @ORM\JoinColumn(name="trait_flaw_id")
     */
    protected $flaw;

    /**
     * @var PersonalityTrait
     * @ORM\ManyToOne(targetEntity="PersonalityTrait")
     * @ORM\JoinColumn(name="trait_quality_id")
     */
    protected $quality;

    /**
     * @var CharAdvantages[]
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharAdvantages", mappedBy="character")
     */
    protected $charAdvantages;

    /**
     * @var CharacterDomains
     *
     * @ORM\Embedded(class="CorahnRin\Entity\CharacterProperties\CharacterDomains", columnPrefix="domain_")
     */
    protected $domains;

    /**
     * @var CharDisciplines[]
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharDisciplines", mappedBy="character")
     */
    protected $disciplines;

    /**
     * @var CharFlux[]
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharFlux", mappedBy="character")
     */
    protected $flux;

    /**
     * @var CharSetbacks[]
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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="characters")
     */
    protected $game;

    /**
     * @var \DateTimeInterface
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \Datetime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->maxHealth = new HealthCondition();
        $this->armors = new ArrayCollection();
        $this->artifacts = new ArrayCollection();
        $this->miracles = new ArrayCollection();
        $this->ogham = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->combatArts = new ArrayCollection();
        $this->charAdvantages = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
        $this->flux = new ArrayCollection();
        $this->setbacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setPlayerName(string $playerName): self
    {
        $this->playerName = $playerName;

        return $this;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function setSex(string $sex): self
    {
        if ($sex !== static::MALE && $sex !== static::FEMALE) {
            throw new \InvalidArgumentException(\sprintf(
                'Sex must be either "%s" or "%s", "%s" given.',
                static::MALE, static::FEMALE, $sex
            ));
        }

        $this->sex = $sex;

        return $this;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setStory(string $story): self
    {
        $this->story = $story;

        return $this;
    }

    public function getStory(): string
    {
        return $this->story;
    }

    public function setFacts(string $facts): self
    {
        $this->facts = $facts;

        return $this;
    }

    public function getFacts(): string
    {
        return $this->facts;
    }

    public function setInventory(array $inventory): self
    {
        foreach ($inventory as $k => $item) {
            $item = \trim($item);
            if (!$item) {
                unset($inventory[$k]);
                continue;
            }

            if (!\is_string($item) || \is_numeric($item)) {
                throw new \InvalidArgumentException('Provided item must be a non-numeric string.');
            }
        }

        $this->inventory = $inventory;

        return $this;
    }

    /**
     * @return string[]|iterable
     */
    public function getInventory(): iterable
    {
        return $this->inventory;
    }

    public function setTreasures(array $treasures): self
    {
        foreach ($treasures as $k => $treasure) {
            $treasure = \trim($treasure);
            if (!$treasure) {
                unset($treasures[$k]);
                continue;
            }

            if (!\is_string($treasure) || \is_numeric($treasure)) {
                throw new \InvalidArgumentException('Provided treasure must be a non-numeric string.');
            }
        }

        $this->treasures = $treasures;

        return $this;
    }

    /**
     * @return string[]|iterable
     */
    public function getTreasures(): iterable
    {
        return $this->treasures;
    }

    public function setMoney(Money $money): self
    {
        $this->money = $money;

        return $this;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function setOrientation(string $orientation): self
    {
        if (!\array_key_exists($orientation, Orientation::ALL)) {
            throw new \InvalidArgumentException(\sprintf(
                'Orientation must be one value in "%s", "%s" given.',
                \implode('", "', \array_keys(Orientation::ALL)), $orientation
            ));
        }

        $this->orientation = $orientation;

        return $this;
    }

    public function getOrientation(): string
    {
        return $this->orientation;
    }

    public function setGeoLiving(GeoEnvironment $geoLiving): self
    {
        $this->geoLiving = $geoLiving;

        return $this;
    }

    public function getGeoLiving(): GeoEnvironment
    {
        return $this->geoLiving;
    }

    public function setTemporaryTrauma(int $trauma): self
    {
        if ($trauma < 0) {
            throw new \InvalidArgumentException('Temporary trauma must be equal or superior to zero.');
        }

        $this->temporaryTrauma = $trauma;

        return $this;
    }

    public function getTemporaryTrauma(): int
    {
        return $this->temporaryTrauma;
    }

    public function setPermanentTrauma($permanentTrauma): self
    {
        if ($permanentTrauma < 0) {
            throw new \InvalidArgumentException('Permanent trauma must be equal or superior to zero.');
        }

        $this->permanentTrauma = $permanentTrauma;

        return $this;
    }

    public function getPermanentTrauma(): int
    {
        return $this->permanentTrauma;
    }

    public function setHardening(int $hardening): self
    {
        if ($hardening < 0) {
            throw new \InvalidArgumentException('Hardening must be equal or superior to zero.');
        }

        $this->hardening = $hardening;

        return $this;
    }

    public function getHardening(): int
    {
        return $this->hardening;
    }

    public function setAge(int $age): self
    {
        if ($age < 1) {
            throw new \InvalidArgumentException('Age must be equal or superior to one.');
        }

        $this->age = $age;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getMentalResistanceBonus(): int
    {
        return $this->mentalResistanceBonus;
    }

    public function setMentalResistanceBonus(int $mentalResistanceBonus): self
    {
        $this->mentalResistanceBonus = $mentalResistanceBonus;

        return $this;
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

    public function setWay(Ways $ways): void
    {
        $this->ways = $ways;
    }

    public function setHealth(HealthCondition $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getHealth(): HealthCondition
    {
        return $this->health;
    }

    public function setMaxHealth(HealthCondition $maxHealth): self
    {
        $this->maxHealth = $maxHealth;

        return $this;
    }

    public function getMaxHealth(): HealthCondition
    {
        return $this->maxHealth;
    }

    public function setStamina(int $stamina): self
    {
        $this->stamina = $stamina;

        return $this;
    }

    public function getStamina(): int
    {
        return $this->stamina;
    }

    public function getStaminaBonus(): int
    {
        return $this->staminaBonus;
    }

    public function setStaminaBonus(int $staminaBonus): void
    {
        $this->staminaBonus = $staminaBonus;
    }

    public function setSurvival(int $survival): self
    {
        $this->survival = $survival;

        return $this;
    }

    public function getSurvival(): int
    {
        return $this->survival;
    }

    public function setSpeed(int $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function getSpeedBonus(): int
    {
        return $this->speedBonus;
    }

    public function setSpeedBonus(int $speedBonus): self
    {
        $this->speedBonus = $speedBonus;

        return $this;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function getDefenseBonus(): int
    {
        return $this->defenseBonus;
    }

    public function setDefenseBonus(int $defenseBonus): void
    {
        $this->defenseBonus = $defenseBonus;
    }

    public function setRindath(int $rindath): self
    {
        $this->rindath = $rindath;

        return $this;
    }

    public function getRindath(): int
    {
        return $this->rindath;
    }

    public function getRindathMax(): int
    {
        return $this->rindathMax;
    }

    public function setRindathMax(int $rindathMax): self
    {
        $this->rindathMax = $rindathMax;

        return $this;
    }

    public function setExaltation(int $exaltation): self
    {
        $this->exaltation = $exaltation;

        return $this;
    }

    public function getExaltation(): int
    {
        return $this->exaltation;
    }

    public function getExaltationMax(): int
    {
        return $this->exaltationMax;
    }

    public function setExaltationMax(int $exaltationMax): self
    {
        $this->exaltationMax = $exaltationMax;

        return $this;
    }

    public function setExperienceActual(int $experienceActual): self
    {
        $this->experienceActual = $experienceActual;

        return $this;
    }

    public function getExperienceActual(): int
    {
        return $this->experienceActual;
    }

    public function setExperienceSpent(int $experienceSpent): self
    {
        $this->experienceSpent = $experienceSpent;

        return $this;
    }

    public function getExperienceSpent(): int
    {
        return $this->experienceSpent;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getUpdated(): \DateTimeInterface
    {
        return $this->updated;
    }

    public function setDeleted(\DateTimeInterface $deleted = null): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getDeleted(): \DateTimeInterface
    {
        return $this->deleted;
    }

    public function setPeople(People $people = null): self
    {
        $this->people = $people;

        return $this;
    }

    public function getPeople(): People
    {
        return $this->people;
    }

    public function addArmor(Armors $armor): self
    {
        $this->armors[] = $armor;

        return $this;
    }

    public function removeArmor(Armors $armor): self
    {
        $this->armors->removeElement($armor);

        return $this;
    }

    /**
     * @return Armors[]|iterable
     */
    public function getArmors(): iterable
    {
        return $this->armors;
    }

    public function addArtifact(Artifacts $artifact): self
    {
        $this->artifacts[] = $artifact;

        return $this;
    }

    public function removeArtifact(Artifacts $artifact): self
    {
        $this->artifacts->removeElement($artifact);

        return $this;
    }

    /**
     * @return Artifacts[]|iterable
     */
    public function getArtifacts(): iterable
    {
        return $this->artifacts;
    }

    public function addMiracle(Miracle $miracle): self
    {
        $this->miracles[] = $miracle;

        return $this;
    }

    public function removeMiracle(Miracle $miracle): self
    {
        $this->miracles->removeElement($miracle);

        return $this;
    }

    /**
     * @return Miracle[]|iterable
     */
    public function getMiracles(): iterable
    {
        return $this->miracles;
    }

    public function addOgham(Ogham $ogham): self
    {
        $this->ogham[] = $ogham;

        return $this;
    }

    public function removeOgham(Ogham $ogham): self
    {
        $this->ogham->removeElement($ogham);

        return $this;
    }

    /**
     * @return Ogham[]|iterable
     */
    public function getOgham(): iterable
    {
        return $this->ogham;
    }

    public function addWeapon(Weapon $weapon): self
    {
        $this->weapons[] = $weapon;

        return $this;
    }

    public function removeWeapon(Weapon $weapon): self
    {
        $this->weapons->removeElement($weapon);

        return $this;
    }

    /**
     * @return Weapon[]|iterable
     */
    public function getWeapons(): iterable
    {
        return $this->weapons;
    }

    public function addCombatArt(CombatArt $combatArt): self
    {
        $this->combatArts[] = $combatArt;

        return $this;
    }

    public function removeCombatArt(CombatArt $combatArt): self
    {
        $this->combatArts->removeElement($combatArt);

        return $this;
    }

    /**
     * @return CombatArt[]|iterable
     */
    public function getCombatArts(): iterable
    {
        return $this->combatArts;
    }

    public function setSocialClass(SocialClass $socialClass = null): self
    {
        $this->socialClass = $socialClass;

        return $this;
    }

    public function getSocialClass(): SocialClass
    {
        return $this->socialClass;
    }

    public function setSocialClassDomain1(string $socialClassDomain1 = null): void
    {
        DomainsData::validateDomain($socialClassDomain1);

        $this->socialClassDomain1 = $socialClassDomain1;
    }

    public function getSocialClassDomain1(): string
    {
        return $this->socialClassDomain1;
    }

    public function setSocialClassDomain2(string $socialClassDomain2 = null): void
    {
        DomainsData::validateDomain($socialClassDomain2);

        $this->socialClassDomain2 = $socialClassDomain2;
    }

    public function getSocialClassDomain2(): string
    {
        return $this->socialClassDomain2;
    }

    public function setMentalDisorder(MentalDisorder $mentalDisorder = null): self
    {
        $this->mentalDisorder = $mentalDisorder;

        return $this;
    }

    public function getMentalDisorder(): MentalDisorder
    {
        return $this->mentalDisorder;
    }

    public function setJob(Job $job = null): self
    {
        $this->job = $job;

        return $this;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function setBirthPlace(Zone $birthPlace = null): self
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    public function getBirthPlace(): Zone
    {
        return $this->birthPlace;
    }

    public function setFlaw(PersonalityTrait $flaw = null): self
    {
        $this->flaw = $flaw;

        return $this;
    }

    public function getFlaw(): PersonalityTrait
    {
        return $this->flaw;
    }

    public function setQuality(PersonalityTrait $quality = null): self
    {
        $this->quality = $quality;

        return $this;
    }

    public function getQuality(): PersonalityTrait
    {
        return $this->quality;
    }

    public function addCharAdvantage(CharAdvantages $advantage): self
    {
        $this->charAdvantages[] = $advantage;

        return $this;
    }

    public function removeCharAdvantage(CharAdvantages $advantage): self
    {
        $this->charAdvantages->removeElement($advantage);

        return $this;
    }

    /**
     * @return CharAdvantages[]|iterable
     */
    public function getCharAdvantages(): iterable
    {
        return $this->charAdvantages;
    }

    public function setDomains(CharacterDomains $domain): void
    {
        $this->domains = $domain;
    }

    public function getDomains(): CharacterDomains
    {
        return $this->domains;
    }

    public function addDiscipline(CharDisciplines $discipline): self
    {
        $this->disciplines[] = $discipline;

        return $this;
    }

    public function removeDiscipline(CharDisciplines $discipline): self
    {
        $this->disciplines->removeElement($discipline);

        return $this;
    }

    /**
     * @return CharDisciplines[]|iterable
     */
    public function getDisciplines(): iterable
    {
        return $this->disciplines;
    }

    public function addFlux(CharFlux $flux): self
    {
        $this->flux[] = $flux;

        return $this;
    }

    public function removeFlux(CharFlux $flux): self
    {
        $this->flux->removeElement($flux);

        return $this;
    }

    /**
     * @return CharFlux[]|iterable
     */
    public function getFlux(): iterable
    {
        return $this->flux;
    }

    public function addSetback(CharSetbacks $setback): self
    {
        $this->setbacks[] = $setback;

        return $this;
    }

    public function removeSetback(CharSetbacks $setback): self
    {
        $this->setbacks->removeElement($setback);

        return $this;
    }

    /**
     * @return CharSetbacks[]|ArrayCollection
     */
    public function getSetbacks()
    {
        return $this->setbacks;
    }

    public function setUser(User $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setGame(Game $game = null): self
    {
        $this->game = $game;

        return $this;
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
     * @return CharAdvantages[]
     */
    public function getAdvantages(): array
    {
        $advantages = [];

        foreach ($this->charAdvantages as $charAdvantage) {
            if (!$charAdvantage->getAdvantage()->isDesv()) {
                $advantages[] = $charAdvantage;
            }
        }

        return $advantages;
    }

    /**
     * @return CharAdvantages[]
     */
    public function getDisadvantages(): array
    {
        $advantages = [];

        foreach ($this->charAdvantages as $charAdvantage) {
            if ($charAdvantage->getAdvantage()->isDesv()) {
                $advantages[] = $charAdvantage;
            }
        }

        return $advantages;
    }

    /**
     * Conscience is determined by "Reason" and "Conviction" ways.
     */
    public function getConsciousness(): string
    {
        return $this->getReason() + $this->getConviction();
    }

    /**
     * Conscience is determined by "Creativity" and "Combativity" ways.
     */
    public function getInstinct(): string
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
        $this->validateCombatAttitude($attitude);

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
        $this->validateCombatAttitude($attitude);

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
                throw new CharactersException('Wrong creativity value to calculate potential');
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
        $this->validateCombatAttitude($attitude);

        // Récupération du score de voie
        $way = $this->getCombativeness();

        if ('melee' === $type) {
            $domain_id = DomainsData::CLOSE_COMBAT['title'];
        } elseif ('ranged' === $type) {
            $domain_id = DomainsData::SHOOTING_AND_THROWING['title'];
        } else {
            throw new CharactersException('Attack can only be "melee" or "ranged".');
        }

        // Récupération du score du domaine
        $domain = $this->getDomain($domain_id);

        // Si on indique une discipline, le score du domaine sera remplacé par le score de discipline
        if (null !== $discipline) {
            $charDiscipline = $this->getDiscipline($discipline);

            // Il faut impérativement que la discipline soit associée au même domaine
            if ($charDiscipline->getDomain()->getId() === $domain_id) {
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

    /**
     * @param int $id
     */
    public function hasAdvantage($id): bool
    {
        $id = (int) $id;

        foreach ($this->charAdvantages as $advantage) {
            if ($advantage->getAdvantage()->getId() === $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int  $id
     * @param bool $falseIfAvoided
     */
    public function hasSetback($id, $falseIfAvoided = true): bool
    {
        $id = (int) $id;

        foreach ($this->setbacks as $setback) {
            if ($setback->getSetback()->getId() === $id) {
                if (true === $falseIfAvoided && $setback->isAvoided()) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    private function validateCombatAttitude(string $attitude): void
    {
        if (!\in_array($attitude, self::COMBAT_ATTITUDES, true)) {
            throw new \InvalidArgumentException("Combat attitude is invalid, $attitude given.");
        }
    }
}

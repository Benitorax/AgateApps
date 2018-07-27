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

use CorahnRin\Data\Orientation;
use CorahnRin\Data\Ways;
use CorahnRin\Entity\CharacterProperties\CharAdvantages;
use CorahnRin\Entity\CharacterProperties\CharDisciplines;
use CorahnRin\Entity\CharacterProperties\CharDomains;
use CorahnRin\Entity\CharacterProperties\CharFlux;
use CorahnRin\Entity\CharacterProperties\CharSetbacks;
use CorahnRin\Entity\CharacterProperties\HealthCondition;
use CorahnRin\Entity\CharacterProperties\Money;
use CorahnRin\Exception\CharactersException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EsterenMaps\Entity\Zones;
use Gedmo\Mapping\Annotation as Gedmo;
use Pierstoval\Bundle\CharacterManagerBundle\Entity\Character as BaseCharacter;

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
    protected $inventory;

    /**
     * @var array
     *
     * @ORM\Column(name="treasures", type="simple_array")
     */
    protected $treasures;

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
     * @var GeoEnvironments
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\GeoEnvironments")
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
     * @var Peoples
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Peoples")
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
     * @var Miracles[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Miracles")
     */
    protected $miracles;

    /**
     * @var Ogham[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Ogham")
     */
    protected $ogham;

    /**
     * @var Weapons[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Weapons")
     */
    protected $weapons;

    /**
     * @var CombatArts[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\CombatArts")
     */
    protected $combatArts;

    /**
     * @var SocialClasses
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\SocialClasses")
     */
    protected $socialClass;

    /**
     * @var Domains
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Domains")
     */
    protected $socialClassDomain1;

    /**
     * @var Domains
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Domains")
     */
    protected $socialClassDomain2;

    /**
     * @var Disorders
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Disorders")
     */
    protected $mentalDisorder;

    /**
     * @var Jobs
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Jobs")
     */
    protected $job;

    /**
     * @var Zones
     *
     * @ORM\ManyToOne(targetEntity="EsterenMaps\Entity\Zones")
     */
    protected $birthPlace;

    /**
     * @var Traits
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Traits")
     * @ORM\JoinColumn(name="trait_flaw_id")
     */
    protected $flaw;

    /**
     * @var Traits
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Traits")
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
     * @var CharDomains[]
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharDomains", mappedBy="character")
     */
    protected $domains;

    /**
     * @var CharDisciplines[]
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\CharacterProperties\CharDisciplines", mappedBy="character")
     */
    protected $disciplines;

    /**
     * @var int
     *
     * @ORM\Column(name="combativeness", type="integer")
     */
    protected $combativeness;

    /**
     * @var int
     *
     * @ORM\Column(name="creativity", type="integer")
     */
    protected $creativity;

    /**
     * @var int
     *
     * @ORM\Column(name="empathy", type="integer")
     */
    protected $empathy;

    /**
     * @var int
     *
     * @ORM\Column(name="reason", type="integer")
     */
    protected $reason;

    /**
     * @var int
     *
     * @ORM\Column(name="conviction", type="integer")
     */
    protected $conviction;

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
     * @var \User\Entity\User
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     */
    protected $user;

    /**
     * @var Games
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Games", inversedBy="characters")
     */
    protected $game;

    /**
     * @var \Datetime
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
        $this->domains = new ArrayCollection();
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
     * @return string[]
     */
    public function getInventory(): array
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
     * @return string[]
     */
    public function getTreasures()
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

    public function setGeoLiving(GeoEnvironments $geoLiving): self
    {
        $this->geoLiving = $geoLiving;

        return $this;
    }

    public function getGeoLiving(): GeoEnvironments
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

    public function getMentalResistance(): int
    {
        $value = $this->conviction + 5;

        foreach ($this->getAdvantages() as $disadvantage) {
            if ('resm' === $disadvantage->getAdvantage()->getBonusdisc()) {
                $value += $disadvantage->getScore();
            }
        }

        foreach ($this->getDisadvantages() as $disadvantage) {
            if ('resm' === $disadvantage->getAdvantage()->getBonusdisc()) {
                $value -= $disadvantage->getScore();
            }
        }

        return $value;
    }

    public function getMentalResistanceBonus(): int
    {
        return $this->mentalResistanceBonus;
    }

    public function setMentalResistanceBonus(int $mentalResistanceBonus): self
    {
        if ($mentalResistanceBonus < 1) {
            throw new \InvalidArgumentException('Mental resistance must be equal or superior to zero.');
        }

        $this->mentalResistanceBonus = $mentalResistanceBonus;

        return $this;
    }

    public function getCombativeness(): int
    {
        return $this->combativeness;
    }

    public function getCreativity(): int
    {
        return $this->creativity;
    }

    public function getEmpathy(): int
    {
        return $this->empathy;
    }

    public function getReason(): int
    {
        return $this->reason;
    }

    public function getConviction(): int
    {
        return $this->conviction;
    }

    public function getWay(string $way): int
    {
        Ways::validateWay($way);

        switch ($way) {
            case Ways::COMBATIVENESS:
                return $this->combativeness;
            case Ways::CREATIVITY:
                return $this->creativity;
            case Ways::EMPATHY:
                return $this->empathy;
            case Ways::REASON:
                return $this->reason;
            case Ways::CONVICTION:
                return $this->conviction;
        }
    }

    public function setWay(string $way, int $value): void
    {
        Ways::validateWayValue($way, $value);

        switch ($way) {
            case Ways::COMBATIVENESS:
                $this->combativeness = $value;
                break;
            case Ways::CREATIVITY:
                $this->creativity = $value;
                break;
            case Ways::EMPATHY:
                $this->empathy = $value;
                break;
            case Ways::REASON:
                $this->reason = $value;
                break;
            case Ways::CONVICTION:
                $this->conviction = $value;
                break;
        }
    }

    public function setHealth(HealthCondition $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getHealth()
    {
        return $this->health;
    }

    public function setMaxHealth(HealthCondition $maxHealth): self
    {
        $this->maxHealth = $maxHealth;

        return $this;
    }

    /**
     * @return HealthCondition
     */
    public function getMaxHealth(): HealthCondition
    {
        return $this->maxHealth;
    }

    /**
     * @param int $stamina
     */
    public function setStamina($stamina): self
    {
        $this->stamina = $stamina;

        return $this;
    }

    /**
     * @return int
     */
    public function getStamina()
    {
        return $this->stamina;
    }

    /**
     * @return int
     */
    public function getStaminaBonus()
    {
        return $this->staminaBonus;
    }

    /**
     * @param int $staminaBonus
     */
    public function setStaminaBonus($staminaBonus)
    {
        $this->staminaBonus = $staminaBonus;
    }

    /**
     * @param int $survival
     */
    public function setSurvival($survival): self
    {
        $this->survival = $survival;

        return $this;
    }

    /**
     * @return int
     */
    public function getSurvival()
    {
        return $this->survival;
    }

    /**
     * @param int $speed
     */
    public function setSpeed($speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * @return int
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return int
     */
    public function getSpeedBonus()
    {
        return $this->speedBonus;
    }

    /**
     * @param int $speedBonus
     */
    public function setSpeedBonus($speedBonus): self
    {
        $this->speedBonus = $speedBonus;

        return $this;
    }

    /**
     * @param int $defense
     */
    public function setDefense($defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefense()
    {
        return $this->defense;
    }

    /**
     * @return int
     */
    public function getDefenseBonus()
    {
        return $this->defenseBonus;
    }

    /**
     * @param int $defenseBonus
     */
    public function setDefenseBonus($defenseBonus)
    {
        $this->defenseBonus = $defenseBonus;
    }

    /**
     * @param int $rindath
     */
    public function setRindath($rindath): self
    {
        $this->rindath = $rindath;

        return $this;
    }

    /**
     * @return int
     */
    public function getRindath()
    {
        return $this->rindath;
    }

    /**
     * @return int
     */
    public function getRindathMax()
    {
        return $this->rindathMax;
    }

    /**
     * @param int $rindathMax
     */
    public function setRindathMax($rindathMax): self
    {
        $this->rindathMax = $rindathMax;

        return $this;
    }

    /**
     * @param int $exaltation
     */
    public function setExaltation($exaltation): self
    {
        $this->exaltation = $exaltation;

        return $this;
    }

    /**
     * @return int
     */
    public function getExaltation()
    {
        return $this->exaltation;
    }

    /**
     * @return int
     */
    public function getExaltationMax()
    {
        return $this->exaltationMax;
    }

    /**
     * @param int $exaltationMax
     */
    public function setExaltationMax($exaltationMax): self
    {
        $this->exaltationMax = $exaltationMax;

        return $this;
    }

    /**
     * @param int $experienceActual
     */
    public function setExperienceActual($experienceActual): self
    {
        $this->experienceActual = $experienceActual;

        return $this;
    }

    /**
     * @return int
     */
    public function getExperienceActual()
    {
        return $this->experienceActual;
    }

    /**
     * @param int $experienceSpent
     */
    public function setExperienceSpent($experienceSpent): self
    {
        $this->experienceSpent = $experienceSpent;

        return $this;
    }

    /**
     * @return int
     */
    public function getExperienceSpent()
    {
        return $this->experienceSpent;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $deleted
     */
    public function setDeleted(\DateTime $deleted = null): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param Peoples $people
     */
    public function setPeople(Peoples $people = null): self
    {
        $this->people = $people;

        return $this;
    }

    /**
     * @return Peoples
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * @param Armors $armor
     */
    public function addArmor(Armors $armor): self
    {
        $this->armors[] = $armor;

        return $this;
    }

    /**
     * @param Armors $armor
     */
    public function removeArmor(Armors $armor): self
    {
        $this->armors->removeElement($armor);

        return $this;
    }

    /**
     * @return Armors[]
     */
    public function getArmors()
    {
        return $this->armors;
    }

    /**
     * @param Artifacts $artifact
     */
    public function addArtifact(Artifacts $artifact): self
    {
        $this->artifacts[] = $artifact;

        return $this;
    }

    /**
     * @param Artifacts $artifact
     */
    public function removeArtifact(Artifacts $artifact): self
    {
        $this->artifacts->removeElement($artifact);

        return $this;
    }

    /**
     * @return Artifacts[]
     */
    public function getArtifacts()
    {
        return $this->artifacts;
    }

    /**
     * @param Miracles $miracle
     */
    public function addMiracle(Miracles $miracle): self
    {
        $this->miracles[] = $miracle;

        return $this;
    }

    /**
     * @param Miracles $miracle
     */
    public function removeMiracle(Miracles $miracle): self
    {
        $this->miracles->removeElement($miracle);

        return $this;
    }

    /**
     * @return Miracles[]
     */
    public function getMiracles()
    {
        return $this->miracles;
    }

    /**
     * @param Ogham $ogham
     */
    public function addOgham(Ogham $ogham): self
    {
        $this->ogham[] = $ogham;

        return $this;
    }

    /**
     * @param Ogham $ogham
     */
    public function removeOgham(Ogham $ogham): self
    {
        $this->ogham->removeElement($ogham);

        return $this;
    }

    /**
     * @return Ogham[]
     */
    public function getOgham()
    {
        return $this->ogham;
    }

    /**
     * @param Weapons $weapon
     */
    public function addWeapon(Weapons $weapon): self
    {
        $this->weapons[] = $weapon;

        return $this;
    }

    /**
     * @param Weapons $weapon
     */
    public function removeWeapon(Weapons $weapon): self
    {
        $this->weapons->removeElement($weapon);

        return $this;
    }

    /**
     * @return Weapons[]
     */
    public function getWeapons()
    {
        return $this->weapons;
    }

    /**
     * @param CombatArts $combatArt
     */
    public function addCombatArt(CombatArts $combatArt): self
    {
        $this->combatArts[] = $combatArt;

        return $this;
    }

    /**
     * @param CombatArts $combatArt
     */
    public function removeCombatArt(CombatArts $combatArt): self
    {
        $this->combatArts->removeElement($combatArt);

        return $this;
    }

    /**
     * @return CombatArts[]
     */
    public function getCombatArts()
    {
        return $this->combatArts;
    }

    /**
     * @param SocialClasses $socialClass
     */
    public function setSocialClass(SocialClasses $socialClass = null): self
    {
        $this->socialClass = $socialClass;

        return $this;
    }

    /**
     * @return SocialClasses
     */
    public function getSocialClass()
    {
        return $this->socialClass;
    }

    /**
     * @param Domains $socialClassDomain1
     */
    public function setSocialClassDomain1(Domains $socialClassDomain1 = null): self
    {
        $this->socialClassDomain1 = $socialClassDomain1;

        return $this;
    }

    /**
     * @return Domains
     */
    public function getSocialClassDomain1()
    {
        return $this->socialClassDomain1;
    }

    /**
     * @param Domains $socialClassDomain2
     */
    public function setSocialClassDomain2(Domains $socialClassDomain2 = null): self
    {
        $this->socialClassDomain2 = $socialClassDomain2;

        return $this;
    }

    /**
     * @return Domains
     */
    public function getSocialClassDomain2()
    {
        return $this->socialClassDomain2;
    }

    /**
     * @param Disorders $mentalDisorder
     */
    public function setMentalDisorder(Disorders $mentalDisorder = null): self
    {
        $this->mentalDisorder = $mentalDisorder;

        return $this;
    }

    /**
     * @return Disorders
     */
    public function getMentalDisorder()
    {
        return $this->mentalDisorder;
    }

    /**
     * @param Jobs $job
     */
    public function setJob(Jobs $job = null): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Jobs
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param Zones $birthPlace
     */
    public function setBirthPlace(Zones $birthPlace = null): self
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    /**
     * @return Zones
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * @param Traits $flaw
     */
    public function setFlaw(Traits $flaw = null): self
    {
        $this->flaw = $flaw;

        return $this;
    }

    /**
     * @return Traits
     */
    public function getFlaw()
    {
        return $this->flaw;
    }

    /**
     * @param Traits $quality
     */
    public function setQuality(Traits $quality = null): self
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * @return Traits
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param CharAdvantages $advantage
     */
    public function addCharAdvantage(CharAdvantages $advantage): self
    {
        $this->charAdvantages[] = $advantage;

        return $this;
    }

    /**
     * @param CharAdvantages $advantage
     */
    public function removeCharAdvantage(CharAdvantages $advantage): self
    {
        $this->charAdvantages->removeElement($advantage);

        return $this;
    }

    /**
     * @return CharAdvantages[]
     */
    public function getCharAdvantages()
    {
        return $this->charAdvantages;
    }

    /**
     * @param CharDomains $domain
     */
    public function addDomain(CharDomains $domain): self
    {
        $this->domains[] = $domain;

        return $this;
    }

    /**
     * @param CharDomains $domain
     */
    public function removeDomain(CharDomains $domain): self
    {
        $this->domains->removeElement($domain);

        return $this;
    }

    /**
     * @return CharDomains[]
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @param CharDisciplines $discipline
     */
    public function addDiscipline(CharDisciplines $discipline): self
    {
        $this->disciplines[] = $discipline;

        return $this;
    }

    /**
     * @param CharDisciplines $discipline
     */
    public function removeDiscipline(CharDisciplines $discipline): self
    {
        $this->disciplines->removeElement($discipline);

        return $this;
    }

    /**
     * @return CharDisciplines[]
     */
    public function getDisciplines()
    {
        return $this->disciplines;
    }

    /**
     * @param CharFlux $flux
     */
    public function addFlux(CharFlux $flux): self
    {
        $this->flux[] = $flux;

        return $this;
    }

    /**
     * @param CharFlux $flux
     */
    public function removeFlux(CharFlux $flux): self
    {
        $this->flux->removeElement($flux);

        return $this;
    }

    /**
     * @return CharFlux[]
     */
    public function getFlux()
    {
        return $this->flux;
    }

    /**
     * @param CharSetbacks $setback
     */
    public function addSetback(CharSetbacks $setback): self
    {
        $this->setbacks[] = $setback;

        return $this;
    }

    /**
     * @param CharSetbacks $setback
     */
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

    /**
     * @param \User\Entity\User $user
     */
    public function setUser(\User\Entity\User $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \User\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setGame(Games $game = null): self
    {
        $this->game = $game;

        return $this;
    }

    public function getGame(): ?Games
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
     *
     * @return string
     */
    public function getConsciousness(): string
    {
        return $this->reason + $this->conviction;
    }

    /**
     * Conscience is determined by "Creativity" and "Combativity" ways.
     *
     * @return string
     */
    public function getInstinct(): string
    {
        return $this->creativity + $this->combativeness;
    }

    /**
     * Get a domain based on its id or name.
     *
     * @param int|string $id
     *
     * @return CharDomains|null
     */
    public function getDomain($id): ?CharacterProperties\CharDomains
    {
        foreach ($this->domains as $charDomain) {
            $domain = $charDomain->getDomain();
            if (
                $charDomain instanceof CharDomains &&
                (($domain->getId() === (int) $id) || $domain->getName() === $id)
            ) {
                return $charDomain;
            }
        }

        return null;
    }

    /**
     * @param int|Domains $domain
     *
     * @return CharDisciplines[]
     */
    public function getDisciplineFromDomain($domain): array
    {
        if ($domain instanceof Domains) {
            $domain = $domain->getId();
        }

        $disciplines = [];

        foreach ($this->disciplines as $discipline) {
            if ($discipline->getDomain()->getId() === $domain) {
                $disciplines[] = $discipline;
            }
        }

        return $disciplines;
    }

    /**
     * @param int|string $id
     */
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
     *
     * @return int
     */
    public function getBaseDefense(): int
    {
        $rai = $this->reason;
        $emp = $this->empathy;

        return $rai + $emp + 5;
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
     *
     * @return int
     */
    public function getBaseSpeed(): int
    {
        $com = $this->combativeness;
        $emp = $this->empathy;

        return $com + $emp;
    }

    /**
     * @param string $attitude
     *
     * @return int|null
     */
    public function getTotalSpeed($attitude = self::COMBAT_ATTITUDE_STANDARD): ?int
    {
        $this->validateCombatAttitude($attitude);

        $speed = $this->getBaseSpeed() + $this->speed + $this->speedBonus;

        if (self::COMBAT_ATTITUDE_QUICK === $attitude) {
            $speed += $this->getPotential();
        }

        return $speed;
    }

    /**
     * Base mental resistance is calculated from "Conviction".
     *
     * @return int
     */
    public function getBaseMentalResistance(): int
    {
        $ide = $this->conviction;

        return $ide + 5;
    }

    /**
     * @return int
     */
    public function getTotalMentalResistance(): int
    {
        return $this->getBaseMentalResistance() + $this->mentalResistance + $this->mentalResistanceBonus;
    }

    /**
     * @return int
     *
     * @throws CharactersException
     */
    public function getPotential(): ?int
    {
        $creativity = $this->creativity;

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

    /**
     * Calculate melee attack score.
     *
     * @param int|string $discipline
     * @param string     $potentialOperator Can be "+" or "-"
     *
     * @return int
     */
    public function getMeleeAttackScore($discipline = null, $potentialOperator = ''): int
    {
        return $this->getAttackScore('melee', $discipline, $potentialOperator);
    }

    /**
     * Retourne le score de base du type de combat spécifié dans $type.
     * Si $discipline est mentionné, il doit s'agir d'un identifiant valide de discipline,.
     *
     * @param string     $type
     * @param int|string $discipline
     * @param string     $attitude
     *
     * @throws CharactersException
     *
     * @return int
     */
    public function getAttackScore($type = 'melee', $discipline = null, $attitude = self::COMBAT_ATTITUDE_STANDARD): int
    {
        $this->validateCombatAttitude($attitude);

        // Récupération du score de voie
        $way = $this->combativeness;

        // Définition de l'id des domaines "Combat au contact" et "Tir & lancer"
        if ('melee' === $type) {
            $domain_id = 2;
        } elseif ('ranged' === $type) {
            $domain_id = 14;
        } else {
            throw new CharactersException('Attack can only be "melee" or "ranged".');
        }

        $domain_id = (int) $domain_id;

        // Récupération du score du domaine
        $domain = $this->getDomain($domain_id)->getScore();

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
     *
     * @return bool
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
     *
     * @return bool
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

    /**
     * @param string $attitude
     */
    private function validateCombatAttitude(string $attitude): void
    {
        if (!\in_array($attitude, self::COMBAT_ATTITUDES, true)) {
            throw new \InvalidArgumentException("Combat attitude is invalid, $attitude given.");
        }
    }
}

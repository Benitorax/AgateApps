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

use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Entity\Traits\HasBook;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Setbacks.
 *
 * @ORM\Table(name="setbacks")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\SetbacksRepository")
 */
class Setbacks
{
    use HasBook;

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
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $malus;

    /**
     * It can disable either advantages or disadvantages, as they're in the same table.
     *
     * @var string[]
     *
     * @ORM\ManyToMany(targetEntity="CharacterAlteration")
     * @ORM\JoinTable(
     *     name="setbacks_advantages",
     *     joinColumns={@ORM\JoinColumn(name="setback_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="advantage_id", referencedColumnName="id")},
     * )
     */
    private $disabledAdvantages;

    public function __construct()
    {
        $this->disabledAdvantages = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Setbacks
     *
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Setbacks
     *
     * @codeCoverageIgnore
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set malus.
     *
     * @param string $malus
     *
     * @return Setbacks
     *
     * @codeCoverageIgnore
     */
    public function setMalus($malus)
    {
        if ($malus) {
            Bonuses::validateBonus($malus);
        }

        $this->malus = $malus;

        return $this;
    }

    /**
     * Get malus.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getMalus()
    {
        return $this->malus;
    }

    /**
     * @return CharacterAlteration[]|ArrayCollection
     */
    public function getDisabledAdvantages()
    {
        return $this->disabledAdvantages;
    }

    public function setDisabledAdvantages(array $disabledAdvantages): void
    {
        foreach ($disabledAdvantages as $disabledAdvantage) {
            $this->addDisabledAdvantage($disabledAdvantage);
        }
    }

    public function addDisabledAdvantage(CharacterAlteration $disabledAdvantage): void
    {
        if (!$this->disabledAdvantages->contains($disabledAdvantage)) {
            $this->disabledAdvantages[] = $disabledAdvantage;
        }
    }

    public function removeDisabledAdvantage(CharacterAlteration $disabledAdvantage): void
    {
        $this->disabledAdvantages->removeElement($disabledAdvantage);
    }
}

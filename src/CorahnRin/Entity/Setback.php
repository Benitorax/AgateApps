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

use CorahnRin\Entity\Traits\HasBook;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="setbacks")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\SetbacksRepository")
 */
class Setback
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
     * Unlucky means that we pick another setback after picking this one.
     *
     * @var bool
     *
     * @ORM\Column(name="is_unlucky", type="boolean", options={"default" = "0"})
     */
    private $isUnlucky = false;

    /**
     * Lucky means we pick another setback and it'll be marked as "avoided".
     *
     * @var bool
     *
     * @ORM\Column(name="is_lucky", type="boolean", options={"default" = "0"})
     */
    private $isLucky = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, options={"default" = ""})
     */
    protected $malus = '';

    /**
     * It can disable either advantages or disadvantages, as they're in the same table.
     *
     * @var Advantage[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Advantage")
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isUnlucky(): bool
    {
        return $this->isUnlucky;
    }

    public function setIsUnlucky(bool $isUnlucky): void
    {
        $this->isUnlucky = $isUnlucky;
    }

    public function isLucky(): bool
    {
        return $this->isLucky;
    }

    public function setIsLucky(bool $isLucky): void
    {
        $this->isLucky = $isLucky;
    }

    public function getMalus(): string
    {
        return $this->malus;
    }

    public function setMalus(string $malus): void
    {
        $this->malus = $malus;
    }

    /**
     * @return Advantage[]|ArrayCollection
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

    public function addDisabledAdvantage(Advantage $disabledAdvantage): void
    {
        if (!$this->disabledAdvantages->contains($disabledAdvantage)) {
            $this->disabledAdvantages[] = $disabledAdvantage;
        }
    }

    public function removeDisabledAdvantage(Advantage $disabledAdvantage): void
    {
        $this->disabledAdvantages->removeElement($disabledAdvantage);
    }
}

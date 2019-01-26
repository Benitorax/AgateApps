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

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\Character;
use CorahnRin\Entity\Discipline;
use Doctrine\ORM\Mapping as ORM;

/**
 * CharDisciplines.
 *
 * @ORM\Table(name="characters_disciplines")
 * @ORM\Entity
 */
class CharDisciplines
{
    /**
     * @var Character
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Character", inversedBy="disciplines")
     */
    protected $character;

    /**
     * @var Discipline
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Discipline")
     */
    protected $discipline;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=100)
     */
    protected $domain;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $score;

    public function __construct(Character $character, Discipline $discipline, string $domain, int $score)
    {
        DomainsData::validateDomain($domain);

        $this->character = $character;
        $this->discipline = $discipline;
        $this->domain = $domain;
        $this->updateScore($score);
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getDiscipline(): Discipline
    {
        return $this->discipline;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function updateScore(int $score): void
    {
        if ($score < 6) {
            throw new \RuntimeException('Discipline score must be at least 6.');
        }

        if ($score > 15) {
            throw new \RuntimeException('Discipline score cannot exceed 15.');
        }

        $this->score = $score;
    }
}

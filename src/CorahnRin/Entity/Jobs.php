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

use CorahnRin\Data\Domains;
use CorahnRin\Entity\Traits\HasBook;
use Doctrine\ORM\Mapping as ORM;

/**
 * Jobs.
 *
 * @ORM\Table(name="jobs")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\JobsRepository")
 */
class Jobs
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
     * @ORM\Column(type="string", length=140, nullable=false, unique=true)
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
     * @ORM\Column(name="daily_salary", type="integer", options={"default" = "0"})
     */
    protected $dailySalary = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="primary_domain", type="string", length=100)
     */
    protected $primaryDomain;

    /**
     * @var string[]
     *
     * @ORM\Column(name="secondary_domains", type="simple_array", nullable=true)
     */
    protected $secondaryDomains = [];

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

    public function getDailySalary(): int
    {
        return $this->dailySalary;
    }

    public function setDailySalary(int $dailySalary): void
    {
        $this->dailySalary = $dailySalary;
    }

    public function getPrimaryDomain(): string
    {
        return $this->primaryDomain;
    }

    public function setPrimaryDomain(string $primaryDomain): void
    {
        $this->primaryDomain = $primaryDomain;
    }

    public function getSecondaryDomains(): array
    {
        return $this->secondaryDomains;
    }

    public function setSecondaryDomains(array $secondaryDomains): void
    {
        $this->secondaryDomains = $secondaryDomains;
    }

    public function addSecondaryDomain(string $domain): void
    {
        Domains::validateDomain($domain);

        $this->secondaryDomains[] = $domain;
    }

    public function removeSecondaryDomain(string $domain): void
    {
        Domains::validateDomain($domain);

        if (!\in_array($domain, $this->secondaryDomains, true)) {
            throw new \InvalidArgumentException(sprintf('Current social class does not have specified domain %s', $domain));
        }

        unset($this->secondaryDomains[array_search($domain, $this->secondaryDomains)]);

        $this->secondaryDomains = \array_values($this->secondaryDomains);
    }

    public function hasSecondaryDomain(string $domain): bool
    {
        Domains::validateDomain($domain);

        return !\in_array($domain, $this->secondaryDomains, true);
    }
}

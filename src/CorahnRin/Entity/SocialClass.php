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

use CorahnRin\Data\DomainsData;
use Doctrine\ORM\Mapping as ORM;

/**
 * SocialClass.
 *
 * @ORM\Table(name="social_class")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\SocialClassRepository")
 */
class SocialClass
{
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
     * @ORM\Column(type="string", length=25, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string[]
     *
     * @ORM\Column(name="domains", type="simple_array")
     */
    protected $domains = [];

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

    public function getDomains(): array
    {
        return $this->domains;
    }

    public function setDomains(array $domains): void
    {
        foreach ($domains as $domain) {
            $this->addDomain($domain);
        }
    }

    public function addDomain(string $domain): void
    {
        DomainsData::validateDomain($domain);

        $this->domains[] = $domain;
    }

    public function removeDomain(string $domain): void
    {
        DomainsData::validateDomain($domain);

        if (!\in_array($domain, $this->domains, true)) {
            throw new \InvalidArgumentException(\sprintf('Current social class does not have specified domain %s', $domain));
        }

        unset($this->domains[\array_search($domain, $this->domains, true)]);

        $this->domains = \array_values($this->domains);
    }

    public function hasDomain(string $domain): bool
    {
        DomainsData::validateDomain($domain);

        return \in_array($domain, $this->domains, true);
    }
}

<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Voucher\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Voucher\Repository\VoucherRepository")
 * @ORM\Table(name="vouchers")
 *
 * @UniqueEntity("uniqueCode", errorPath="uniqueCode")
 */
class Voucher
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="unique_code", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank
     */
    private $uniqueCode;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Choice(Voucher\Data\VoucherType::TYPES)
     */
    private $type;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="valid_from", type="date_immutable")
     *
     * @Assert\DateTime
     * @Assert\GreaterThanOrEqual("today")
     */
    protected $validFrom;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="valid_until", type="date_immutable", nullable=true)
     *
     * @Assert\DateTime
     * @Assert\GreaterThanOrEqual(propertyPath="validFrom")
     */
    protected $validUntil;

    /**
     * 0 is similar to "infinite number of times".
     *
     * @var int
     *
     * @ORM\Column(name="max_number_of_uses", type="integer")
     *
     * @Assert\Range(min="0")
     */
    protected $maxNumberOfUses = 0;

    public static function create(
        string $type,
        string $uniqueCode,
        \DateTimeImmutable $validFrom,
        \DateTimeImmutable $validUntil = null,
        int $maxNumberOfUses = 0
    ): self {
        $object = new self();

        $object->uniqueCode = $uniqueCode;
        $object->setType($type);
        $object->validFrom = $validFrom;
        $object->validUntil = $validUntil;
        $object->maxNumberOfUses = $maxNumberOfUses;

        return $object;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUniqueCode(): string
    {
        return $this->uniqueCode;
    }

    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil;
    }

    public function getMaxNumberOfUses(): int
    {
        return $this->maxNumberOfUses;
    }

    private function setType(string $type): void
    {
        if (!\in_array($type, \Voucher\Data\VoucherType::TYPES, true)) {
            throw new \InvalidArgumentException(\sprintf('Invalid voucher type "%s"', $type));
        }

        $this->type = $type;
    }
}

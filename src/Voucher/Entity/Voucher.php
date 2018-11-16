<?php

namespace Voucher\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Voucher\Repository\VoucherRepository")
 * @ORM\Table(name="vouchers")
 *
 * @UniqueEntity("uniqueCode")
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
     * @Assert\NotBlank()
     */
    private $uniqueCode;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(Voucher\VoucherType::TYPES)
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
     * @Assert\GreaterThanOrEqual("tomorrow")
     * @Assert\GreaterThanOrEqual(propertyPath="startsAt")
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

    public static function createBasic(
        string $type,
        string $uniqueCode,
        \DateTimeImmutable $validFrom
    ): self {
        $object = new self();

        $object->uniqueCode = $uniqueCode;
        $object->setType($type);
        $object->validFrom = $validFrom;

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
        if (!\in_array($type, \Voucher\VoucherType::TYPES, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid voucher type "%s"', $type));
        }

        $this->type = $type;
    }
}

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
use User\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Voucher\Repository\RedeemedVoucherRepository")
 * @ORM\Table(name="used_vouchers")
 */
class RedeemedVoucher
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
     * @var Voucher
     *
     * @ORM\ManyToOne(targetEntity="Voucher\Entity\Voucher")
     * @ORM\JoinColumn(name="voucher_id", nullable=false)
     */
    private $voucher;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $user;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="redeemed_at", type="date_immutable")
     */
    protected $redeemedAt;

    private function __construct()
    {
    }

    public static function create(Voucher $voucher, User $user): self
    {
        $object = new self();

        $object->voucher = $voucher;
        $object->user = $user;
        $object->redeemedAt = new \DateTimeImmutable();

        return $object;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoucher(): Voucher
    {
        return $this->voucher;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRedeemedAt(): \DateTimeInterface
    {
        return $this->redeemedAt;
    }
}

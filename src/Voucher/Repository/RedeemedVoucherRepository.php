<?php

namespace Voucher\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use User\Entity\User;
use Voucher\Entity\RedeemedVoucher;
use Voucher\Entity\Voucher;

class RedeemedVoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedeemedVoucher::class);
    }

    public function getNumberOfVouchersUsedForType(string $type): int
    {
        return $this->createQueryBuilder('redeemed_voucher')
            ->select('COUNT(redeemed_voucher.id) as number_of_vouchers')
            ->leftJoin('redeemed_voucher.voucher', 'voucher')
            ->where('voucher.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return RedeemedVoucher[]|array
     */
    public function findByVoucherAndUser(Voucher $voucher, User $user)
    {
        return $this->createQueryBuilder('redeemed_voucher')
            ->where('redeemed_voucher.voucher = :voucher')
            ->andWhere('redeemed_voucher.user = :user')
            ->setParameters([
                'voucher' => $voucher,
                'user' => $user,
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}
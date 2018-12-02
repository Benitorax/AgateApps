<?php

namespace Voucher\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Subscription\Entity\Subscription;
use Subscription\Mailer\SubscriptionMailer;
use Subscription\SubscriptionType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use User\Entity\User;
use Voucher\Data\VoucherType;
use Voucher\Entity\RedeemedVoucher;
use Voucher\Entity\Voucher;
use Voucher\Exception\SaveError;
use Voucher\Exception\StopRedeemPropagation;

class EsterenMapsVoucherHandler implements VoucherHandlerInterface
{
    private $em;
    private $mailer;
    private $session;

    public static function getPriority(): int
    {
        return 10;
    }

    public function __construct(EntityManagerInterface $em, SubscriptionMailer $mailer, FlashBagInterface $session)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->session = $session;
    }

    public function supports(Voucher $voucher, User $user): bool
    {
        return VoucherType::ESTEREN_MAPS === $voucher->getType();
    }

    public function handle(Voucher $voucher, User $user): void
    {
        try {
            $redeemedVoucher = RedeemedVoucher::create($voucher, $user);
            $subscription = Subscription::create(
                $user,
                SubscriptionType::ESTEREN_MAPS,
                new \DateTimeImmutable('+90 days')
            );

            $this->em->persist($redeemedVoucher);
            $this->em->persist($subscription);

            $this->em->flush();

            $this->mailer->sendNewSubscriptionEmail($subscription);

            $this->session->add('success', 'subscription.esteren_maps.used_voucher');
        } catch (\Throwable $previous) {
            throw new SaveError($voucher, $user, $previous);
        }

        throw new StopRedeemPropagation();
    }
}

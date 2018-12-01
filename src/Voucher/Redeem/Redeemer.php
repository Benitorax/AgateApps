<?php

namespace Voucher\Redeem;

use User\Entity\User;
use Voucher\Entity\Voucher;
use Voucher\Exception\NoHandlerForVoucherAndUser;
use Voucher\Exception\RedeemExceptionInterface;
use Voucher\Exception\StopRedeemPropagation;
use Voucher\Handler\VoucherHandlerInterface;

final class Redeemer
{
    /**
     * @var VoucherHandlerInterface[]
     */
    private $handlers = [];

    public function __construct(iterable $handlers)
    {
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }

        $this->sortHandlers();
    }

    /**
     * @return int the number of handlers that were executed
     *
     * @throws RedeemExceptionInterface
     */
    public function redeem(Voucher $voucher, User $user): int
    {
        $handled = 0;

        try {
            foreach ($this->handlers as $handler) {
                if ($handler->supports($voucher, $user)) {
                    $handled++;
                    $handler->handle($voucher, $user);
                }
            }
        } catch (StopRedeemPropagation $e) {
            // Stop silently
        }

        if (!$handled) {
            throw new NoHandlerForVoucherAndUser($voucher, $user);
        }

        return $handled;
    }

    private function addHandler(VoucherHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    private function sortHandlers(): void
    {
        $handlers = $this->handlers;

        \usort($handlers, function (
            VoucherHandlerInterface $handler1,
            VoucherHandlerInterface $handler2
        ) {
            return $handler2->getPriority() <=> $handler1->getPriority();
        });

        $this->handlers = $handlers;
    }
}

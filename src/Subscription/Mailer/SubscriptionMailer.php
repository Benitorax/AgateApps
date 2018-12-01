<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Subscription\Mailer;

use Subscription\Entity\Subscription;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

final class SubscriptionMailer
{
    private $twig;
    private $mailer;
    private $translator;
    private $requestContext;
    private $agateDomain;

    public function __construct(
        RequestContext $requestContext,
        \Swift_Mailer $mailer,
        Environment $twig,
        TranslatorInterface $translator,
        string $agateDomain
    ) {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->requestContext = $requestContext;
        $this->agateDomain = $agateDomain;
    }

    public function sendNewSubscriptionEmail(Subscription $subscription)
    {
        $user = $subscription->getUser();

        $type = $subscription->getType();

        if ('localhost' === $this->requestContext->getHost()) {
            // Means we're in CLI
            $this->requestContext->setHost($this->agateDomain);
        }

        $rendered = $this->twig->render("user/subscription/email.new.$type.html.twig", [
            'subscription' => $subscription,
        ]);

        $message = new \Swift_Message();

        $message
            ->setSubject($this->translator->trans("subscription.email.$type.subject", [], 'user'))
            ->setFrom('no-reply@'.$this->requestContext->getHost())
            ->setContentType('text/html')
            ->setTo($user->getEmail())
            ->setBody($rendered)
        ;

        $this->mailer->send($message);
    }
}

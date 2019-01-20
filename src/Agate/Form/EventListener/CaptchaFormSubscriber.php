<?php

declare(strict_types=1);

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Agate\Form\EventListener;

use ReCaptcha\ReCaptcha;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;

/**
 * This class must not be autoconfigured in the Container,
 * because it is used only by Forms,
 * not the global EventDispatcher.
 */
class CaptchaFormSubscriber implements EventSubscriberInterface
{
    private $enableCaptcha;
    private $reCaptcha;
    private $request;

    public function __construct(bool $enableCaptcha, ReCaptcha $reCaptcha, Request $request)
    {
        $this->reCaptcha = $reCaptcha;
        $this->request = $request;
        $this->enableCaptcha = $enableCaptcha;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => ['onFormSubmit'],
        ];
    }

    public function onFormSubmit(FormEvent $event): void
    {
        if (!$this->enableCaptcha) {
            return;
        }

        $captcha = $this->request->request->get('g-recaptcha-response');

        if (
            !$captcha
            ||
            ($captcha && false === $this->reCaptcha->verify($captcha, $this->request->getClientIp())->isSuccess())
        ) {
            $event->getForm()->addError(new FormError('Invalid form values, please check'));
        }
    }
}

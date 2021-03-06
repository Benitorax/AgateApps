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

namespace Agate\Mailer;

use Agate\Model\ContactMessage;
use Twig\Environment;

final class PortalMailer
{
    private $twig;
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendContactMail(ContactMessage $message, string $subject): int
    {
        $swiftMessage = new \Swift_Message();

        $swiftMessage
            ->setSubject($subject)
            ->setContentType('text/html')
            ->setCharset('utf-8')
            ->setFrom($message->getEmail(), $message->getName())
            ->setTo('pierstoval+newportal@gmail.com')
            ->addCc('nelyhann+portal@gmail.com', 'Les Ombres d\'Esteren')
            ->addCc('iris@esteren.org', 'Iris D\'Automne')
            ->setBody($this->twig->render('agate/email/contact_email.html.twig', [
                'message' => $message,
            ]))
        ;

        return $this->mailer->send($swiftMessage);
    }
}

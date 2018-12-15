<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Agate\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use Tests\WebTestCase as PiersTestCase;

class ContactControllerTest extends WebTestCase
{
    use PiersTestCase;

    public function testValidContactForm()
    {
        $client = $this->getClient('www.studio-agate.docker', ['debug' => true]);

        $crawler = $client->request('GET', '/fr/contact');

        static::assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('#content .container form')->form();

        $data = [
            'name' => 'username',
            'subject' => 'contact.subject.application',
            'email' => 'test@local.host',
            'message' => 'a message for testing purpose',
            'title' => 'Some message title',
        ];

        $client->submit($form, [
            'contact' => $data,
        ]);

        // Enable the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $message = $client->getContainer()->get('translator')->trans('contact.form.message_sent', [], 'agate');

        static::assertSame($message, \trim($crawler->filter('#flash-messages div.card-panel.success')->text()));

        /** @var MessageDataCollector $mailCollector */
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $collectedMessages = $mailCollector->getMessages();

        // FIXME
        static::assertGreaterThanOrEqual(1, \count($collectedMessages));

        /** @var \Swift_Message $message */
        $message = $collectedMessages[0];

        // Asserting email data
        static::assertInstanceOf(\Swift_Message::class, $message);
        static::assertSame($data['email'], \key($message->getFrom()));
        static::assertSame('[Candidature] Some message title', $message->getSubject());
        static::assertContains($data['message'], $message->getBody());

        if (\count($collectedMessages) > 1) {
            $this->markAsRisky();
        }
    }
}

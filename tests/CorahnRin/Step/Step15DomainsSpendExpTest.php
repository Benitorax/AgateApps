<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\CorahnRin\Step;

class Step15DomainsSpendExpTest extends AbstractStepTest
{
    use StepsWithDomainsTrait;

    public function testStepDependency()
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', []); // Varigal
        $session->save();

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $errorMessage = $crawler->filter('head title')->text();

        static::assertSame(302, $client->getResponse()->getStatusCode(), $errorMessage);
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    public function testStep()
    {
        static::markTestIncomplete();
    }
}

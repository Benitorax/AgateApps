<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\CorahnRinBundle\Tests\Step;

class Step14UseDomainBonusesTest extends AbstractStepTest
{
    use StepsWithDomainsTrait;

    /**
     * @dataProvider provideInvalidDependencies
     */
    public function testStepDependency($dependencies)
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character', $dependencies); // Varigal
        $session->save();

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $errorMessage = $crawler->filter('head title')->text();

        static::assertSame(302, $client->getResponse()->getStatusCode(), $errorMessage);
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    public function provideInvalidDependencies()
    {
        return [
            [[]],
            [['02_job' => 1]],
            [['08_ways' => [1, 2, 3, 4, 5]]],
            [['11_advantages' => []]],
            [['02_job' => 1, '08_ways' => [1, 2, 3, 4, 5]]],
            [['11_advantages' => [], '08_ways' => [1, 2, 3, 4, 5]]],
            [['13_primary_domains' => ['domains' => [],'ost' => 2,'scholar' => null]]],
        ];
    }

    public function testNoBonusesSpent()
    {
        $client = $this->getClientWithRequirements($this->getValidRequirements());

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('#generator_form')->form();

        $client->submit($form);

        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/15_domains_spend_exp'));
        $this->assertSessionEquals([], 1, $client);
    }

    public function testPrimaryDomainThrowsError()
    {
        $client = $this->getClientWithRequirements($this->getValidRequirements());

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('#generator_form')->form();

        $form['domains_bonuses[1]'] = 1;

        $crawler = $client->submit($form);

        // Redirection means error
        static::assertSame(200, $client->getResponse()->getStatusCode());
        $flashMessages = $crawler->filter('#flash-messages') ?: '';
        static::assertContains('Certaines valeurs envoyées sont incorrectes, veuillez recommencer (et sans tricher).', $flashMessages ? trim($flashMessages->text()) : '');
    }

    public function testStep()
    {
        static::markTestIncomplete();
    }
}

<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\CorahnRin\Step;

class Step05SocialClassTest extends AbstractStepTest
{
    public function testValidSocialClass()
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 1,
            'domains' => ['domains.natural_environment', 'domains.perception'],
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/06_age'));
        static::assertSame([$this->getStepName() => [
            'id' => 1,
            'domains' => ['domains.natural_environment', 'domains.perception'],
        ]], $result->getSession()->get('character.corahn_rin'));
    }

    public function testInvalidSocialClass()
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 0,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('#flash-messages > .card-panel.error')->count());
        static::assertEquals('Veuillez sélectionner une classe sociale valide.', \trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testValidSocialClassButNotAssociatedDomains()
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 1,
            'domains' => ['domains.relation', 'domains.erudition'],
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('#flash-messages > .card-panel.error')->count());
        static::assertEquals('Les domaines choisis ne sont pas associés à la classe sociale sélectionnée.', \trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testValidSocialClassButNotEnoughDomains()
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 1,
            'domains' => ['domains.natural_environment'],
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('#flash-messages > .card-panel.warning')->count());
        static::assertEquals('Vous devez choisir 2 domaines pour lesquels vous obtiendrez un bonus de +1. Ces domaines doivent être choisi dans la classe sociale sélectionnée.', \trim($crawler->filter('#flash-messages > .card-panel.warning')->text()));
    }
}

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

class Step03BirthplaceTest extends AbstractStepTest
{
    public function testValidBirthplace()
    {
        $result = $this->submitAction([], [
            'region_value' => 1,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/04_geo'));
        static::assertSame([$this->getStepName() => 1], $result->getSession()->get('character.corahn_rin'));
    }

    public function testInvalidBirthplace()
    {
        $result = $this->submitAction([], [
            'region_value' => 0,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode(), $crawler->filter('title')->text());
        static::assertSame(1, $crawler->filter('#flash-messages > .card-panel.error')->count());
        static::assertEquals('Veuillez choisir une région de naissance correcte.', \trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }
}

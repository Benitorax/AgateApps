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

class Step06AgeTest extends AbstractStepTest
{
    public function testValidAge()
    {
        $result = $this->submitAction([], [
            'age' => 16,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/07_setbacks'));
        static::assertSame([$this->getStepName() => 16], $result->getSession()->get('character.corahn_rin'));
    }

    public function testInvalidAge()
    {
        $result = $this->submitAction([], [
            'age' => 0,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('#flash-messages > .card-panel.error')->count());
        static::assertEquals('L\'âge doit être compris entre 16 et 35 ans.', trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }
}

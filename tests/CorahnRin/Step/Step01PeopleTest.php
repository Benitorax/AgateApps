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

class Step01PeopleTest extends AbstractStepTest
{
    public function testValidPeople()
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 1,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/02_job'));
        static::assertSame([$this->getStepName() => 1], $result->getSession()->get('character.corahn_rin'));
    }

    public function testInvalidPeople()
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 0,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode(), $crawler->filter('title')->text());
        static::assertSame(1, $crawler->filter('#flash-messages > .card-panel.error')->count());
        static::assertEquals('Veuillez indiquer un peuple correct.', \trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }
}

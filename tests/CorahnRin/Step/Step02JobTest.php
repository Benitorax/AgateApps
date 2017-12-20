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

class Step02JobTest extends AbstractStepTest
{
    public function testValidJob()
    {
        $result = $this->submitAction([], [
            'job_value' => 1,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/03_birthplace'));
        static::assertSame([$this->getStepName() => 1], $result->getSession()->get('character'));
    }

    public function testInvalidJob()
    {
        $result = $this->submitAction([], [
            'job_value' => 0,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('#flash-messages > .card-panel.error')->count());
        static::assertEquals('Veuillez entrer un métier correct.', trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }
}

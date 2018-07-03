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

use CorahnRin\Data\Orientation;

class Step10OrientationTest extends AbstractStepTest
{
    public function testWaysDependency()
    {
        $client = $this->getClient();

        $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    public function testValidInstinctiveOrientation()
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 5,
                'ways.creativity' => 4,
                'ways.empathy' => 3,
                'ways.reason' => 2,
                'ways.conviction' => 1,
            ],
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/11_advantages'));
        static::assertSame(Orientation::INSTINCTIVE, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    public function testValidRationalOrientation()
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 1,
                'ways.creativity' => 2,
                'ways.empathy' => 3,
                'ways.reason' => 4,
                'ways.conviction' => 5,
            ],
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/11_advantages'));
        static::assertSame(Orientation::RATIONAL, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    public function testValidManualInstinctiveOrientation()
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 3,
                'ways.creativity' => 4,
                'ways.empathy' => 1,
                'ways.reason' => 4,
                'ways.conviction' => 3,
            ],
        ], $values = [
            'gen-div-choice' => Orientation::INSTINCTIVE,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/11_advantages'));
        static::assertSame(Orientation::INSTINCTIVE, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    public function testValidManualRationalOrientation()
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 3,
                'ways.creativity' => 4,
                'ways.empathy' => 1,
                'ways.reason' => 4,
                'ways.conviction' => 3,
            ],
        ], $values = [
            'gen-div-choice' => Orientation::RATIONAL,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/11_advantages'));
        static::assertSame(Orientation::RATIONAL, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    public function testInvalidManualOrientation()
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 3,
                'ways.creativity' => 4,
                'ways.empathy' => 1,
                'ways.reason' => 4,
                'ways.conviction' => 3,
            ],
        ], $values = [
            'gen-div-choice' => 'INVALID',
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertEquals('L\'orientation de la personnalité est incorrecte, veuillez vérifier.', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }
}

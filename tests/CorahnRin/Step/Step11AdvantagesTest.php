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

class Step11AdvantagesTest extends AbstractStepTest
{
    public function testValidAdvantagesStep()
    {
        $result = $this->submitAction([], [
            'advantages' => [
                3 => 1,
                8 => 1,
            ],
            'disadvantages' => [
                31 => 1,
                47 => 1,
                48 => 1,
            ],
            'advantages_indications' => [
                3 => 'Influent ally',
                48 => 'Some phobia',
            ],
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/12_mental_disorder'));
        static::assertSame([
            'advantages' => [
                3 => 1,
                8 => 1,
            ],
            'disadvantages' => [
                31 => 1,
                47 => 1,
                48 => 1,
            ],
            'advantages_indications' => [
                3 => 'Influent ally',
                48 => 'Some phobia',
            ],
            'remainingExp' => 100,
        ], $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    public function testTooMuchExpHasBeenSpent()
    {
        $result = $this->submitAction([], [
            'advantages' => [
                3  => 1,
                8  => 1,
                10 => 2,
                15 => 1,
            ],
            'disadvantages' => [],
            'advantages_indications' => [
                3 => 'Influent ally',
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vous n\'avez pas assez d\'expérience.', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testTooMuchExpHasBeenGained()
    {
        $result = $this->submitAction([], [
            'advantages'    => [],
            'disadvantages' => [
                44 => 1,
                46 => 2,
                48 => 1,
                50 => 3,
            ],
            'advantages_indications' => [
                48 => 'Some phobia',
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vos désavantages vous donnent un gain d\'expérience supérieur à 80.', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    /**
     * @dataProvider provideAllyTests
     */
    public function testCannotChoseAllyMultipleTimes($values, array $indications)
    {
        $result = $this->submitAction([], [
            'advantages'    => $values,
            'disadvantages' => [
                // This is to have enough exp to maybe buy them all
                43 => 1,
                44 => 1,
                48 => 1,
            ],
            'advantages_indications' => \array_merge($indications, [48 => 'Some phobia']),
        ]);

        $code = $result->getResponse()->getStatusCode();
        if (500 === $code) {
            $msg = $result->getCrawler()->filter('title')->text();
        } else {
            $msg = json_encode($result->getSession()->get('character.corahn_rin'));
        }
        static::assertSame(200, $code, $msg);
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vous ne pouvez pas combiner plusieurs avantages "Allié".', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function provideAllyTests()
    {
        // Test all "Ally" advantage possibilities so we're sure every case is covered
        return [
            [[1=>1, 2=>1, 3=>1], [3=>'Influent ally']],
            [[1=>1, 3=>1], [3=>'Influent ally']],
            [[1=>1, 2=>1], [3=>'Influent ally']],
            [[2=>1, 3=>1], [3=>'Influent ally']],
        ];
    }

    /**
     * @dataProvider provideFinancialEaseTests
     */
    public function testCannotChoseFinancialEaseMultipleTimes($values)
    {
        $result = $this->submitAction([], [
            'advantages'    => $values,
            'disadvantages' => [
                // This is to have enough exp to maybe buy them all
                43 => 1,
                44 => 1,
                48 => 1,
            ],
            'advantages_indications' => [
                48 => 'Some phobia',
            ],
        ]);

        $code = $result->getResponse()->getStatusCode();
        if (500 === $code) {
            $msg = $result->getCrawler()->filter('title')->text();
        } else {
            $msg = json_encode($result->getSession()->get('character.corahn_rin'));
        }
        static::assertSame(200, $code, $msg);
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vous ne pouvez pas combiner plusieurs avantages "Aisance financière".', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function provideFinancialEaseTests()
    {
        // Test all "FinancialEase" advantage possibilities so we're sure every case is covered
        return [
            // Four choices
            [[4=>1, 5=>1, 6=>1, 7=>1]],
            [[4=>1, 5=>1, 6=>1, 8=>1]],
            [[4=>1, 5=>1, 7=>1, 8=>1]],
            [[4=>1, 6=>1, 7=>1, 8=>1]],
            [[5=>1, 6=>1, 7=>1, 8=>1]],

            // Three choices
            [[4=>1, 5=>1, 6=>1]],
            [[4=>1, 5=>1, 7=>1]],
            [[4=>1, 6=>1, 7=>1]],
            [[5=>1, 6=>1, 7=>1]],
            [[4=>1, 5=>1, 8=>1]],
            [[4=>1, 6=>1, 8=>1]],
            [[5=>1, 6=>1, 8=>1]],
            [[4=>1, 7=>1, 8=>1]],
            [[5=>1, 7=>1, 8=>1]],
            [[6=>1, 7=>1, 8=>1]],

            // TODO: Create real truth table for this, it's a bit insane or now
        ];
    }

    public function testIncorrectAdvantageValue()
    {
        $result = $this->submitAction([], [
            'advantages' => [
                1 => 2,
            ],
            'disadvantages' => [],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Une valeur incorrecte a été donnée à un avantage.', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testIncorrectDisadvantageValue()
    {
        $result = $this->submitAction([], [
            'advantages'    => [],
            'disadvantages' => [
                48 => 2,
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Une valeur incorrecte a été donnée à un désavantage.', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testIncorrectAdvantageId()
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', []);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages' => [
                99999 => 1,
            ],
            'disadvantages' => [],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text());
        static::assertEquals('Les avantages soumis sont incorrects.', trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testIncorrectDisadvantageId()
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', []);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages'    => [],
            'disadvantages' => [
                99999 => 1,
            ],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text());
        static::assertEquals('Les désavantages soumis sont incorrects.', trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testCannotHaveMoreThan4Advantages()
    {
        $result = $this->submitAction([], [
            'advantages' => [
                1  => 1,
                4  => 1,
                19 => 1,
                29 => 1,
                30 => 1,
            ],
            'disadvantages' => [],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vous ne pouvez pas avoir plus de 4 avantages.', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testCannotHaveMoreThan4Disadvantages()
    {
        $result = $this->submitAction([], [
            'advantages'    => [],
            'disadvantages' => [
                41 => 1,
                43 => 1,
                47 => 1,
                49 => 1,
                51 => 1,
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vous ne pouvez pas avoir plus de 4 désavantages.', trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    /**
     * @dataProvider provideFinancialEaseForPoor
     */
    public function testPoorCannotUseFinancialEase($values)
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', ['07_setbacks' => [9 => ['id' => 9, 'avoided' => false]]]);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages'    => $values,
            'disadvantages' => [],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text());
        static::assertEquals('Vous ne pouvez pas choisir "Avantage financier" si votre personnage a le revers "Pauvre".', trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function provideFinancialEaseForPoor()
    {
        return [
            [[4 => 1]],
            [[5 => 1]],
            [[6 => 1]],
            [[7 => 1]],
            [[8 => 1]],
        ];
    }
}

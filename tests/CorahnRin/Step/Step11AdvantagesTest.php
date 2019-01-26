<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\CorahnRin\Step;

class Step11AdvantagesTest extends AbstractStepTest
{
    public function testValidAdvantagesStep(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
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
            'remainingExp' => 80,
        ], $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    public function testTooMuchExpHasBeenSpent(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [
                3 => 1,
                8 => 1,
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
        static::assertSame('Vous n\'avez pas assez d\'expérience.', \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testTooMuchExpHasBeenGained(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [],
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

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vos désavantages vous donnent un gain d\'expérience supérieur à 80.', \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    /**
     * @dataProvider provideAllyTests
     */
    public function testCannotChoseAllyMultipleTimes($values, array $indications): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => $values,
            'disadvantages' => [
                // This is to have enough exp to maybe buy them all
                43 => 1,
                44 => 1,
                48 => 1,
            ],
            'advantages_indications' => $indications + [48 => 'Some phobia'],
        ]);

        $code = $result->getResponse()->getStatusCode();
        if (500 === $code) {
            $msg = $result->getCrawler()->filter('title')->text();
        } else {
            $msg = \json_encode($result->getSession()->get('character.corahn_rin'));
        }
        static::assertSame(200, $code, $msg);
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());

        $txt = \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text());
        static::assertSame('Vous ne pouvez pas combiner plusieurs avantages ou désavantages de type "advantages.group.ally".', $txt);
    }

    public function provideAllyTests()
    {
        // Test all "Ally" advantage possibilities so we're sure every case is covered
        $ids = [1, 2, 3];

        // initialize by adding the empty set
        $results = [[]];

        foreach ($ids as $element) {
            foreach ($results as $combination) {
                $newElement = \array_merge([$element], $combination);
                $results[\implode(', ', $newElement)] = $newElement;
            }
        }

        $results = \array_filter($results, function ($item) {
            return \count($item) > 1;
        });

        $results = \array_map(function ($item) {
            return [
                \array_combine($item, \array_fill(0, \count($item), 1)),
                \array_combine($item, \array_fill(0, \count($item), 'Indication for : '.\implode(', ', $item))),
            ];
        }, $results);

        return $results;
    }

    /**
     * @dataProvider provideFinancialEaseTests
     */
    public function testCannotChoseFinancialEaseMultipleTimes($values): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => $values,
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
            $msg = \json_encode($result->getSession()->get('character.corahn_rin'));
        }
        static::assertSame(200, $code, $msg);
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vous ne pouvez pas combiner plusieurs avantages ou désavantages de type "advantages.group.financial_ease".', \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function provideFinancialEaseTests()
    {
        // Test all "FinancialEase" advantage possibilities so we're sure every case is covered
        $ids = [4, 5, 6, 7, 8];

        // initialize by adding the empty set
        $results = [[]];

        foreach ($ids as $element) {
            foreach ($results as $combination) {
                $newElement = \array_merge([$element], $combination);
                $results[\implode(', ', $newElement)] = $newElement;
            }
        }

        $results = \array_filter($results, function ($item) {
            // Only need from 2 to 4 because 1 is valid, and more than 4 is invalid.
            $c = \count($item);

            return $c > 1 && $c < 5;
        });

        return \array_map(function ($item) {
            return [
                \array_combine($item, \array_fill(0, \count($item), 1)),
            ];
        }, $results);
    }

    public function testIncorrectAdvantageValue(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [
                10 => 10000,
            ],
            'disadvantages' => [],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Une valeur incorrecte a été donnée à un avantage.', \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testIncorrectDisadvantageValue(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [],
            'disadvantages' => [
                47 => 2,
            ],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Une valeur incorrecte a été donnée à un désavantage.', \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testIncorrectAdvantageId(): void
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', [
            '07_setbacks' => [],
        ]);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages' => [
                99999 => 1,
            ],
            'disadvantages' => [],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text());
        static::assertEquals('Les avantages soumis sont incorrects.', \trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testIncorrectDisadvantageId(): void
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', [
            '07_setbacks' => [],
        ]);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages' => [],
            'disadvantages' => [
                99999 => 1,
            ],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text());
        static::assertEquals('Les désavantages soumis sont incorrects.', \trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testCannotHaveMoreThan4Advantages(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [
                1 => 1,
                5 => 1,
                19 => 1,
                29 => 1,
                30 => 1,
            ],
            'disadvantages' => [],
            'advantages_indications' => [
                1 => 'Ally',
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vous ne pouvez pas avoir plus de 4 avantages.', \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    public function testCannotHaveMoreThan4Disadvantages(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [],
            'disadvantages' => [
                41 => 1,
                43 => 1,
                47 => 1,
                49 => 1,
                51 => 1,
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin')));
        static::assertSame(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error')->count());
        static::assertSame('Vous ne pouvez pas avoir plus de 4 désavantages.', \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text()));
    }

    /**
     * @dataProvider provideFinancialEaseForPoor
     */
    public function testPoorCannotUseFinancialEase($values): void
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', ['07_setbacks' => [9 => ['id' => 9, 'avoided' => false]]]);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages' => $values,
            'disadvantages' => [],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text());
        static::assertRegExp('~^L\'avantage "Aisance financière \d" a été désactivé par le revers "Pauvreté".~', \trim($crawler->filter('#flash-messages > .card-panel.error')->text()));
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

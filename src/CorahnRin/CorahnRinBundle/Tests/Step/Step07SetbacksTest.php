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

class Step07SetbacksTest extends AbstractStepTest
{
    /**
     * Used to check how many times we process tests that have a certain amount of randomness.
     */
    const RANDOMNESS_COUNT = 100;

    public function testNoSetback()
    {
        $result = $this->submitAction([
            '06_age' => 20
        ], []);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/08_ways'));
        static::assertSame([
            '06_age' => 20,
            $this->getStepName() => [],
        ], $result->getSession()->get('character'));
    }

    public function provideManyTestsToEnsureRandomness()
    {
        $tests = [];

        $count = ((int) getenv('STEP7_RANDOMNESS_COUNT')) ?: static::RANDOMNESS_COUNT;

        for ($i = 1; $i <= $count; $i++) {
            $tests['randomness_'.$i] = [$i];
        }

        return $tests;
    }

    /**
     * @dataProvider provideManyTestsToEnsureRandomness
     */
    public function testAgeProvideAtLeastOneSetback()
    {
        $result = $this->submitAction([
            '06_age' => 21
        ], []);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/08_ways'));
        $setbacks = $result->getSession()->get('character')[$this->getStepName()];
        $nb = count($setbacks);
        switch ($nb) {
            case 1:
                static::assertFalse(current($setbacks)['avoided']);
                break;
            case 2:
                static::assertArrayHasKey(10, $setbacks); // Good luck
                unset($setbacks[10]);
                reset($setbacks);
                static::assertTrue(current($setbacks)['avoided']);
                break;
            case 3:
                static::assertArrayHasKey(1, $setbacks); // Bad luck
                break;
            default:
                static::fail('The amount of setbacks "'.$nb.'" is invalid');
        }
    }

    /**
     * @dataProvider provideManyTestsToEnsureRandomness
     */
    public function testAgeProvideAtLeastTwoSetbacks()
    {
        $result = $this->submitAction([
            '06_age' => 26
        ], []);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/08_ways'));
        $setbacks = $result->getSession()->get('character')[$this->getStepName()];
        $nb = count($setbacks);
        switch ($nb) {
            case 2:
                static::assertFalse(current($setbacks)['avoided']);
                static::assertFalse(next($setbacks)['avoided']);
                break;
            case 3:
                static::assertArrayHasKey(10, $setbacks); // Good luck
                unset($setbacks[10]);
                $hasAvoided = false;
                foreach ($setbacks as $value) {
                    if ($value['avoided'] === true) {
                        if (true === $hasAvoided) {
                            static::fail('Cannot have two avoided setbacks');
                        }
                        $hasAvoided = true;
                    }
                }
                static::assertTrue($hasAvoided, 'When age>26 and get 3 setbacks, at least one must be avoided.');
                break;
            case 4:
                static::assertArrayHasKey(1, $setbacks); // Bad luck
                break;
            default:
                static::fail('The amount of setbacks "'.$nb.'" is invalid');
        }
    }

    /**
     * @dataProvider provideManyTestsToEnsureRandomness
     */
    public function testAgeProvideAtLeastThreeSetbacks()
    {
        $result = $this->submitAction([
            '06_age' => 31
        ], []);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/08_ways'));
        $setbacks = $result->getSession()->get('character')[$this->getStepName()];
        $baseSetbacks = $setbacks;
        $nb = count($setbacks);
        switch ($nb) {
            case 3:
                static::assertFalse(current($setbacks)['avoided']);
                static::assertFalse(next($setbacks)['avoided']);
                static::assertFalse(next($setbacks)['avoided']);
                break;
            case 4:
                static::assertArrayHasKey(10, $setbacks, json_encode($baseSetbacks)); // Good luck
                unset($setbacks[10]);
                $hasAvoided = false;
                foreach ($setbacks as $value) {
                    if ($value['avoided'] === true) {
                        if (true === $hasAvoided) {
                            static::fail('Cannot have two setbacks that are avoided. '.json_encode($baseSetbacks));
                        }
                        $hasAvoided = true;
                    }
                }
                static::assertTrue($hasAvoided, 'When age>31 and get 4 setbacks, at least one must be avoided. '.json_encode($baseSetbacks));
                break;
            case 5:
                static::assertArrayHasKey(1, $setbacks, json_encode($baseSetbacks)); // Bad luck
                break;
            default:
                static::fail('The amount of setbacks "' . $nb . '" is invalid'.json_encode($baseSetbacks));
        }
    }

    public function testAgeNotDefinedRedirectsToStepOne()
    {
        $client = $this->getClient();

        $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
        $client->followRedirect();
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/01_people'));
        $crawler = $client->followRedirect();
        static::assertEquals(
            "L'étape \"07 Setbacks\" dépend de \"06 Age\", mais celle-ci n'est pas présente\ndans le personnage en cours de création...",
            trim($crawler->filter('#flash-messages > .card-panel.error')->text())
        );
    }

    public function testManualWithValidSetbacks()
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character', ['06_age' => 21]);
        $session->save();

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName().'?manual=');
        $form = $crawler->filter('#generator_form')->form()
            ->disableValidation()
            ->setValues([
                'setbacks_value' => [2, 3]
            ])
        ;

        $client->submit($form);

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/08_ways'));
        static::assertSame([2 => ['id' => 2, 'avoided' => false], 3 => ['id' => 3, 'avoided' => false]], $session->get('character')[$this->getStepName()]);
    }

    public function testManualWithInValidSetbacks()
    {
        $client = $this->getClient();

        $session = $client->getContainer()->get('session');
        $session->set('character', ['06_age' => 21]);
        $session->save();

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName().'?manual=');

        $form = $crawler->filter('#generator_form')->form()
            ->disableValidation()
            ->setValues([
                'setbacks_value' => [1, 10] // 1 and 10 exists, but they cannot be chosen with manual setup
            ])
        ;

        $crawler = $client->submit($form);

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertEquals(
            'Veuillez entrer des revers correct(s).',
            trim($crawler->filter('#flash-messages > .card-panel.error')->text())
        );
    }
}

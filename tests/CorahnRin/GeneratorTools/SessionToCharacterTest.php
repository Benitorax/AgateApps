<?php

namespace Tests\CorahnRin\GeneratorTools;

use CorahnRin\Entity\Characters;
use CorahnRin\GeneratorTools\SessionToCharacter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SessionToCharacterTest extends KernelTestCase
{
    /** @var PropertyAccessor */
    private static $propertyAccessor;

    public static function setUpBeforeClass()
    {
        static::bootKernel();
        static::$propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public static function tearDownAfterClass()
    {
        static::ensureKernelShutdown();
        static::$propertyAccessor = null;
    }

    public function test base working character(): void
    {
        // This one is just here as a smoke test,
        // just like the FullValidStepsControllerTest class.
        $character = static::getCharacterFromValues($values = [
            '01_people' => 1,
            '02_job' => 1,
            '03_birthplace' => 1,
            '04_geo' => 1,
            '05_social_class' => [
                'id' => 1,
                'domains' => [
                    0 => 'domains.natural_environment',
                    1 => 'domains.perception',
                ],
            ],
            '06_age' => 31,
            '07_setbacks' => [
                2 => [
                    'id' => 2,
                    'avoided' => false,
                ],
                3 => [
                    'id' => 3,
                    'avoided' => false,
                ],
            ],
            '08_ways' => [
                'ways.combativeness' => 5,
                'ways.creativity' => 4,
                'ways.empathy' => 3,
                'ways.reason' => 2,
                'ways.conviction' => 1,
            ],
            '09_traits' => [
                'quality' => 1,
                'flaw' => 10,
            ],
            '10_orientation' => 'character.orientation.instinctive',
            '11_advantages' => [
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
            ],
            '12_mental_disorder' => 1,
            '13_primary_domains' => [
                'domains' => [
                    'domains.craft' => 5,
                    'domains.close_combat' => 2,
                    'domains.stealth' => 1,
                    'domains.magience' => 0,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 3,
                    'domains.performance' => 0,
                    'domains.science' => 1,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 2,
                ],
                'ost' => 'domains.close_combat',
            ],
            '14_use_domain_bonuses' => [
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 0,
                    'domains.magience' => 0,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
                'remaining' => 2,
            ],
            '15_domains_spend_exp' => [
                'domains' => [
                    'domains.craft' => null,
                    'domains.close_combat' => 1,
                    'domains.stealth' => null,
                    'domains.magience' => null,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => null,
                    'domains.occultism' => null,
                    'domains.perception' => null,
                    'domains.prayer' => null,
                    'domains.feats' => null,
                    'domains.relation' => null,
                    'domains.performance' => null,
                    'domains.science' => null,
                    'domains.shooting_and_throwing' => null,
                    'domains.travel' => null,
                    'domains.erudition' => null,
                ],
                'remainingExp' => 50,
            ],
            '16_disciplines' => [
                'disciplines' => [
                    'domains.craft' => [
                        12 => true,
                        45 => true,
                        92 => true,
                    ],
                ],
                'remainingExp' => 25,
                'remainingBonusPoints' => 0,
            ],
            '17_combat_arts' => [
                'combatArts' => [
                    1 => true,
                ],
                'remainingExp' => 5,
            ],
            '18_equipment' => [
                'armors' => [
                    9 => true,
                ],
                'weapons' => [
                    5 => true,
                ],
                'equipment' => [
                    'Livre de règles',
                    'Un grimoire',
                ],
            ],
            '19_description' => [
                'name' => 'A',
                'player_name' => 'B',
                'sex' => 'character.sex.female',
                'description' => 'Some kind of description',
                'story' => 'An incredible story',
                'facts' => 'Of course, something true',
            ],
            '20_finish' => true,
        ]);

        $propertyAccessor = static::$propertyAccessor;

        $getValue = function(string $propertyPath) use ($character, $propertyAccessor) {
            return $propertyAccessor->getValue($character, $propertyPath);
        };

        static::assertSame(1, $getValue('people.id'));

        static::assertSame(1, $getValue('job.id'));

        static::assertSame(1, $getValue('birthplace.id'));

        static::assertSame(1, $getValue('geoLiving.id'));

        static::assertSame(1, $getValue('socialClass.id'));
        static::assertSame('domains.natural_environment', $getValue('socialClassDomain1'));
        static::assertSame('domains.perception', $getValue('socialClassDomain2'));

        static::assertSame(31, $getValue('age'));

        static::assertSame(2, $getValue('setbacks[0].setback.id'));
        static::assertSame(3, $getValue('setbacks[1].setback.id'));

        static::assertSame(5, $getValue('combativeness'));
        static::assertSame(5, $getValue('ways.combativeness'));
        static::assertSame(4, $getValue('creativity'));
        static::assertSame(4, $getValue('ways.creativity'));
        static::assertSame(3, $getValue('empathy'));
        static::assertSame(3, $getValue('ways.empathy'));
        static::assertSame(2, $getValue('reason'));
        static::assertSame(2, $getValue('ways.reason'));
        static::assertSame(1, $getValue('conviction'));
        static::assertSame(1, $getValue('ways.conviction'));

        static::assertSame(1, $getValue('quality.id'));
        static::assertSame(10, $getValue('flaw.id'));
        static::assertSame(1, $getValue('mentalDisorder.id'));

        static::assertSame('character.orientation.instinctive', $getValue('orientation'));

        static::assertSame(3, $getValue('advantages[0].advantage.id'));
        static::assertSame(1, $getValue('advantages[0].score'));
        static::assertSame('Influent ally', $getValue('advantages[0].indication'));

        static::assertSame(8, $getValue('advantages[1].advantage.id'));
        static::assertSame(1, $getValue('advantages[1].score'));
        static::assertSame('', $getValue('advantages[1].indication'));

        static::assertSame(31, $getValue('disadvantages[0].advantage.id'));
        static::assertSame(1, $getValue('disadvantages[0].score'));
        static::assertSame('', $getValue('disadvantages[0].indication'));

        static::assertSame(47, $getValue('disadvantages[1].advantage.id'));
        static::assertSame(1, $getValue('disadvantages[1].score'));
        static::assertSame('', $getValue('disadvantages[1].indication'));

        static::assertSame(48, $getValue('disadvantages[2].advantage.id'));
        static::assertSame(1, $getValue('disadvantages[2].score'));
        static::assertSame('Some phobia', $getValue('disadvantages[2].indication'));

        static::assertSame(5, $getValue('domains.craft'));
        static::assertSame(0, $getValue('domains.craftBonus'));
        static::assertSame(5, $getValue('domains.closeCombat'));
        static::assertSame(0, $getValue('domains.closeCombatBonus'));
        static::assertSame(1, $getValue('domains.stealth'));
        static::assertSame(0, $getValue('domains.stealthBonus'));
        static::assertSame(0, $getValue('domains.magience'));
        static::assertSame(0, $getValue('domains.magienceBonus'));
        static::assertSame(4, $getValue('domains.naturalEnvironment'));
        static::assertSame(0, $getValue('domains.naturalEnvironmentBonus'));
        static::assertSame(0, $getValue('domains.demorthenMysteries'));
        static::assertSame(0, $getValue('domains.demorthenMysteriesBonus'));
        static::assertSame(0, $getValue('domains.occultism'));
        static::assertSame(0, $getValue('domains.occultismBonus'));
        static::assertSame(1, $getValue('domains.perception'));
        static::assertSame(0, $getValue('domains.perceptionBonus'));
        static::assertSame(0, $getValue('domains.prayer'));
        static::assertSame(0, $getValue('domains.prayerBonus'));
        static::assertSame(0, $getValue('domains.feats'));
        static::assertSame(0, $getValue('domains.featsBonus'));
        static::assertSame(3, $getValue('domains.relation'));
        static::assertSame(0, $getValue('domains.relationBonus'));
        static::assertSame(0, $getValue('domains.performance'));
        static::assertSame(0, $getValue('domains.performanceBonus'));
        static::assertSame(1, $getValue('domains.science'));
        static::assertSame(0, $getValue('domains.scienceBonus'));
        static::assertSame(0, $getValue('domains.shootingAndThrowing'));
        static::assertSame(0, $getValue('domains.shootingAndThrowingBonus'));
        static::assertSame(0, $getValue('domains.travel'));
        static::assertSame(0, $getValue('domains.travelBonus'));
        static::assertSame(2, $getValue('domains.erudition'));
        static::assertSame(0, $getValue('domains.eruditionBonus'));

        static::assertSame('domains.craft', $getValue('disciplines[0].domain'));
        static::assertSame(6, $getValue('disciplines[0].score'));
        static::assertSame(12, $getValue('disciplines[0].discipline.id'));
        static::assertSame('domains.craft', $getValue('disciplines[1].domain'));
        static::assertSame(6, $getValue('disciplines[1].score'));
        static::assertSame(45, $getValue('disciplines[1].discipline.id'));
        static::assertSame('domains.craft', $getValue('disciplines[2].domain'));
        static::assertSame(6, $getValue('disciplines[2].score'));
        static::assertSame(92, $getValue('disciplines[2].discipline.id'));

        static::assertSame(1, $getValue('combatArts[0].id'));
        static::assertSame(5, $getValue('experienceActual'));

        static::assertSame(9, $getValue('armors[0].id'));
        static::assertSame(5, $getValue('weapons[0].id'));
        static::assertSame(['Livre de règles', 'Un grimoire'], $getValue('inventory'));

        static::assertSame('A', $getValue('name'));
        static::assertSame('B', $getValue('playerName'));
        static::assertSame('character.sex.female', $getValue('sex'));
        static::assertSame('Some kind of description', $getValue('description'));
        static::assertSame('An incredible story', $getValue('story'));
        static::assertSame('Of course, something true', $getValue('facts'));

        // Spent XP only grows when
        static::assertSame(0, $getValue('experienceSpent'));
    }

    public static function getCharacterFromValues(array $values): Characters
    {
        $sut = static::createInstance();

        return $sut->createCharacterFromGeneratorValues($values);
    }

    public static function createInstance(): SessionToCharacter
    {
        if (!static::$kernel) {
            static::bootKernel();
        }

        return static::$kernel
            ->getContainer()
            ->get('test.service_container')
            ->get(SessionToCharacter::class)
        ;
    }
}

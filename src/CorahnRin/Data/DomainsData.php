<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Data;

use CorahnRin\Entity\CharacterProperties\CharacterDomains;
use CorahnRin\Exception\InvalidDomain;
use CorahnRin\Exception\InvalidDomainValue;
use Doctrine\Common\Inflector\Inflector;

final class DomainsData
{
    // Old ID : 1
    public const CRAFT = [
        'way' => Ways::CREATIVITY,
        'title' => 'domains.craft',
        'description' => 'domains.craft.description',
    ];

    // Old ID : 2
    public const CLOSE_COMBAT = [
        'way' => Ways::COMBATIVENESS,
        'title' => 'domains.close_combat',
        'description' => 'domains.close_combat.description',
    ];

    // Old ID : 3
    public const STEALTH = [
        'way' => Ways::EMPATHY,
        'title' => 'domains.stealth',
        'description' => 'domains.stealth.description',
    ];

    // Old ID : 4
    public const MAGIENCE = [
        'way' => Ways::REASON,
        'title' => 'domains.magience',
        'description' => 'domains.magience.description',
    ];

    // Old ID : 5
    public const NATURAL_ENVIRONMENT = [
        'way' => Ways::EMPATHY,
        'title' => 'domains.natural_environment',
        'description' => 'domains.natural_environment.description',
    ];

    // Old ID : 6
    public const DEMORTHEN_MYSTERIES = [
        'way' => Ways::EMPATHY,
        'title' => 'domains.demorthen_mysteries',
        'description' => 'domains.demorthen_mysteries.description',
    ];

    // Old ID : 7
    public const OCCULTISM = [
        'way' => Ways::REASON,
        'title' => 'domains.occultism',
        'description' => 'domains.occultism.description',
    ];

    // Old ID : 8
    public const PERCEPTION = [
        'way' => Ways::REASON,
        'title' => 'domains.perception',
        'description' => 'domains.perception.description',
    ];

    // Old ID : 9
    public const PRAYER = [
        'way' => Ways::CONVICTION,
        'title' => 'domains.prayer',
        'description' => 'domains.prayer.description',
    ];

    // Old ID : 10
    public const FEATS = [
        'way' => Ways::COMBATIVENESS,
        'title' => 'domains.feats',
        'description' => 'domains.feats.description',
    ];

    // Old ID : 11
    public const RELATION = [
        'way' => Ways::EMPATHY,
        'title' => 'domains.relation',
        'description' => 'domains.relation.description',
    ];

    // Old ID : 12
    public const PERFORMANCE = [
        'way' => Ways::CREATIVITY,
        'title' => 'domains.performance',
        'description' => 'domains.performance.description',
    ];

    // Old ID : 13
    public const SCIENCE = [
        'way' => Ways::REASON,
        'title' => 'domains.science',
        'description' => 'domains.science.description',
    ];

    // Old ID : 14
    public const SHOOTING_AND_THROWING = [
        'way' => Ways::COMBATIVENESS,
        'title' => 'domains.shooting_and_throwing',
        'description' => 'domains.shooting_and_throwing.description',
    ];

    // Old ID : 15
    public const TRAVEL = [
        'way' => Ways::EMPATHY,
        'title' => 'domains.travel',
        'description' => 'domains.travel.description',
    ];

    // Old ID : 16
    public const ERUDITION = [
        'way' => Ways::REASON,
        'title' => 'domains.erudition',
        'description' => 'domains.erudition.description',
    ];

    public const ALL = [
        self::CRAFT['title'] => self::CRAFT,
        self::CLOSE_COMBAT['title'] => self::CLOSE_COMBAT,
        self::STEALTH['title'] => self::STEALTH,
        self::MAGIENCE['title'] => self::MAGIENCE,
        self::NATURAL_ENVIRONMENT['title'] => self::NATURAL_ENVIRONMENT,
        self::DEMORTHEN_MYSTERIES['title'] => self::DEMORTHEN_MYSTERIES,
        self::OCCULTISM['title'] => self::OCCULTISM,
        self::PERCEPTION['title'] => self::PERCEPTION,
        self::PRAYER['title'] => self::PRAYER,
        self::FEATS['title'] => self::FEATS,
        self::RELATION['title'] => self::RELATION,
        self::PERFORMANCE['title'] => self::PERFORMANCE,
        self::SCIENCE['title'] => self::SCIENCE,
        self::SHOOTING_AND_THROWING['title'] => self::SHOOTING_AND_THROWING,
        self::TRAVEL['title'] => self::TRAVEL,
        self::ERUDITION['title'] => self::ERUDITION,
    ];

    public static function validateDomain(string $domain): void
    {
        if (!isset(static::ALL[$domain])) {
            throw new InvalidDomain($domain);
        }
    }

    public static function validateDomainBaseValue(string $domain, int $value): void
    {
        self::validateDomain($domain);

        if ($value < 0 || $value > 5) {
            throw new InvalidDomainValue($domain);
        }
    }

    public static function getAsObject(string $name): DomainItem
    {
        $item = static::ALL[$name];

        return new DomainItem($item['title'], $item['description'], $item['way']);
    }

    /**
     * @return DomainItem[]
     */
    public static function allAsObjects(): array
    {
        $collection = [];

        foreach (static::ALL as $item) {
            $collection[$item['title']] = static::getAsObject($item['title']);
        }

        return $collection;
    }

    /**
     * Also verifies that the camelized title does exist in the CharacterDomains class.
     */
    public static function getCamelizedTitle(string $domainName, string $suffix = ''): string
    {
        $camelizedTitle = \preg_replace('~^domains\.~iUu', '', Inflector::camelize($domainName));

        $camelizedTitle .= $suffix;

        if (!\property_exists(CharacterDomains::class, $camelizedTitle)) {
            throw new InvalidDomain($camelizedTitle);
        }

        return $camelizedTitle;
    }
}

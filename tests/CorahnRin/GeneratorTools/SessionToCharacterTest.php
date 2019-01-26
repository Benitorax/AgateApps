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

namespace Tests\CorahnRin\GeneratorTools;

use CorahnRin\Entity\Character;
use CorahnRin\Exception\CharacterException;
use CorahnRin\GeneratorTools\SessionToCharacter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SessionToCharacterTest extends KernelTestCase
{
    /** @var PropertyAccessor */
    private static $propertyAccessor;

    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::$propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public static function tearDownAfterClass(): void
    {
        static::ensureKernelShutdown();
        static::$propertyAccessor = null;
    }

    public function test unfinished character generation(): void
    {
        $this->expectException(CharacterException::class);
        $this->expectExceptionMessage('Character error: Generator seems not to be fully finished');

        static::getCharacterFromValues([]);
    }

    /**
     * @dataProvider provideCharacterFiles
     */
    public function test base working characters(array $values, array $expectedValues): void
    {
        // This one is just here as a smoke test,
        // just like the FullValidStepsControllerTest class.
        $character = static::getCharacterFromValues($values);

        $propertyAccessor = static::$propertyAccessor;

        $getValue = function (string $propertyPath) use ($character, $propertyAccessor) {
            return $propertyAccessor->getValue($character, $propertyPath);
        };

        foreach ($expectedValues as $data) {
            static::assertSame($data['value'], $getValue($data['property_path']));
        }
    }

    public function provideCharacterFiles()
    {
        /** @var Finder|SplFileInfo[] $files */
        $files = (new Finder())->name('*.php')->in(__DIR__.'/session_to_character_tests/');

        foreach ($files as $file) {
            $fileData = require $file;
            yield $file->getBasename('.php') => [$fileData['values'], $fileData['expected_values']];
        }
    }

    public static function getCharacterFromValues(array $values): Character
    {
        return self::createInstance()->createCharacterFromGeneratorValues($values);
    }

    private static function createInstance(): SessionToCharacter
    {
        static::bootKernel();

        return static::$container->get(SessionToCharacter::class);
    }
}

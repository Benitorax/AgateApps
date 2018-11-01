<?php

namespace Tests\CorahnRin\GeneratorTools;

use CorahnRin\Entity\Characters;
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
        $files = (new Finder())->name('*.php')->in(__DIR__.'/test_files/');

        foreach ($files as $file) {
            $fileData = require $file;
            yield $file->getBasename('.php') => [$fileData['values'], $fileData['expected_values']];
        }
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

        return static::$container
            ->get(SessionToCharacter::class)
        ;
    }
}

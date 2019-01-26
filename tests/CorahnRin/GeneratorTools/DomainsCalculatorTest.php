<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainsData;
use CorahnRin\GeneratorTools\DomainsCalculator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DomainsCalculatorTest extends KernelTestCase
{
    /**
     * @dataProvider provideTestsWithoutBonuses
     */
    public function test calculator with fixtures data(array $arguments, array $expectedValues): void
    {
        $arguments[3] = $this->fillMissingDomains($arguments[3]);

        $values = \call_user_func_array([$this->getCalculator(), 'calculateFromGeneratorData'], $arguments);

        foreach ($expectedValues as $key => $value) {
            static::assertArrayHasKey($key, $values);
            static::assertSame($values[$key], $value, \sprintf('Key "%s" has wrong value.', $key));
        }
    }

    public function provideTestsWithoutBonuses(): \Generator
    {
        /** @var Finder|SplFileInfo[] $files */
        $files = (new Finder())->name('*.php')->in(__DIR__.'/domains_calculator_tests_without_bonuses/');

        foreach ($files as $file) {
            $fileData = require $file;
            yield $file->getBasename('.php') => [$fileData['calculator_arguments'], $fileData['expected_values']];
        }
    }

    private function fillMissingDomains(array $step13DomainsToFill): array
    {
        foreach (DomainsData::ALL as $domain => $data) {
            if (!\array_key_exists($domain, $step13DomainsToFill)) {
                $step13DomainsToFill[$domain] = 0;
            }
        }

        return $step13DomainsToFill;
    }

    private function getCalculator(): DomainsCalculator
    {
        return new DomainsCalculator();
    }
}

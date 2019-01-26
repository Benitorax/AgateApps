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

namespace DataFixtures;

use Doctrine\ORM\Mapping\ClassMetadata;

trait FixtureMetadataIdGeneratorTrait
{
    /**
     * {@inheritdoc}
     *
     * We override this because the generator type sometimes depends on the DBMS.
     */
    protected function setGeneratorBasedOnId(ClassMetadata $metadata, $id = null): void
    {
        if ($id) {
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        } else {
            $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_AUTO);
        }
    }
}

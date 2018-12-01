<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Cache;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ClearableCache;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

/**
 * Standalone service to manage cache for EsterenMaps.
 * The only goal is to clear app cache & Doctrine cache.
 */
class CacheManager implements EventSubscriber, CacheClearerInterface
{
    public const CACHE_PREFIX = 'esterenmaps_';

    private $doctrineResultCache;
    private $logger;

    public function __construct(LoggerInterface $logger, Cache $doctrineResultCache = null)
    {
        $this->doctrineResultCache = $doctrineResultCache;
        $this->logger = $logger;
    }

    public function clearDoctrineCache(): void
    {
        if (!$this->doctrineResultCache) {
            return;
        }

        if ($this->doctrineResultCache instanceof ClearableCache) {
            $this->logger->info('Clearing doctrine cache');

            $this->doctrineResultCache->deleteAll();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear($cacheDir)
    {
        $this->clearDoctrineCache();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            'postPersist',
            'postUpdate',
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->clearCacheForEntity($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->clearCacheForEntity($args);
    }

    private function clearCacheForEntity(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        // Clear the map cache if the entity corresponds to a specific class.
        if ($entity instanceof EntityToClearInterface) {
            $this->clearDoctrineCache();
        }
    }
}

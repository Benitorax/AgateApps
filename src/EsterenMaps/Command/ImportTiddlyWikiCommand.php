<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Command;

use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnitOfWork;
use EsterenMaps\Entity\Factions;
use EsterenMaps\Entity\Maps;
use EsterenMaps\Entity\Markers;
use EsterenMaps\Entity\MarkersTypes;
use EsterenMaps\Entity\Routes;
use EsterenMaps\Entity\RoutesTypes;
use EsterenMaps\Entity\Zones;
use EsterenMaps\Entity\ZonesTypes;
use Orbitale\Component\DoctrineTools\EntityRepositoryHelperTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ImportTiddlyWikiCommand extends Command
{
    public static $defaultName = 'esterenmaps:import:tiddly-wiki';

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UnitOfWork
     */
    private $uow;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var bool
     */
    private $dryRun = true;

    /**
     * @var Maps[]
     */
    private $maps = [];

    /**
     * @var Factions[][]
     */
    private $factions = [];

    /**
     * @var MarkersTypes[][]
     */
    private $markersTypes = [];

    /**
     * @var ZonesTypes[][]
     */
    private $zonesTypes = [];

    /**
     * @var RoutesTypes[][]
     */
    private $routesTypes = [];

    /**
     * @var Routes[][]
     */
    private $routes = [];

    /**
     * @var Zones[][]
     */
    private $zones = [];

    /**
     * @var Markers[][]
     */
    private $markers = [];

    /**
     * @var array
     */
    private $repos = [];

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    public function __construct(PropertyAccessor $propertyAccessor, EntityManagerInterface $em)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->em = $em;
        parent::__construct(static::$defaultName);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Generate all tiles for a specific map.')
            ->setHelp('This command imports data from a tiddly wiki file or url into the database.')
            ->addArgument('file', InputArgument::REQUIRED, 'The file or the url to check.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Executes the command instead of just showing modified elements.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time = \microtime(true);

        $this->output = $output;

        $this->dryRun = !$input->getOption('force');

        $file = $input->getArgument('file');

        $data = \file_get_contents($file);
        $encoding = \mb_detect_encoding($data, \mb_detect_order(), true);
        if ('UTF-8' !== $encoding) {
            // Force UTF8 conversion to avoid reinserting data
            $data = \mb_convert_encoding($data, 'UTF-8');
        }

        if (!$data || !\is_string($data)) {
            throw new \InvalidArgumentException('Tiddly wiki content could not be retrieved.');
        }

        /** @var array $data */
        $data = \json_decode((string) $data, true);

        if (!$data) {
            throw new \InvalidArgumentException('Json error while decoding: <error>'.\json_last_error_msg().'</error>.');
        }

        $this->uow = $this->em->getUnitOfWork();

        // Setting all data in the class for we can use it in the other methods
        $this->data = $data;
        $total = \count($data);

        $output->writeln('Found <info>'.$total.'</info> items to check for import.');

        $output->writeln('Processing...');

        $tags = \array_reduce($data, function ($carry, $object) {
            $carry[$object['tags']] = [
                'tag' => $object['tags'],
                'nb_occurrences' => isset($carry[$object['tags']]['nb_occurrences']) ? $carry[$object['tags']]['nb_occurrences'] + 1 : 1,
            ];

            return $carry;
        });
        $table = new Table($output);
        $table->setHeaders(['Tag found', 'Count']);
        $table->setRows($tags);
        $table->render();

        $this->maps = $this->getRepository(Maps::class)->findAllRoot(true);

        $this->factions = $this->getReferenceObjects('factions', Factions::class);
        $this->markersTypes = $this->getReferenceObjects('markertype', MarkersTypes::class);
        $this->zonesTypes = $this->getReferenceObjects('zonetype', ZonesTypes::class);
        $this->routesTypes = $this->getReferenceObjects('routetype', RoutesTypes::class);

        $this->markers = $this->processObjects('marqueurs', Markers::class);
        $this->zones = $this->processObjects('zones', Zones::class);
        $this->routes = $this->processObjects('routes', Routes::class);

        $allData = \array_merge(
            $this->factions['new'],
            $this->factions['existing'],
            $this->markersTypes['new'],
            $this->markersTypes['existing'],
            $this->zonesTypes['new'],
            $this->zonesTypes['existing'],
            $this->routesTypes['new'],
            $this->routesTypes['existing'],
            $this->markers['new'],
            $this->markers['existing'],
            $this->zones['new'],
            $this->zones['existing'],
            $this->routes['new'],
            $this->routes['existing']
        );

        $this->uow->computeChangeSets();

        foreach ($allData as $object) {
            $changesets = $this->uow->getEntityChangeSet($object);
            $changesetsNb = 0;
            $class = \get_class($object);

            $table = new Table($this->output);
            $table->setHeaders(['Class', 'Property', 'Before', 'After']);

            foreach ($changesets as $field => $changeset) {
                if ('createdAt' === $field || 'updatedAt' === $field) {
                    continue;
                }
                ++$changesetsNb;
                $before = $changeset[0];
                $after = $changeset[1];
                $table->addRow([$class, $field, $before, $after]);
            }

            if ($changesetsNb) {
                $table->render();
            }
        }

        if ($this->dryRun) {
            $output->writeln('Finished processing dry run.');
            $code = 1;
        } else {
            try {
                $output->write('Attempting to flush all data...');
                $this->em->flush();
                $output->writeln(' <info>Ok!</info>');

                $idsUpdated = 0;

                foreach ($this->data as $k => $data) {
                    $id = $data['id'];

                    if (\is_numeric($id)) {
                        continue;
                    }

                    $newId = null;

                    switch ($data['tags']) {
                        case 'marqueurs':
                            $newId = $this->markers['new'][$id]->getId();
                            break;
                        case 'zones':
                            $newId = $this->zones['new'][$id]->getId();
                            break;
                        case 'routes':
                            $newId = $this->routes['new'][$id]->getId();
                            break;
                        case 'factions':
                            $newId = $this->factions['new'][$id]->getId();
                            break;
                    }

                    if ($newId) {
                        $this->data[$k]['id'] = (string) $newId;
                        ++$idsUpdated;
                    }
                }

                $json = \json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_BIGINT_AS_STRING);
                \file_put_contents($file, $json);
                $output->writeln('Updated <info>'.$idsUpdated.'</info> identifiers in the JSON file.');

                $code = 0;
            } catch (\Exception $e) {
                throw new \RuntimeException('An error occured when trying to update the database.', null, $e);
            }
        }

        $output->writeln('Executed in '.\number_format(\microtime(true) - $time, 4).' seconds');

        return $code;
    }

    /**
     * @param string $tag
     * @param string $entity
     * @param string $nameProperty
     *
     * @return array
     */
    private function getReferenceObjects($tag, $entity, $nameProperty = 'name')
    {
        $data = \array_filter($this->data, function ($element) use ($tag) {
            return isset($element['tags']) && $element['tags'] === $tag;
        });

        $repo = $this->getRepository($entity);

        $new = [];
        $existing = [];

        /** @var Markers[]|Zones[]|Routes[] $objects */
        $objects = $repo->findAllRoot('id');

        foreach ($data as $datum) {
            $object = null;

            $id = $datum['id'];

            // Checks automatically if the objects exists from its ID.
            // If not, we get the object by title
            $object = $objects[$id] ?? $repo->findOneBy([$nameProperty => $datum['title']]);

            if ($object) {
                $existing[$object->getId()] = $object;
            } else {
                $object = new $entity();
                $new[$id] = $object;
            }

            $this->propertyAccessor->setValue($object, $nameProperty, $datum['title']);

            $this->em->persist($object);
        }

        foreach ($objects as $object) {
            if (!isset($existing[$object->getId()])) {
                $existing[$object->getId()] = $object;
            }
        }

        return [
            'new' => $new,
            'existing' => $existing,
        ];
    }

    /**
     * @param string $tag
     * @param string $entity
     *
     * @return array
     */
    private function processObjects($tag, $entity)
    {
        $data = \array_filter($this->data, function ($element) use ($tag) {
            return isset($element['tags']) && $element['tags'] === $tag;
        });

        $repo = $this->getRepository($entity);

        $new = [];
        $existing = [];

        /** @var Markers[]|Zones[]|Routes[] $objects */
        $objects = $repo->findAllRoot(true);

        foreach ($data as $datum) {
            $object = null;

            $id = $datum['id'];

            // Checks automatically if the objects exists from its ID.
            // If not, we get the object by title
            $object = $objects[$id] ?? $repo->findOneBy(['name' => $datum['title']]);

            if ($object) {
                $existing[$object->getId()] = $object;
            } else {
                $object = new $entity();
                $new[$id] = $object;
            }

            $this->propertyAccessor->setValue($object, 'name', $datum['title']);

            $this->updateOneObject($object, $datum);

            $this->em->persist($object);
        }

        foreach ($objects as $object) {
            if (!isset($existing[$object->getId()])) {
                $existing[$object->getId()] = $object;
            }
        }

        return [
            'new' => $new,
            'existing' => $existing,
        ];
    }

    /**
     * @param string $entityName
     *
     * @return EntityRepository|ObjectRepository|EntityRepositoryHelperTrait
     */
    private function getRepository($entityName): EntityRepository
    {
        if (!isset($this->repos[$entityName])) {
            $this->repos[$entityName] = $this->em->getRepository($entityName);
        }

        return $this->repos[$entityName];
    }

    /**
     * @param Markers|Zones|Routes $object
     * @param array                $data
     */
    private function updateOneObject($object, $data)
    {
        $data['created'] = DateTime::createFromFormat('YmdHisu', $data['created'] ?? \date('YmdHisu'));

        $object
            ->setId(\is_numeric($data['id']) ? $data['id'] : null)
            ->setName($data['title'])
            ->setDescription($data['text'])
            ->setMap($this->maps[$data['map_id']])
            ->setFaction($this->getOneReferencedObject('factions', $data['faction_id']))
            ->setCreatedAt($data['created'])
        ;

        if (\method_exists($object, 'setUpdatedAt')) {
            $object->setUpdatedAt(new DateTime());
        }

        if ($object instanceof Markers) {
            $object
                ->setMarkerType($this->getOneReferencedObject('markersTypes', $data['markertype_id']))
            ;
            if (!$object->isLocalized()) {
                $object->setLatitude(0)->setLongitude(0);
            }
        } elseif ($object instanceof Routes) {
            $object
                ->setRouteType($this->getOneReferencedObject('routesTypes', $data['routetype_id']))
                ->setMarkerStart($this->getOneReferencedObject('markers', $data['markerstart_id']))
                ->setMarkerEnd($this->getOneReferencedObject('markers', $data['markerend_id']))
            ;
            if (!$object->isLocalized()) {
                $object->setCoordinates(\json_encode([
                    ['lat' => 0, 'lng' => 0],
                    ['lat' => 1, 'lng' => 1],
                ]));
            }
            $object->refresh();
        } elseif ($object instanceof Zones) {
            $object
                ->setZoneType($this->getOneReferencedObject('zonesTypes', $data['zonetype_id']))
            ;
            if (!$object->isLocalized()) {
                $object->setCoordinates(json_encode([
                    ['lat' => 0, 'lng' => 0],
                    ['lat' => 0, 'lng' => 1],
                    ['lat' => 1, 'lng' => 0],
                ]));
            }
        } else {
            throw new \RuntimeException('Unknown type "'.$data['tags'].'".');
        }
    }

    private function getOneReferencedObject($type, $id)
    {
        $object = null;
        if ($id) {
            $data = $this->$type;
            if (isset($data['new'][$id])) {
                $object = $data['new'][$id];
            } elseif (isset($data['existing'][$id])) {
                $object = $data['existing'][$id];
            }
        }

        return $object;
    }
}

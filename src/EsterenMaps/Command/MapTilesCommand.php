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

use Doctrine\Common\Persistence\ObjectManager;
use EsterenMaps\Entity\Map;
use EsterenMaps\Repository\MapsRepository;
use EsterenMaps\Services\MapsTilesManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MapTilesCommand extends Command
{
    protected static $defaultName = 'esterenmaps:map:generate-tiles';

    private $projectDir;
    private $em;
    private $tilesManager;

    public function __construct(string $projectDir, ObjectManager $em, MapsTilesManager $tilesManager)
    {
        parent::__construct(static::$defaultName);
        $this->projectDir = $projectDir;
        $this->em = $em;
        $this->tilesManager = $tilesManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate all tiles for a specific map.')
            ->setHelp('This command is used to generate a tile image for one of your maps.'."\n"
                .'You can specify the id of the map by adding it as an argument, or as an option with "-i x" or "--i=x" where "x" is the map id'."\n"
                ."\n".'The command will generate all tiles of a map. The tiles number is calculated upon the image size and the maxZoom value'
                ."\n".'The higher is the maxZoom value, higher is the number of tiles.'
                ."\n".'This command can take a long time to execute, depending of your system.'
                ."\n".'but do not worry : you can restart it at any time and skip all existing files')
            ->addArgument('id', InputArgument::OPTIONAL, 'Enter the id of the map you want to generate', null)
            ->addOption('replace', 'r', InputOption::VALUE_NONE, 'Replaces all existing tiles')
            ->addOption('skip', 'k', InputOption::VALUE_NONE, 'Skip all existing tiles')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $id = $input->hasArgument('id') ? $input->getArgument('id') : null;

        /** @var MapsRepository $repo */
        $repo = $this->em->getRepository(Map::class);

        $list = null;

        $io->comment('Be careful: as maps may be huge, this application can use a lot of memory and take very long to execute.');

        /** @var Map $map */
        $map = null;

        do {
            // Finds a map
            $map = $repo->findOneBy(['id' => $id]);

            // If no map is found, we'll ask the user to choose between any of the maps in the database
            if (!$map) {
                $maps_list = [];
                if (null === $list) {
                    /* @var Map[] $list */
                    $maps_list = $repo->findAllRoot('id');

                    if (!\count($maps_list)) {
                        $io->comment('There is no map in the database.');

                        return 1;
                    }

                    unset($list);
                }
                if (null !== $id) {
                    $io->warning('No map with id: '.$id);
                }
                $id = $io->choice('Select a map to generate:', $maps_list);
            }
        } while (!$map);

        $io->comment('Generating map tiles for "'.$map->getName().'"');

        // This is a workaround to allow images to be stored with either global path or relative path
        if (!\file_exists($map->getImage())) {
            $img = $this->projectDir.'/public/'.$map->getImage();
            $map->setImage($img);
        }

        if (!\file_exists($map->getImage())) {
            throw new \RuntimeException(\sprintf('Map image file "%s" cannot be found.', $img ?? $map->getImage()));
        }

        try {
            $maxZoom = $map->getMaxZoom();
            for ($i = 0; $i <= $maxZoom; ++$i) {
                $io->comment('Processing extraction for zoom value '.$i);
                $this->tilesManager->generateTiles($i, true, $map);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Error while processing extraction for zoom value "'.($i ?? '0').'".', 1, $e);
        }

        return 0;
    }
}

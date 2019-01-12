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

use Doctrine\ORM\EntityManager;
use EsterenMaps\Entity\Map;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RefreshDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('esterenmaps:map:refresh')
            ->setDescription('Refresh all dynamic data (routes distances, etc.)')
            ->setHelp('This command is here to update all data that may be dynamic.'."\n"
                .'For example, the start and end marker of each route may change its coordinates,'."\n"
                .'and the distance depends on the coordinates, so both data are updated with this command.'."\n\n"
                .'This command runs on every data for every map in the database.'
            )
            ->addOption('nan-as', null, InputOption::VALUE_OPTIONAL, 'Treat all "NaN" values as the specified number.', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $nanAs = $input->getOption('nan-as');

        if (null !== $nanAs) {
            if (false !== \mb_strpos($nanAs, ',')) {
                $nanAs = \str_replace(',', '.', $nanAs);
            }
            $nanAs = \preg_replace("~\s+~", '', $nanAs);

            if (!\is_numeric($nanAs)) {
                $io->error(\sprintf(
                    'The --nan-as option must be a valid number. "%s" given.',
                    $input->getOption('nan-as')
                ));

                return 1;
            }

            $nanAs = \is_float($nanAs) ? (float) $nanAs : (int) $nanAs;
        }

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $maps = $em->getRepository(Map::class)->findAllWithRoutes();

        // Calculate the number of objects.
        $numberTotal = \array_reduce($maps, function ($carry, Map $map) {
            return $carry + $map->getRoutes()->count();
        }, 0);
        $numberModified = 0;

        $io->block('Refreshing elements...');
        $io->progressStart($numberTotal);
        // For each map
        foreach ($maps as $map) {
            // Refresh all routes
            foreach ($map->getRoutes() as $route) {
                $route->refresh();
                $em->persist($route);
                $io->progressAdvance();
            }
        }
        $io->progressFinish();
        $io->success('Done!');

        $io->block('Computing changesets...');

        // We compute changesets to "truncate" the distances.
        $uow = $em->getUnitOfWork();
        $uow->computeChangeSets();

        $io->progressStart($numberTotal);

        $errors = [];

        foreach ($maps as $map) {
            foreach ($map->getRoutes() as $route) {
                $changesets = $uow->getEntityChangeSet($route);

                if (\array_key_exists('distance', $changesets)) {
                    // Change all "NaN" to the value of $nanAs if specified.
                    if (null !== $nanAs) {
                        $changesets['distance'] = \array_map(function ($e) use ($nanAs) {
                            return 'nan' === \mb_strtolower($e) ? $nanAs : $e;
                        }, $changesets['distance']);
                    }

                    // If we have a "null" or "NaN", it's quite problematic...
                    // We skip it and log the error
                    if (\in_array(null, $changesets['distance'], true)
                        || \in_array('nan', \array_map('strtolower', $changesets['distance']), true)
                    ) {
                        $errors[] = 'Error in the changesets for route "'.$route.'".'.PHP_EOL
                            .'Incriminated changes: '.\json_encode($changesets);
                        continue;
                    }

                    if ($changesets['distance'][0] === $changesets['distance'][1]) {
                        unset($changesets['distance']);
                    } else {
                        $changesets['distance'] = \array_map('floatval', $changesets['distance']);
                    }
                }

                if (!\count($changesets)) {
                    continue;
                }

                ++$numberModified;
                $io->progressAdvance();
            }
        }

        $io->progressFinish();

        if (0 === $numberModified) {
            $io->block('Nothing to update.', null, 'comment');

            return 2;
        }

        if ($errors) {
            \array_unshift($errors, '');
            $io->warning($errors);
        }

        $io->writeln('Elements updated: <info>'.$numberModified.' / '.$numberTotal.'</info>');

        try {
            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        }

        $io->success('Done!');

        return 0;
    }
}

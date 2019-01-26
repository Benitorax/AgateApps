<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Services;

use EsterenMaps\Entity\Map;
use EsterenMaps\ImageManagement\ImageIdentification;
use Orbitale\Component\ImageMagick\Command;
use Orbitale\Component\ImageMagick\ReferenceClasses\Geometry;
use Symfony\Component\HttpKernel\KernelInterface;

class MapsTilesManager
{
    private $outputDirectory;
    private $webDir;
    private $mapImageManager;
    private $tileSize;
    private $magickPath;
    private $debug;

    /** @var Map */
    private $map;

    /** @var int */
    private $imgWidth;

    /** @var int */
    private $imgHeight;

    /** @var ImageIdentification[] */
    private $identifications = [];

    public function __construct(string $outputDirectory, int $tileSize, string $imageMagickPath, MapImageManager $mapImageManager, KernelInterface $kernel)
    {
        $this->tileSize = $tileSize;
        $outputDirectory = \rtrim($outputDirectory, '\\/');
        $imageMagickPath = \rtrim($imageMagickPath, '\\/');
        if (\mb_strpos($imageMagickPath, 'magick') !== \mb_strlen($imageMagickPath) - 6) {
            $imageMagickPath .= \DIRECTORY_SEPARATOR;
        }
        $this->magickPath = $imageMagickPath;
        $this->outputDirectory = $outputDirectory;
        if (0 === \mb_strpos($outputDirectory, '@')) {
            $this->outputDirectory = $kernel->locateResource($outputDirectory);
        }
        $this->webDir = $kernel->getRootDir().'/../web';
        $this->debug = $kernel->isDebug();
        $this->mapImageManager = $mapImageManager;
    }

    public function getOutputDirectory(): string
    {
        return $this->outputDirectory;
    }

    public function setMap(Map $map): self
    {
        $this->map = $map;
        $path = null;
        if (
            !\file_exists($path = $this->map->getImage())
            && !\file_exists($path = $this->webDir.'/'.$this->map->getImage())
        ) {
            throw new \RuntimeException('Map image could not be found: '.$path);
        }

        return $this;
    }

    /**
     * Identifie les caractéristiques de la carte si ce n'est pas déjà fait
     * Renvoie l'identification demandée en fonction du zoom
     * Renvoie une exception si l'identification par ImageMagick ne fonctionne
     * pas.
     *
     * @return ImageIdentification|ImageIdentification[]
     *
     * @throws \RunTimeException
     */
    public function identifyImage(int $zoom = null)
    {
        if (!$this->imgWidth || !$this->imgHeight) {
            // Détermine la taille de l'image initiale une fois et place les attributs dans l'objet
            $size = \getimagesize($this->map->getImage());
            if (!$size || !isset($size[0], $size[1])) {
                throw new \RuntimeException('Error while retrieving map dimensions');
            }
            [$w, $h] = $size;
            $this->imgWidth = $w;
            $this->imgHeight = $h;
        }

        if (null === $zoom) {
            return $this->identifications;
        }

        if (!isset($this->identifications[$zoom])) {
            // Calcul des ratios et du nombre maximum de vignettes
            $crop_unit = 2 ** ($this->map->getMaxZoom() - $zoom) * $this->tileSize;

            $max_tiles_x = \ceil($this->imgWidth / $crop_unit) - 1;
            $max_tiles_y = \ceil($this->imgHeight / $crop_unit) - 1;

            $max_width = $max_tiles_x * $this->tileSize;
            $max_height = $max_tiles_y * $this->tileSize;

            $max_width_global = $crop_unit * ($max_tiles_x + 1);
            $max_height_global = $crop_unit * ($max_tiles_y + 1);

            $this->identifications[$zoom] = new ImageIdentification([
                'xmax' => $max_tiles_x,
                'ymax' => $max_tiles_y,
                'tiles_max' => $max_tiles_x * $max_tiles_y,
                'wmax' => $max_width,
                'hmax' => $max_height,
                'wmax_global' => $max_width_global,
                'hmax_global' => $max_height_global,
            ]);
        }

        return $this->identifications[$zoom];
    }

    public function generateTiles(int $zoom, bool $debug = false, Map $map = null): void
    {
        if (!$this->map && $map) {
            $this->setMap($map);
        }

        $max = $this->map->getMaxZoom();

        $ratio = 1 / (2 ** ($max - $zoom)) * 100;

        $outputScheme = $this->outputDirectory.'/temp_tiles/'.$this->map->getId().'/'.$zoom.'.jpg';
        $outputFinal = $this->outputDirectory.'/'.$this->map->getId().'/'.$zoom.'/{x}/{y}.jpg';

        if (!\is_dir(\dirname($outputScheme))) {
            \mkdir(\dirname($outputScheme), 0775, true);
        }

        // Supprime tout fichier existant
        $existingFiles = \glob(\dirname($outputScheme).'/*');
        foreach ($existingFiles as $file) {
            \unlink($file);
        }

        $this->identifyImage($zoom);

        $w = $this->imgWidth;
        $h = $this->imgHeight;

        if ($w >= $h) {
            $h = $w;
        } else {
            $w = $h;
        }

        $cmd = (new Command($this->magickPath))
            ->convert($this->map->getImage())
            ->background('#000000')
            ->extent($w.'x'.$h)
            ->resize($ratio.'%')
            ->crop($this->tileSize.'x'.$this->tileSize)
            ->background('#000000')
            ->extent($this->tileSize.'x'.$this->tileSize)
            ->thumbnail($this->tileSize.'x'.$this->tileSize)
            ->output($outputScheme)
        ;

        $commandResponse = $cmd->run($cmd::RUN_DEBUG);

        if ($commandResponse->hasFailed()) {
            $error = \trim($commandResponse->getError());
            $msg = \trim('Error while processing conversion. Command returned error:'."\n\t".\str_replace("\n", "\n\t", $error));
            if ($debug) {
                $msg .= "\n".'Executed command : '."\n\t".$cmd->getCommand();
            }
            throw new \RuntimeException($msg);
        }

        $existingFiles = \glob(\dirname($outputScheme).'/*');

        \sort($existingFiles, SORT_NATURAL | SORT_FLAG_CASE);
        $existingFiles = \array_values($existingFiles);

        $modulo = \sqrt(\count($existingFiles));

        foreach ($existingFiles as $i => $file) {
            $x = \floor($i / $modulo);
            $y = $i % $modulo;
            $filename = \str_replace(['{x}', '{y}'], [$x, $y], $outputFinal);

            if (!\is_dir(\dirname($filename))
                && !\mkdir($concurrentDirectory = \dirname($filename), 0775, true)
                && !\is_dir($concurrentDirectory)
            ) {
                throw new \RuntimeException(\sprintf('Directory "%s" could not be created', $concurrentDirectory));
            }

            \rename($file, $filename);
        }

        // Supprime tout fichier existant
        $existingFiles = \glob(\dirname($outputScheme).'/*');
        foreach ($existingFiles as $file) {
            \unlink($file);
        }
    }

    /**
     * Crée une image à partir de l'image principale et renvoie le nom du
     * fichier temporaire TOUTES LES DIMENSIONS DOIVENT ÊTRE EN PIXELS !
     */
    public function createImage(int $ratio, int $x, int $y, int $width, int $height, bool $withImages = false, bool $dry_run = false): string
    {
        if ($ratio <= 0 || null === $ratio) {
            $ratio = 100;
        }
        $this->identifyImage();

        $maxWidth = $this->imgWidth;
        $maxHeight = $this->imgHeight;
        $errMsg = '"%s" + "%s" values exceed image size which is %dx%d';

        if (($ratio * $width / 100) + $x >= $maxWidth) {
            throw new \InvalidArgumentException(\sprintf($errMsg, 'width', 'x', $maxWidth, $maxHeight));
        }
        if (($ratio * $height / 100) + $y >= $maxHeight) {
            throw new \InvalidArgumentException(\sprintf($errMsg, 'height', 'y', $maxWidth, $maxHeight));
        }

        $imgOutput = $this->mapDestinationName($ratio, $x, $y, $width, $height, $withImages);

        if (!$this->debug && \file_exists($imgOutput)) {
            return $imgOutput;
        }

        if (!\is_dir(\dirname($imgOutput))) {
            \mkdir(\dirname($imgOutput), 0777, true);
        }

        $command = new Command(\trim($this->magickPath));

        if ($withImages) {
            $imgSource = $this->mapImageManager->getImagePath($this->map);
            if (!\file_exists($imgSource)) {
                $this->mapImageManager->generateImage($this->map);
            }
        } else {
            $imgSource = $this->webDir.'/'.$this->map->getImage();
        }

        $command
            ->convert($imgSource)
            ->background('black')
        ;
        if ($x && $y) {
            $command->crop(new Geometry(null, null, $x, $y));
        }
        $command
            ->resize($ratio.'%')
            ->extent(new Geometry($width, $height, null, null, Geometry::RATIO_MIN))
            ->thumbnail(new Geometry($width, $height))
            ->quality(95)
            ->file($imgOutput, false)
        ;

        if (false === $dry_run) {
            $response = $command->run($this->debug ? Command::RUN_DEBUG : Command::RUN_NORMAL);
            if ($response->hasFailed() || !\file_exists($imgOutput)) {
                throw new \RuntimeException("ImageMagick error.\n".$response->getOutput());
            }

            return $imgOutput;
        }

        return $command->getCommand();
    }

    private function mapDestinationName(int $zoom, int $x, int $y, int $width, int $height, bool $withImages = false): string
    {
        $id = $this->map->getId();
        $name = $this->map->getNameSlug();

        return $this->outputDirectory."/$id/custom/${name}_${zoom}_${x}_${y}_${width}_${height}".($withImages ? '_IM' : '').'.jpg';
    }
}

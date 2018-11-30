<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Admin\CustomController;

use Admin\Controller\AdminController;
use Agate\Entity\PortalElement;
use Behat\Transliterator\Transliterator;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PortalElementController extends AdminController
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $oneupFlysystem)
    {
        $this->filesystem = $oneupFlysystem;
    }

    protected function preUpdatePortalElementEntity(PortalElement $portalElement)
    {
        $this->uploadImageFile($portalElement, false);
    }

    protected function prePersistPortalElementEntity(PortalElement $portalElement)
    {
        $this->uploadImageFile($portalElement, true);
    }

    protected function uploadImageFile(PortalElement $portalElement, bool $required): void
    {
        $image = $portalElement->getImage();

        if (true === $required && !($image instanceof UploadedFile)) {
            // Can happen only if user have hijacked the form. Exception is nice because it prevents flushing the db.
            throw new \RuntimeException('File is mandatory.');
        }

        if ($image instanceof UploadedFile) {
            $newname = 'portal_element_'
                .Transliterator::urlize(\pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))
                .\uniqid('_pe_', true) // "pe" for "portal element"
                .'.'.$image->guessExtension()
            ;

            $stream = \fopen($image->getRealPath(), 'rb');

            $uploadResult = $this->filesystem->writeStream($newname, $stream, ['mimetype' => $image->getMimeType()]);

            if (false === $uploadResult) {
                throw new \RuntimeException('An error occured when uploading the file.');
            }

            $portalElement->setImageUrl($newname);
        }
    }
}

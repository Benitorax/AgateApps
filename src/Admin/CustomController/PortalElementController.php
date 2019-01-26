<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Admin\CustomController;

use Admin\Controller\AdminController;
use Agate\Entity\PortalElement;
use Behat\Transliterator\Transliterator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PortalElementController extends AdminController
{
    private $uploadPath;

    public function __construct(string $publicDir, string $portalElementUploadPath)
    {
        $this->uploadPath = \rtrim($publicDir, '\\/').'/'.\ltrim($portalElementUploadPath, '\\/');
    }

    protected function updateEntity($portalElement)
    {
        $this->uploadImageFile($portalElement, false);
        parent::updateEntity($portalElement);
    }

    protected function persistEntity($portalElement)
    {
        $this->uploadImageFile($portalElement, true);
        parent::persistEntity($portalElement);
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

            $image->move($this->uploadPath, $newname);

            if ($portalElement->getImageUrl() && \is_file($oldFile = ($this->uploadPath.'/'.$portalElement->getImageUrl()))) {
                \unlink($oldFile);
            }

            $portalElement->setImageUrl($newname);
        }
    }
}

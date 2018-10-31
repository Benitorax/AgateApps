<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Admin;

use Agate\Entity\PortalElement;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PortalElementAdminTest extends AbstractEasyAdminTest
{
    private const TEMPFILE_REGEX = '~^portal_element_[a-z0-9_-]+_pe_[a-z0-9]+\.[0-9]+\.jpeg$~isUu';

    private $file;

    /**
     * {@inheritdoc}
     */
    public function getEntityName()
    {
        return 'PortalElement';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return PortalElement::class;
    }

    protected function tearDown()
    {
        parent::tearDown();

        if (null !== $this->file && \file_exists($this->file)) {
            \unlink($this->file);
        }
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        static::resetDatabase();
    }

    public function testNewValidFileUpload()
    {
        static::resetDatabase();

        $this->createImage();

        $submitted = [
            'portal' => 'agate',
            'locale' => 'en',
            'image' => new UploadedFile($this->file, 'uploaded_file.jpg'),
            'title' => 'Portail Esteren',
            'subtitle' => 'sub',
            'buttonText' => 'button',
            'buttonLink' => '/',
        ];

        $expected = [
            'portal' => 'agate',
            'locale' => 'en',
            'title' => 'Portail Esteren',
            'subtitle' => 'sub',
            'buttonText' => 'button',
            'buttonLink' => '/',
        ];

        $search = [
            'portal' => 'agate',
            'locale' => 'en',
        ];

        /** @var PortalElement $entity */
        $entity = $this->submitData($submitted, $expected, $search, 'new');

        static::assertRegExp(self::TEMPFILE_REGEX, $entity->getImageUrl(), $entity->getImageUrl());

        $filePath = static::$kernel->getContainer()->getParameter('kernel.project_dir').'/public/uploads/'.$entity->getImageUrl();

        static::assertFileExists($filePath);

        \unlink($filePath);
    }

    public function testEditValidFileUpload()
    {
        static::resetDatabase();

        $this->createImage();

        $submitted = [
            'image' => new UploadedFile($this->file, 'uploaded_file.jpg'),
            'title' => 'Portail Esteren',
            'subtitle' => 'sub',
            'buttonText' => 'button',
            'buttonLink' => '/',
        ];

        $expected = [
            'id' => 1,
            'portal' => 'esteren',
            'locale' => 'fr',
            'title' => 'Portail Esteren',
            'subtitle' => 'sub',
            'buttonText' => 'button',
            'buttonLink' => '/',
        ];

        $search = [
            'portal' => 'esteren',
            'locale' => 'fr',
        ];

        /** @var PortalElement $entity */
        $entity = $this->submitData($submitted, $expected, $search, 'edit');

        static::assertRegExp(self::TEMPFILE_REGEX, $entity->getImageUrl(), $entity->getImageUrl());

        $filePath = static::$kernel->getContainer()->getParameter('kernel.project_dir').'/public/uploads/'.$entity->getImageUrl();

        static::assertFileExists($filePath);

        \unlink($filePath);
    }

    /**
     * {@inheritdoc}
     */
    public function provideListingFields()
    {
        return [
            'id',
            'portal',
            'locale',
            'imageUrl',
            'title',
            'subtitle',
            'buttonText',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function provideNewFormData()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function provideEditFormData()
    {
        return false;
    }

    private function createImage(int $width = 1900, $height = 900)
    {
        $this->file = \tempnam(\sys_get_temp_dir(), 'portal_test').'.jpg';

        \imagejpeg(\imagecreate($width, $height), $this->file);
    }

    protected static function resetDatabase()
    {
        parent::resetDatabase();

        $kernel = static::bootKernel();

        $class = PortalElement::class;

        $kernel->getContainer()->get('doctrine.orm.entity_manager')
            ->createQuery(<<<DQL
                DELETE FROM {$class} element 
                WHERE element.portal = :portal 
                AND element.locale = :locale
DQL
)
            ->setParameter('portal', 'agate')
            ->setParameter('locale', 'en')
            ->execute()
        ;

        static::ensureKernelShutdown();
        static::$kernel = null;
    }
}

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

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Tests\WebTestCase as PiersTestCase;

abstract class AbstractEasyAdminTest extends WebTestCase
{
    use PiersTestCase {
        getClient as baseGetClient;
    }

    /**
     * Returns the entity name in the backend.
     *
     * @return string
     */
    abstract public function getEntityName();

    /**
     * The entity full qualified class name to be used when managing entities.
     *
     * @return string
     */
    abstract public function getEntityClass();

    /**
     * Returns the list of fields you expect to see in the backend.
     * Return "false" if you don't want to test native listing.
     *
     * @return array
     */
    abstract public function provideListingFields();

    /**
     * A simple array of data to submit in the "new" form.
     * Keys of the array must correspond to the property field as specified in EasyAdmin config.
     * Return "false" if you don't want to test "new" form.
     *
     * @return array
     */
    abstract public function provideNewFormData();

    /**
     * A simple object to submit in the "edit" form.
     * Keys of the array must correspond to the property field as specified in EasyAdmin config.
     * Must specify an "id" attribute, else it will fail.
     * To be sure the test works, you might need to add fixtures with proper ID forced in the database.
     * Return "false" if you don't want to test "edit" form.
     *
     * @return array
     */
    abstract public function provideEditFormData();

    /**
     * Provides an ID to test the "delete" action.
     * To be sure the test works, you might need to add fixtures with proper ID forced in the database.
     * Return "false" if you don't want to test "delete" form.
     *
     * @return int
     */
    public function provideIdToDelete()
    {
        return false;
    }

    public function testListingFields()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/fr/?entity='.$this->getEntityName().'&action=list');

        $wishedColumns = $this->provideListingFields();

        // False means that we do not ever want to test this feature.
        // Allows a cleaner phpunit output.
        if (false === $wishedColumns) {
            static::assertTrue(true);

            return;
        }

        if (!$wishedColumns) {
            static::markTestIncomplete('No columns to test the listing page.');
        }

        static::assertSame(200, $client->getResponse()->getStatusCode(), $this->getEntityName());

        /** @var Crawler|\DOMElement[] $nodeHeaders */
        $nodeHeaders = $crawler->filter('#main table thead tr th[data-property-name]');

        static::assertCount(\count($wishedColumns), $nodeHeaders, $this->getEntityName());

        foreach ($nodeHeaders as $k => $node) {
            static::assertArrayHasKey($k, $wishedColumns, $this->getEntityName());
            static::assertSame($wishedColumns[$k], $node->getAttribute('data-property-name'), $this->getEntityName());
        }

        foreach ($wishedColumns as $columnName) {
            static::assertSame(1, $crawler->filter('#main table thead tr th[data-property-name="'.$columnName.'"]')->count(), 'Column '.$columnName.' in title. ['.$this->getEntityName().']');
        }
    }

    public function testListingContentsIsNotEmpty()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/fr/?entity='.$this->getEntityName().'&action=list');

        static::assertSame(200, $client->getResponse()->getStatusCode(), $this->getEntityName()."\n".$crawler->filter('title')->text());

        $count = $crawler->filter('#main table tr[data-id]')->count();

        if (0 === $count) {
            static::markTestIncomplete('No data to test the "list" action for the entity "'.$this->getEntityName().'"');
        }
    }

    public function testNewAction()
    {
        $data = $this->provideNewFormData();

        // False means that we do not ever want to test this feature.
        // Allows a cleaner phpunit output.
        if (false === $data) {
            static::assertTrue(true);

            return;
        }

        $this->submitData($data, $data, 'new');
    }

    /**
     * @depends testNewAction
     */
    public function testEditAction()
    {
        $data = $this->provideEditFormData();

        // False means that we do not ever want to test this feature.
        // Allows a cleaner phpunit output.
        if (false === $data) {
            static::assertTrue(true);

            return;
        }
        $this->submitData($data, $data, 'edit');
    }

    /**
     * @depends testNewAction
     */
    public function testDeleteAction()
    {
        $id = $this->provideIdToDelete();

        // False means that we do not ever want to test this feature.
        // Allows a cleaner phpunit output.
        if (false === $id) {
            static::assertTrue(true);

            return;
        }

        if (!$id) {
            static::markTestIncomplete('No data to test the "delete" action for entity "'.$this->getEntityName().'".');
        }

        $client = $this->getClient();

        // We'll make the DELETE request starting from the EDIT page.

        $crawler = $client->request('DELETE', '/fr/?action=edit&id='.$id.'&entity='.$this->getEntityName().'&referer=/');

        $deleteForm = $crawler->filter('#delete_form_submit');

        static::assertSame(1, $deleteForm->count(), $this->getEntityName());

        $form = $deleteForm->form();

        $client->submit($form);

        // If redirects to list, it means it's correct, else it would redirect to "list" action.
        static::assertSame(302, $client->getResponse()->getStatusCode(), $this->getEntityName());
        static::assertSame('/', $client->getResponse()->headers->get('location'), $this->getEntityName());

        /** @var EntityManager $em */
        $em = $client->getContainer()->get('doctrine')->getManager();

        $object = $em->find($this->getEntityClass(), $id);

        static::assertFalse((bool) $object, $this->getEntityName());
    }

    protected function submitData(array $dataToSubmit, array $expectedData, string $view)
    {
        $id = $dataToSubmit['id'] ?? $expectedData['id'] ?? null;
        if ('edit' === $view && !$id) {
            static::fail('You must specify an ID for the edit mode.');

            return;
        }

        $client = $this->getClient();

        /** @var EntityManager $em */
        $em = $client->getContainer()->get('doctrine')->getManager();

        $crawler = $client->request('GET', "/fr/?action=$view&id=$id&entity=".$this->getEntityName());

        static::assertSame(200, $client->getResponse()->getStatusCode(), $this->getEntityName());

        $formEntityFieldName = \mb_strtolower($this->getEntityName());

        /** @var Crawler $formNode */
        $formNode = $crawler->filter("#$view-$formEntityFieldName-form");

        static::assertSame(1, $formNode->count(), $this->getEntityName());

        $form = $formNode->form();

        foreach ($dataToSubmit as $field => $expectedValue) {
            if ('id' === $field) {
                continue;
            }
            $form->get($formEntityFieldName.'['.$field.']')->setValue($expectedValue);
        }

        $crawler = $client->submit($form);

        $response = $client->getResponse();

        $message = '';
        // If redirects to list, it means it's correct, else it would redirect to "new" action.
        if (200 === $response->getStatusCode()) {
            $errors = $crawler->filter('.error-block');
            foreach ($errors as $error) {
                $message .= "\n".\trim($error->textContent);
            }
        }
        static::assertSame(302, $response->getStatusCode(), "Not redirecting after submitting $view action ".$this->getEntityName().$message);
        static::assertSame('/fr/?action=list&entity='.$this->getEntityName(), $response->headers->get('location'), $this->getEntityName());

        $crawler->clear();
        $client->followRedirect();

        static::assertSame(200, $client->getResponse()->getStatusCode(), $this->getEntityName());

        if (null !== $id) {
            // Test the new entity corresponds.
            $lastEntity = $em->find($this->getEntityClass(), $id);
        } else {
            // Test that the last inserted ID corresponds.
            $lastEntity = $em
                ->getRepository($this->getEntityClass())
                ->createQueryBuilder('entity')
                ->orderBy('entity.id', 'desc')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

        static::assertNotNull($lastEntity);

        foreach ($expectedData as $field => $expectedValue) {
            $methodExists = false;
            $methodName = null;

            if (\method_exists($lastEntity, 'get'.\ucfirst($field))) {
                $methodExists = true;
                $methodName = 'get'.\ucfirst($field);
            } elseif (\method_exists($lastEntity, 'is'.\ucfirst($field))) {
                $methodExists = true;
                $methodName = 'is'.\ucfirst($field);
            } elseif (\method_exists($lastEntity, 'has'.\ucfirst($field))) {
                $methodExists = true;
                $methodName = 'has'.\ucfirst($field);
            }

            if ($methodExists) {
                $valueToCompare = $lastEntity->$methodName();
                static::assertSame($expectedValue, $valueToCompare, 'Error for class property '.$this->getEntityName().'::$'.$field);
            } else {
                static::fail('No getter found for property '.$this->getEntityName().'::$'.$field.'.');
            }
        }

        return $lastEntity;
    }

    /**
     * Overrides classic client behavior to be sure we have a client that points to the backend.
     *
     * @param string       $host
     * @param array|string $tokenRoles
     *
     * @return Client
     */
    protected function getClient($host = null, array $kernelOptions = [], $tokenRoles = [], array $server = [])
    {
        if (null === $host) {
            $host = 'back.esteren.docker';
        }

        if (\is_string($tokenRoles)) {
            $tokenRoles = [$tokenRoles];
        }

        if (0 === \count($tokenRoles)) {
            $tokenRoles[] = 'ROLE_ADMIN';
        }

        return $this->baseGetClient($host, $kernelOptions, (array) $tokenRoles, $server);
    }
}

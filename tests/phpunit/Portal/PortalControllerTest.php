<?php

namespace Tests\Portal;

use Doctrine\ORM\EntityManager;
use Orbitale\Bundle\CmsBundle\Entity\Page;
use Tests\WebTestCase;

class PortalControllerTest extends WebTestCase
{
    /**
     * @see GeneratorController::indexAction
     */
    public function testIndexWithNoPage()
    {
        $client = static::getClient('portal.esteren.dev');

        $client->request('GET', '/fr/');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @see GeneratorController::indexAction
     */
    public function testIndexWithHomepage()
    {
        $client = static::getClient('www.esteren.dev');

        $page = new Page();
        $page
            ->setHomepage(true)
            ->setHost('www.esteren.dev')
            ->setTitle('Homepage test')
            ->setContent('<h2>This tag is only here for testing</h2>')
            ->setSlug('homepage-test')
            ->setLocale('fr')
            ->setEnabled(true)
        ;

        /** @var EntityManager $em */
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $em->persist($page);
        $em->flush();

        $client->restart();

        $crawler = $client->request('GET', '/fr/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $this->assertEquals($page->getTitle(), trim($crawler->filter('#content section article h1')->html()));
        $this->assertContains($page->getContent(), trim($crawler->filter('#content section article')->html()));
    }

    /**
     * @see GeneratorController::indexAction
     */
    public function testIndexWithOnePage()
    {
        $client = static::getClient('www.esteren.dev');

        $page = new Page();
        $page
            ->setHost('www.esteren.dev')
            ->setTitle('Page test')
            ->setContent('<h2>This tag is only here for testing</h2>')
            ->setSlug('page-test')
            ->setLocale('fr')
        ;

        /** @var EntityManager $em */
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $em->persist($page);
        $em->flush();

        // First, we check that "enabled" is false by default
        $client->request('GET', '/fr/page-test');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $page->setEnabled(true);
        $em->flush($page);

        $client->restart();

        $crawler = $client->request('GET', '/fr/page-test');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $this->assertEquals($page->getTitle(), trim($crawler->filter('#content section article h1')->html()));
        $this->assertContains($page->getContent(), trim($crawler->filter('#content section article')->html()));
    }

}

<?php

namespace App\Tests\Controller;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    protected $client;

    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->databaseTool->loadAliceFixture([
            \dirname(__DIR__).'/Fixtures/UserTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/ArticleTestFixtures.yaml',
        ]);
    }

    public function testHomePageResponse()
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    // public function testHomePageHeadingOne()
    // {
    //     $this->client->request('GET', '/');
    //     $this->assertSelectorTextContains('h1', 'Home', 'error -> pas le titre home sur cette page');
    // }

    // public function testHomePageCountain6Articles()
    // {
    //     $crawler = $this->client->request('GET', '/');

    //     $this->assertCount(6, $crawler->filter('.blog-card'), 'il n\'y as pas 6 articles');
    // }

    // protected function tearDown(): void
    // {
    //     parent::tearDown();
    //     unset($this->databaseTool);
    // }
}

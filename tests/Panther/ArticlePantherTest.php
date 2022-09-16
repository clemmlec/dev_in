<?php

namespace App\Tests\Panther;

use Facebook\WebDriver\WebDriverBy;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\Panther\PantherTestCase;

class ArticlePantherTest extends PantherTestCase
{
    protected $client;

    protected $databaseTool;

    protected function setUp(): void
    {
        $this->client = self::createPantherClient();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->databaseTool->loadAliceFixture([
            \dirname(__DIR__).'/Fixtures/UserTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/TagTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/ArticleTestFixtures.yaml',
        ]);
    }

    public function testArticleNumberOnListePage()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertCount(20, $crawler->filter('.card-article'));
    }

    // public function testArticleShowMoreButton()
    // {
    //     $crawler = $this->client->request('GET', '/articles');

    //     $this->client->waitFor('.btn-show-more', 10);

    //     $this->client->executeScript("document.querySelector('.btn-show-more').click()");

    //     $this->client->waitForEnabled('.btn-show-more', 10);

    //     $crawler = $this->client->refreshCrawler();

    //     $this->assertCount(12, $crawler->filter('.blog-list .blog-card'));
    // }

    // public function testArticleSearchTitle()
    // {
    //     $crawler = $this->client->request('GET', '/articles');

    //     $this->client->waitFor('.form-filter', 5);

    //     $search = $this->client->findElement(WebDriverBy::cssSelector('#query'));

    //     $search->sendKeys('Titre - 3');

    //     $this->client->waitFor('.content-response', 5);

    //     // for flip content delay
    //     sleep(1);

    //     $crawler = $this->client->refreshCrawler();

    //     $this->assertCount(1, $crawler->filter('.blog-list .blog-card'));
    // }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

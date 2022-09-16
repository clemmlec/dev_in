<?php

namespace App\Tests\Entity;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use App\Tests\Utils\AssertTestTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleTest extends KernelTestCase
{
    use AssertTestTrait;

    protected $databaseTool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testRepositoryCount()
    {
        $articles = $this->databaseTool->loadAliceFixture([
            \dirname(__DIR__).'/Fixtures/UserTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/TagTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/ArticleTestFixtures.yaml',
        ]);

        $articles = self::getContainer()->get(ArticleRepository::class)->count([]);
        $this->assertSame(20, $articles, "Il n'y a pas le bon nombre d'article");
    }

    public function getEntity()
    {
        $user = self::getContainer()->get(UserRepository::class)->find(1);
        $tag = self::getContainer()->get(CategorieRepository::class)->find(1);

        return (new Article())
            ->setNom('Article')
            ->setDescription('Description de test en contenur valide')
            ->setUserId($user)
            ->setVisible(true)
            ->setCategorieId($tag)
        ;
    }

    public function testValideArticleEntity()
    {
        $this->assertHasErrors($this->getEntity());
    }

    // public function testNomUniqueTitleArticle()
    // {
    //     $article = $this->getEntity()
    //         ->setTitre('Titre - 1');
    //     $this->assertHasErrors($article, 1);
    // }

    // public function testMinTitleArticle()
    // {
    //     $article = $this->getEntity()
    //         ->setTitre('Ti');
    //     $this->assertHasErrors($article, 1);
    // }

    // public function testMaxTitleArticle()
    // {
    //     $article = $this->getEntity()
    //         ->setTitre('TigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdfdfdfdffdfdfdfdfdgjgjgjgjgjgjgjgjgjgjgjetrhfkldsjfh');
    //     $this->assertHasErrors($article, 1);
    // }

    // public function testMinContentArticle()
    // {
    //     $article = $this->getEntity()
    //         ->setContent('Tiefee');
    //     $this->assertHasErrors($article, 1);
    // }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

<?php

namespace App\Tests\Entity;

use App\Entity\Meme;
use App\Repository\MemeRepository;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use App\Tests\Utils\AssertTestTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MemeTest extends KernelTestCase
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
        $memes = $this->databaseTool->loadAliceFixture([
            \dirname(__DIR__).'/Fixtures/UserTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/TagTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/MemeTestFixtures.yaml',
        ]);

        $memes = self::getContainer()->get(MemeRepository::class)->count([]);
        $this->assertSame(20, $memes, "Il n'y a pas le bon nombre d'meme");
    }

    public function getEntity()
    {
        $user = self::getContainer()->get(UserRepository::class)->find(1);
        $tag = self::getContainer()->get(CategorieRepository::class)->find(1);

        return (new Meme())
            ->setNom('Meme')
            ->setDescription('Description de test en contenur valide')
            ->setUserId($user)
            ->setVisible(true)
            ->setCategorieId($tag)
        ;
    }

    public function testValideMemeEntity()
    {
        $this->assertHasErrors($this->getEntity());
    }

    // public function testNomUniqueTitleMeme()
    // {
    //     $meme = $this->getEntity()
    //         ->setTitre('Titre - 1');
    //     $this->assertHasErrors($meme, 1);
    // }

    // public function testMinTitleMeme()
    // {
    //     $meme = $this->getEntity()
    //         ->setTitre('Ti');
    //     $this->assertHasErrors($meme, 1);
    // }

    // public function testMaxTitleMeme()
    // {
    //     $meme = $this->getEntity()
    //         ->setTitre('TigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdfdfdfdffdfdfdfdfdgjgjgjgjgjgjgjgjgjgjgjetrhfkldsjfh');
    //     $this->assertHasErrors($meme, 1);
    // }

    // public function testMinContentMeme()
    // {
    //     $meme = $this->getEntity()
    //         ->setContent('Tiefee');
    //     $this->assertHasErrors($meme, 1);
    // }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

<?php

namespace App\Tests\Entity;

use App\Entity\Subject;
use App\Repository\ForumRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Tests\Utils\AssertTestTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SubjectTest extends KernelTestCase
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
        $subjects = $this->databaseTool->loadAliceFixture([
            \dirname(__DIR__).'/Fixtures/UserTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/TagTestFixtures.yaml',
            \dirname(__DIR__).'/Fixtures/SubjectTestFixtures.yaml',
        ]);

        $subjects = self::getContainer()->get(SubjectRepository::class)->count([]);
        $this->assertSame(20, $subjects, "Il n'y a pas le bon nombre d'subject");
    }

    public function getEntity()
    {
        $user = self::getContainer()->get(UserRepository::class)->find(1);
        $tag = self::getContainer()->get(ForumRepository::class)->find(1);

        return (new Subject())
            ->setNom('Subject')
            ->setDescription('Description de test en contenur valide')
            ->setUser($user)
            ->setActive(true)
            ->setForum($tag)
        ;
    }

    public function testValideSubjectEntity()
    {
        $this->assertHasErrors($this->getEntity());
    }

    // public function testNomUniqueTitleSubject()
    // {
    //     $subject = $this->getEntity()
    //         ->setTitre('Titre - 1');
    //     $this->assertHasErrors($subject, 1);
    // }

    // public function testMinTitleSubject()
    // {
    //     $subject = $this->getEntity()
    //         ->setTitre('Ti');
    //     $this->assertHasErrors($subject, 1);
    // }

    // public function testMaxTitleSubject()
    // {
    //     $subject = $this->getEntity()
    //         ->setTitre('TigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdfdfdfdffdfdfdfdfdgjgjgjgjgjgjgjgjgjgjgjetrhfkldsjfh');
    //     $this->assertHasErrors($subject, 1);
    // }

    // public function testMinContentSubject()
    // {
    //     $subject = $this->getEntity()
    //         ->setContent('Tiefee');
    //     $this->assertHasErrors($subject, 1);
    // }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Utils\AssertTestTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
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
        $users = $this->databaseTool->loadAliceFixture([
            \dirname(__DIR__).'/Fixtures/UserTestFixtures.yaml',
        ]);

        $users = self::getContainer()->get(UserRepository::class)->count([]);
        $this->assertSame(7, $users, "Il n'y a pas le bon nombre de user");
    }

    public function getEntity()
    {
        return (new User())
            ->setName('Clem')
            ->setEmail('email@test.fr')
            ->setPassword('123456Kk')
            ->setRgpd(true)
        ;
    }

    // public function testValideUserEntity()
    // {
    //     $this->assertHasErrors($this->getEntity());
    // }

    // public function testEmailUniqueUser()
    // {
    //     $user = $this->getEntity()
    //         ->setEmail('clement@test.com');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testArobaseEmailUser()
    // {
    //     $user = $this->getEntity()
    //         ->setEmail('clementtest.com');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testGoodEmailUser()
    // {
    //     $user = $this->getEntity()
    //         ->setEmail('cleement@test.com');
    //     $this->assertHasErrors($user, 0);
    // }

    // public function testMinPasswordUser()
    // {
    //     $user = $this->getEntity()
    //         ->setPassword('123445');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testMajPasswordUser()
    // {
    //     $user = $this->getEntity()
    //         ->setPassword('123456mmd');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testGoodPasswordUser()
    // {
    //     $user = $this->getEntity()
    //         ->setPassword('123456mmdDD');
    //     $this->assertHasErrors($user, 0);
    // }

    // public function testMaxEmailUser()
    // {
    //     $user = $this->getEntity()
    //         ->setEmail('asedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderfasedasderf@test.com');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testmaxPrenomUser()
    // {
    //     $user = $this->getEntity()
    //         ->setPrenom('TigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdfdfdfdffdfdfdfdfdgjgjgjgjgjgjgjgjgjgjgjetrhfkldsjfh');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testmaxNomUser()
    // {
    //     $user = $this->getEntity()
    //         ->setNom('jgjTigjgjgfdsdfsddfffssfdsdjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgj');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testZipCodeUser()
    // {
    //     $user = $this->getEntity()
    //         ->setZipCode('232523');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testLettreZipCodeUser()
    // {
    //     $user = $this->getEntity()
    //         ->setZipCode('aserd');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testMaxAddressUser()
    // {
    //     $user = $this->getEntity()
    //         ->setAddress('jgjTigjgjgfdsdfsddfffssfdsdjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgjjgjTigjgjgfdsdfsddfffssfdsdjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjTigjgjgfdsdfsddfffssfdsdjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgj');
    //     $this->assertHasErrors($user, 1);
    // }

    // public function testMaxVilleUser()
    // {
    //     $user = $this->getEntity()
    //         ->setVille('jgjTigjgjgfdsdfsddfffssfdsdjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgjjgjTigjgjgfdsdfsddfffssfdsdjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjgjgjTigjgjgfdsdfsddfffssfdsdjgjgjgjgjgjgjgjgjgjgjgjTigjgjgjgjjgjgjgjgjgjgjgjgjTigjgjgjdgjgjgjgjgjgjgj');
    //     $this->assertHasErrors($user, 1);
    // }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

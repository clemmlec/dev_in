<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security Controller Test.
 */
class SecurityControllerTest extends WebTestCase
{
    protected $client;

    protected $databaseTool;

    protected $userRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->userRepository = self::getContainer()->get(UserRepository::class);

        $this->databaseTool->loadAliceFixture([
            \dirname(__DIR__).'/Fixtures/UserTestFixtures.yaml',
        ]);
    }

    public function testLoginPageResponse()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    // public function testLoginPageHeadingOne()
    // {
    //     $this->client->request('GET', '/login');
    //     $this->assertSelectorTextContains('h1', 'Se connecter', 'error -> pas le titre home sur cette page');
    // }

    // public function testAdminSubjectNotConnected()
    // {
    //     $this->client->request('GET', '/admin/subject');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    // }

    // public function testAdminUserNotConnected()
    // {
    //     $this->client->request('GET', '/admin/user');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    // }

    // public function testAdminSubjectBadLoggedIn()
    // {
    //     $user = $this->userRepository->find(3);

    //     $this->client->loginUser($user);

    //     $this->client->request('GET', '/admin/subject');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    // }

    // public function testAdminUserBadLoggedIn()
    // {
    //     $user = $this->userRepository->find(3);

    //     $this->client->loginUser($user);

    //     $this->client->request('GET', '/admin/user');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    // }

    // public function testAdminSubjectGoodLoggedIn()
    // {
    //     // $user = $this->userRepository->find(1);
    //     $userAdmin = $this->userRepository->findOneByEmail('clement@test.com');

    //     $this->client->loginUser($userAdmin);

    //     $this->client->request('GET', '/admin/subject');
    //     $this->assertResponseIsSuccessful();
    // }

    // public function testAdminUserGoodLoggedIn()
    // {
    //     // $user = $this->userRepository->find(1);
    //     $userAdmin = $this->userRepository->findOneByEmail('clement@test.com');

    //     $this->client->loginUser($userAdmin);

    //     $this->client->request('GET', '/admin/user');
    //     $this->assertResponseIsSuccessful();
    // }

    // public function testEditorUserBadLoggedIn()
    // {
    //     $user = $this->userRepository->find(2);

    //     $this->client->loginUser($user);

    //     $this->client->request('GET', '/admin/user');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    // }

    // public function testEditorSubjectGoddLoggedIn()
    // {
    //     $user = $this->userRepository->find(2);

    //     $this->client->loginUser($user);

    //     $this->client->request('GET', '/admin/subject');
    //     $this->assertResponseIsSuccessful();
    // }

    // // public function testEditorForumGoddLoggedIn()
    // // {
    // //     $user = $this->userRepository->find(2);

    // //     $this->client->loginUser($user);

    // //     $this->client->request('GET', '/admin/forum');
    // //     $this->assertResponseIsSuccessful();
    // // }

    // public function testRegisterPageResponse()
    // {
    //     $this->client->request('GET', '/register');
    //     $this->assertResponseIsSuccessful();
    // }

    // // public function testRegisterPageHeadingOne()
    // // {
    // //     $this->client->request('GET', '/register');
    // //     $this->assertSelectorTextContains('h1', 'Nouvel utilisateur', 'error -> pas le titre home sur cette page');
    // // }

    // public function testSubmitValideRegisterFormPage()
    // {
    //     $crawler = $this->client->request('GET', '/register');

    //     $form = $crawler->selectButton('Save')->form([
    //         'user[nom]' => 'Jim',
    //         'user[prenom]' => 'K',
    //         'user[email]' => 'mail@mail.com',
    //         'user[password]' => 'LemoDePass565',
    //         'user[address]' => 'L\'adresse',
    //         'user[zipCode]' => '56552',
    //         'user[ville]' => 'La ville',
    //     ]);

    //     $this->client->submit($form);

    //     $newUser = $this->userRepository->findOneByEmail('mail@mail.com');

    //     if (!$newUser) {
    //         throw new Exception('User not created');
    //     }

    //     $this->assertResponseRedirects();
    // }

    // public function testSubmitInvalidMailRegisterFormPage()
    // {
    //     $crawler = $this->client->request('GET', '/register');

    //     $form = $crawler->selectButton('Save')->form([
    //         'user[nom]' => 'Jim',
    //         'user[prenom]' => 'K',
    //         'user[email]' => 'mail@mail',
    //         'user[password]' => 'LemoDePass565',
    //         'user[address]' => 'L\'adresse',
    //         'user[zipCode]' => '56552',
    //         'user[ville]' => 'La ville',
    //     ]);

    //     $this->client->submit($form);

    //     $this->assertSelectorTextContains('.invalid-feedback', 'Veuillez rentrer un email valide.');
    // }

    // public function testSubmitDoubleMailRegisterFormPage()
    // {
    //     $crawler = $this->client->request('GET', '/register');

    //     $form = $crawler->selectButton('Save')->form([
    //         'user[nom]' => 'Jim',
    //         'user[prenom]' => 'K',
    //         'user[email]' => 'clement@test.com',
    //         'user[password]' => 'LemoDePass565',
    //         'user[address]' => 'L\'adresse',
    //         'user[zipCode]' => '56552',
    //         'user[ville]' => 'La ville',
    //     ]);

    //     $this->client->submit($form);

    //     $this->assertSelectorTextContains('.invalid-feedback', 'cet email est déjà utilisé');
    // }

    // public function testSubmitInvalidPasswordRegisterFormPage()
    // {
    //     $crawler = $this->client->request('GET', '/register');

    //     $form = $crawler->selectButton('Save')->form([
    //         'user[nom]' => 'Jim',
    //         'user[prenom]' => 'K',
    //         'user[email]' => 'clemesdfsfsfnt@test.com',
    //         'user[password]' => 'LemoD',
    //         'user[address]' => 'L\'adresse',
    //         'user[zipCode]' => '56552',
    //         'user[ville]' => 'La ville',
    //     ]);

    //     $this->client->submit($form);

    //     $this->assertSelectorTextContains('.invalid-feedback', 'Votre mot de passe doit comporter au moins 6 caractères, une lettre majuscule, une lettre miniscule et 1 chiffre sans espace blanc');
    // }

    // public function testSubmitInvalidZipCodeRegisterFormPage()
    // {
    //     $crawler = $this->client->request('GET', '/register');

    //     $form = $crawler->selectButton('Save')->form([
    //         'user[nom]' => 'Jim',
    //         'user[prenom]' => 'K',
    //         'user[email]' => 'clemefgfgfsdfsfsfnt@test.com',
    //         'user[password]' => 'Lem45545oD',
    //         'user[address]' => 'L\'adresse',
    //         'user[zipCode]' => '5655fd2',
    //         'user[ville]' => 'La ville',
    //     ]);

    //     $this->client->submit($form);

    //     $this->assertSelectorTextContains('.invalid-feedback', 'Veuillez rentrer un code postal valide.');
    // }

    // public function testSubmitInvalidVilleRegisterFormPage()
    // {
    //     $crawler = $this->client->request('GET', '/register');

    //     $form = $crawler->selectButton('Save')->form([
    //         'user[nom]' => 'Jim',
    //         'user[prenom]' => 'K',
    //         'user[email]' => 'clemefgfgfsdfsfsfnt@test.com',
    //         'user[password]' => 'Lem45545oD',
    //         'user[address]' => 'L\'adresse',
    //         'user[zipCode]' => '56552',
    //         'user[ville]' => 'La villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa villeLa ville',
    //     ]);

    //     $this->client->submit($form);

    //     $this->assertSelectorTextContains('.invalid-feedback', 'Votre ville ne dois pas dépasser 255 caracteres');
    // }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

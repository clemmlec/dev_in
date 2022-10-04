<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserTypeEdit;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if ther one
        $error = $authenticationUtils->getLastAuthenticationError();

        // laste username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername,
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $em
    ) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            )
            ->setActive(true)
            ->setCredibility(0);

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Compte créer avec success');

            return $this->redirectToRoute('login');
        }

        return $this->renderForm('Security/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete', name: 'delete')]
    public function delete(Security $security, UserRepository $userRepo): Response
    {
        $user = $security->getUser();
        $this->container->get('security.token_storage')->setToken(null);

        $userRepo->remove($user, true);
        $this->addFlash('success', 'Votre compte utilisateur a bien été supprimé !');

        return $this->redirectToRoute('home');
    }

    // #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, User $user, UserRepository $userRepository, Security $security): Response
    // {
    //     if ($user != $security->getUser()){

    //         $this->addFlash('error', 'probleme d\'authentification');
    //         return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    //     }

    //     $form = $this->createForm(UserTypeEdit::class, $user);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $userRepository->add($user, true);

    //         return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('Security/edit.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }
}

<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ContactType;
use App\Form\UserTypeEdit;
use App\Services\MailerService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerService $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            $subject = 'Demande de contact sur votre site de '.$contactFormData['email'];
            $content = $contactFormData['nom']
                .' vous a envoyé le message suivant: '
                .$contactFormData['message'];
            $from = $contactFormData['email'];
            $mailer->sendEmail(subject: $subject, content: $content, from:$from);
            $this->addFlash('success', 'Votre message a été envoyé');

            return $this->redirectToRoute('contact');
        }

        return $this->renderForm('Security/contact.html.twig', [
            'form' => $form,
        ]);
    }
    
}

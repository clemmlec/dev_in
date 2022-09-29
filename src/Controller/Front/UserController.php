<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Follow;
use App\Form\UserTypeEdit;
use App\Repository\UserRepository;
use App\Repository\FollowRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findUsers(),
        ]);
    }

    #[Route('/followed', name: 'app_user_followed', methods: ['GET'])]
    public function followed(FollowRepository $followRepository, Security $security, Request $request): Response
    {   
        $user = $security->getUser();

        $form = $this->createForm(UserTypeEdit::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_followed.html.twig', [
            'followed' => $followRepository->findAllFollowed($security->getUser()->getId()),
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/followers', name: 'app_user_followers', methods: ['GET'])]
    public function followers(FollowRepository $followRepository, Security $security, Request $request): Response
    {   
        $user = $security->getUser();

        $form = $this->createForm(UserTypeEdit::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_followers.html.twig', [
            'followers' => $followRepository->findAllFollowers($security->getUser()->getId()),
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/sujet-favoris', name: 'app_user_subject-favoris', methods: ['GET'])]
    public function sujetFavoris(UserRepository $userRepo,Security $security, Request $request): Response
    {   
        $user = $security->getUser();

        $form = $this->createForm(UserTypeEdit::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_subjectFavoris.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/sujet-noter', name: 'app_user_subject-noter', methods: ['GET'])]
    public function sujetNoter(UserRepository $userRepo,Security $security, Request $request): Response
    {   
        $user = $security->getUser();

        $form = $this->createForm(UserTypeEdit::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_subjectNoter.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/article-favoris', name: 'app_user_article-favoris', methods: ['GET'])]
    public function articleFavoris(UserRepository $userRepo,Security $security, Request $request): Response
    {   
        $user = $security->getUser();

        $form = $this->createForm(UserTypeEdit::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_articleFavoris.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }


    #[Route('/sujet', name: 'app_user_subject', methods: ['GET'])]
    public function sujet(SubjectRepository $subjectRepository, Security $security, Request $request): Response
    {   
        $user = $security->getUser();

        $form = $this->createForm(UserTypeEdit::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_subjectPosted.html.twig', [
            'subjects' => $subjectRepository->findAllSubjectPosted($security->getUser()->getId()),
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/profil/{id}', name: 'app_user_profil', methods: ['GET', 'POST'])]
    public function profil(Request $request, ?User $user, Security $security, UserRepository $userRepository): Response
    {
        if (!$user instanceof User  || $user == null){

            $this->addFlash('error', 'probleme d\'authentification');
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        
        $userCollection = $userRepository->findOneById($user->getId());

        $form = $this->createForm(UserTypeEdit::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/follow/{id}', name: 'app_user_follow', methods: ['GET'])]
    public function followUser(?User $follow, UserRepository $userRepository, FollowRepository $friendsRepo, Security $security)
    {
        $user = $security->getUser();

        // dd($follow);
        // dd($user, $follow);

        if ($follow && $user) {
            $dejaAmis = $friendsRepo->findOneBy(['user' => $user, 'friend' => $follow]);
            if (!$dejaAmis) {
                $newFollow = new Follow();
                $newFollow->setUser($user)
                ->setFriend($follow);
                $friendsRepo->add($newFollow, true);

                return new Response('amitié créer', 201);
            }

            $friendsRepo->remove($dejaAmis, true);

            return new Response('amitié supprimée', 201);
        }

        return new Response('demande d\'amis non valide', 404);
    }

    #[Route('/style/{style}', name: 'app_user_style', methods: ['GET'])]
    public function styleUser(string $style, UserRepository $userRepository, Security $security)
    {
        $user = $security->getUser();

        if ($style && $user) {
            $user->setStyle($style);
            $userRepository->add($user, true);

            return new Response('style changé', 201);
        }

        return new Response('changement de style non valide', 404);
    }
}

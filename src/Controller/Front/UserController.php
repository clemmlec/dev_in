<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Follow;
use App\Form\SearchType;
use App\Filter\SearchData;
use App\Form\UserTypeEdit;
use App\Repository\UserRepository;
use App\Repository\FollowRepository;
use App\Repository\SubjectRepository;
use App\Repository\NoteSubjectRepository;
use App\Repository\ArticleLikedRepository;
use App\Repository\SubjectFavorisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository
        ) {
    }
    
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index( Request $request): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $forms = $this->createForm(SearchType::class, $data);
        $forms->handleRequest($request);

        $users = $this->userRepository->findUsers($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_users.html.twig', [
                    'users' => $users,
                ]),
                'pagination' => $this->renderView('Components/filter/_paginationUser.html.twig', [
                    'users' => $users,
                ]),
                'count' => $this->renderView('Components/filter/_countUser.html.twig', [
                    'users' => $users,
                ]),
                'pages' => ceil($users->getTotalItemCount() / $users->getItemNumberPerPage()),
            ]);
        }

        return $this->renderForm('user/index.html.twig', [
            'users' => $users,
            'forms' => $forms,
        ]);
    }

    #[Route('/followed/{id}', name: 'app_user_followed', methods: ['GET', 'POST'])]
    public function followed(
        ?User $user,
        FollowRepository $followRepository, 
        Security $security, 
        Request $request
        ): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $forms = $this->createForm(SearchType::class, $data);
        $forms->handleRequest($request);
        $followed =$followRepository->findAllFollowed($user,$data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_usersFollow.html.twig', [
                    'follow' => $followed,
                    'follower' => false
                ]),
                'pagination' => $this->renderView('Components/filter/_paginationUser.html.twig', [
                    'users' => $followed,
                ]),
                'count' => $this->renderView('Components/filter/_countUser.html.twig', [
                    'users' => $followed,
                ]),
                'pages' => ceil($followed->getTotalItemCount() / $followed->getItemNumberPerPage()),
            ]);
        }

        $form = $this->createForm(UserTypeEdit::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);
            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
      
        return $this->renderForm('user/_followed.html.twig', [
            'follow' => $followed,
            'user' => $user,
            'form' => $form,
            'forms' => $forms,
        ]);
    }

    #[Route('/followers/{id}', name: 'app_user_followers', methods: ['GET','POST'])]
    public function followers(
        ?User $user,
        FollowRepository $followRepository, 
        Security $security, 
        Request $request
        ): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $forms = $this->createForm(SearchType::class, $data);
        $forms->handleRequest($request);

        $follower =$followRepository->findAllFollowers($user,$data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_usersFollow.html.twig', [
                    'follow' => $follower,
                    'follower' => true
                ]),
                'pagination' => $this->renderView('Components/filter/_paginationUser.html.twig', [
                    'users' => $follower,
                ]),
                'count' => $this->renderView('Components/filter/_countUser.html.twig', [
                    'users' => $follower,
                ]),
                'pages' => ceil($follower->getTotalItemCount() / $follower->getItemNumberPerPage()),
            ]);
        }

        $form = $this->createForm(UserTypeEdit::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);
            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        
        return $this->renderForm('user/_followers.html.twig', [
            'follow' => $follower,
            'user' => $user,
            'form' => $form,
            'forms' => $forms
        ]);
    }

    #[Route('/sujet-favoris/{id}', name: 'app_user_subject-favoris', methods: ['GET','POST'])]
    public function sujetFavoris(
        ?User $user,
        SubjectFavorisRepository $subjectRepository,
        Security $security, 
        Request $request
        ): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $forms = $this->createForm(SearchType::class, $data);
        $forms->handleRequest($request);

        $subjects = $subjectRepository->getSubjectFavoris($user,$data);
        
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_subjectSwipers.html.twig', [
                    'subjects' => $subjects,
                ]),
                'pagination' => $this->renderView('Components/filter/_paginationSubject.html.twig', [
                    'subjects' => $subjects,
                ]),
                'count' => $this->renderView('Components/filter/_countSubject.html.twig', [
                    'subjects' => $subjects,
                ]),
                'pages' => ceil($subjects->getTotalItemCount() / $subjects->getItemNumberPerPage()),
            ]);
        }

        $form = $this->createForm(UserTypeEdit::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);
            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_subjectFavoris.html.twig', [
            'user' => $user,
            'form' => $form,
            'forms' => $forms,
            'subjects' => $subjects
        ]);
    }

    #[Route('/sujet-noter/{id}', name: 'app_user_subject-noter', methods: ['GET','POST'])]
    public function sujetNoter(
        ?User $user,
        NoteSubjectRepository $noteSubjectRepository,
        Security $security, 
        Request $request
        ): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $forms = $this->createForm(SearchType::class, $data);
        $forms->handleRequest($request);

        $subjects = $noteSubjectRepository->getSubjectNoter($user,$data);
        
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_subjectSwipers.html.twig', [
                    'subjects' => $subjects,
                ]),
                'pagination' => $this->renderView('Components/filter/_paginationSubject.html.twig', [
                    'subjects' => $subjects,
                ]),
                'count' => $this->renderView('Components/filter/_countSubject.html.twig', [
                    'subjects' => $subjects,
                ]),
                'pages' => ceil($subjects->getTotalItemCount() / $subjects->getItemNumberPerPage()),
            ]);
        }
        $form = $this->createForm(UserTypeEdit::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_subjectNoter.html.twig', [
            'user' => $user,
            'form' => $form,
            'subjects' => $subjects,
            'forms' => $forms,
        ]);
    }

    #[Route('/article-favoris/{id}', name: 'app_user_article-favoris', methods: ['GET','POST'])]
    public function articleFavoris(
        ?User $user,
        ArticleLikedRepository $articleLikedRepository,
        Security $security, 
        Request $request): Response
    {

        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $forms = $this->createForm(SearchType::class, $data);
        $forms->handleRequest($request);

        $articles = $articleLikedRepository->getArticleFavoris($user,$data);
        
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_articlesLiked.html.twig', [
                    'articles' => $articles,
                ]),
                'pagination' => $this->renderView('Components/filter/_pagination.html.twig', [
                    'articles' => $articles,
                ]),
                'count' => $this->renderView('Components/filter/_count.html.twig', [
                    'articles' => $articles,
                ]),
                'pages' => ceil($articles->getTotalItemCount() / $articles->getItemNumberPerPage()),
            ]);
        }

        $form = $this->createForm(UserTypeEdit::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);
            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_articleFavoris.html.twig', [
            'user' => $user,
            'form' => $form,
            'articles' => $articles,
            'forms' => $forms,
        ]);
    }

    #[Route('/sujet/{id}', name: 'app_user_subject', methods: ['GET','POST'])]
    public function sujet(
        ?User $user,
        SubjectRepository $subjectRepository, 
        Security $security, 
        Request $request
        ): Response
    {

        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $forms = $this->createForm(SearchType::class, $data);
        $forms->handleRequest($request);

        $subjects = $subjectRepository->findAllSubjectPosted($user,$data);
        
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_subjectsNoters.html.twig', [
                    'subjects' => $subjects,
                ]),
                'pagination' => $this->renderView('Components/filter/_paginationSubject.html.twig', [
                    'subjects' => $subjects,
                ]),
                'count' => $this->renderView('Components/filter/_countSubject.html.twig', [
                    'subjects' => $subjects,
                ]),
                'pages' => ceil($subjects->getTotalItemCount() / $subjects->getItemNumberPerPage()),
            ]);
        }

        $form = $this->createForm(UserTypeEdit::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/_subjectPosted.html.twig', [
            'subjects' => $subjects,
            'user' => $user,
            'form' => $form,
            'forms' => $forms
        ]);
    }

    #[Route('/profil/{id}', name: 'app_user_profil', methods: ['GET', 'POST'])]
    public function profil(
        Request $request, 
        ?User $user, 
        Security $security, 
        ): Response
    {
        if (!$user instanceof User ) {
            $this->addFlash('error', 'probleme d\'authentification');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(UserTypeEdit::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        $userConected = $security->getUser();
        if($user != $userConected ){
            $user->setPassword('');
            $user->setEmail('');
        }
        
        return $this->renderForm('user/profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/follow/{id}', name: 'app_user_follow', methods: ['GET'])]
    public function followUser(?User $follow, FollowRepository $friendsRepo, Security $security)
    {
        $user = $this->getUser();
        if(!$user) {
            $this->addFlash('error', 'Veuillez vous connecter pour ajouter un amis');

            return new Response('authentification requise', 403);
        }
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
    public function styleUser(string $style, Security $security)
    {
        $user = $this->getUser();

        if ($style && $user) {
            $user->setStyle($style);
            $this->userRepository->add($user, true);

            return new Response('style changé', 201);
        }

        return new Response('changement de style non valide', 404);
    }
}

<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SubjectRepository $subjectRepository, ArticleRepository $articleRepo, Security $security): Response
    {
        // $user = $security->getUser();
        // if ($user) {
        //     return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()]);
        // }
        $subjects = $subjectRepository->findLastSubject();
        $articles = $articleRepo->findLastArticles();

        return $this->render('index.html.twig', [
            'subjects' => $subjects,
            'articles' => $articles,
        ]);
    }

    #[Route('/mentionslegal', name: 'mentionslegal')]
    public function mentionslegal(): Response
    {
        return $this->render('mentionslegal.html.twig');
    }
}

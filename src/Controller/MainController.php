<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Subject;
use App\Form\CommentType;
use App\Form\SubjectType;
use App\Repository\ArticleRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SubjectRepository $subjectRepository,ArticleRepository $articleRepo,Security $security): Response
    {
        $user = $security->getUser();
        if ($user) {
            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()]);
        }
        $subjects = $subjectRepository->findRandSubject();
        $article = $articleRepo->findOneBy(array(), array('id' => 'desc'),1,0);
        return $this->render('index.html.twig', [
            'subjects' => $subjects,
            'article' => $article
        ]);
    }
}

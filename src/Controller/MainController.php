<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\Comment;
use App\Form\SubjectType;
use App\Form\CommentType;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SubjectRepository $subjectRepository,Security $security): Response
    {
        $user = $security->getUser();
        if ($user) {
            return $this->redirectToRoute('app_user_profil', ['id' => $user->getId()]);
        }
        $subjects = $subjectRepository->findRandSubject();
        return $this->render('index.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}

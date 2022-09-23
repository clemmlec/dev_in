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
    public function index(SubjectRepository $subjectRepository,): Response
    {
        
        $subjects = $subjectRepository->findRandSubject();
        return $this->render('index.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}

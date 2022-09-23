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
    public function index(Request $request, SubjectRepository $subjectRepository, Security $security): Response
    {
        $selectSubject = $subjectRepository->findActiveSubject();

        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject->setUser($security->getUser());
            $subjectRepository->add($subject, true);

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('index.html.twig', [
            'subjects' => $selectSubject,
            'form' => $form,
        ]);
    }
}

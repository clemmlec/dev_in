<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, ArticleRepository $articleRepository, Security $security): Response
    {
        $selectArticle = $articleRepository->findActiveArticle();
        // $commentaire = new Comment();
        // $formCom = $this->createForm(CommentType::class, $commentaire);

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUserId($security->getUser());
            $articleRepository->add($article, true);

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('index.html.twig', [
            'articles' => $selectArticle,
            // 'note' => $note,

            'form' => $form,
            // 'formCom' => $formCom,
        ]);
    }
}

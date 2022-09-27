<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index')]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $selectArticle = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $selectArticle,
        ]);
    }

        #[Route('/{id}', name: 'article_show', methods: ['GET','POST'])]
    public function show(?Article $article, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findArticleWithSameTags($article->getTags());

        return $this->renderForm('article/show.html.twig', [
            'article' => $article,
            'articles' => $articles
        ]);
    }
}

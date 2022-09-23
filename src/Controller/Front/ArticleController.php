<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
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
}

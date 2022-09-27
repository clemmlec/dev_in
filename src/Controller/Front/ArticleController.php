<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\ArticleLiked;
use App\Entity\ArticleSuggestion;
use App\Repository\ArticleRepository;
use App\Repository\ArticleLikedRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ArticleSuggestionRepository;
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

    #[Route('/liked/{id}', name: 'app_article_liked', methods: ['GET'])]
    public function likedArticle(?Article $article, ArticleLikedRepository $articleFavRepo, ArticleRepository $articleRepo, Security $security)
    {
        $articleFav = new ArticleLiked();
        $follow = $articleRepo->find($article);

        $user = $security->getUser();

        if ($follow && $user) {
            $dejaAmis = $articleFavRepo->findOneBy(['user' => $user, 'article' => $article]);
            if (!$dejaAmis) {
                $articleFav->setUser($user)
                ->setarticle($follow);
                $articleFavRepo->add($articleFav, true);

                return new Response('article ajouté', 201);
            }
            $articleFavRepo->remove($dejaAmis, true);

            return new Response('article retiré des favoris', 201);
        }

        return new Response('demande de favoris non valide', 404);
    }

    #[Route('/suggest/{id}/{message}', name: 'user.article.suggest', methods: ['GET'])]
    public function signalerarticle(?Article $article, String $message, Security $security, ArticleRepository $artRepo, ArticleSuggestionRepository $artSignalRepo)
    {
        $user = $security->getUser();

        if ($article && $user) {
            
            $newSignal = new ArticleSuggestion();
            $newSignal->setUser($user)
            ->setarticle($article)
            ->setMessage($message);
            $artSignalRepo->add($newSignal, true);

            return new Response('article signaler', 201);

        }

        return new Response('article non trouvé', 404);
    }

}

<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\ArticleLiked;
use App\Entity\ArticleSuggestion;
use App\Filter\SearchData;
use App\Form\SearchType;
use App\Repository\ArticleLikedRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleSuggestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index')]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        $selectArticle = $articleRepository->findArticle($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_articles.html.twig', [
                    'articles' => $selectArticle,
                ]),
                'pagination' => $this->renderView('Components/filter/_pagination.html.twig', [
                    'articles' => $selectArticle,
                ]),
                'count' => $this->renderView('Components/filter/_count.html.twig', [
                    'articles' => $selectArticle,
                ]),
                'pages' => ceil($selectArticle->getTotalItemCount() / $selectArticle->getItemNumberPerPage()),
            ]);
        }

        return $this->renderForm('article/index.html.twig', [
            'articles' => $selectArticle,
            'form' => $form,
        ]);
    }

        #[Route('/{id}/{slug}', name: 'article_show', methods: ['GET', 'POST'])]
    public function show(?Article $article,string $slug, ArticleRepository $articleRepository): Response
    {
        if(!$article instanceof Article) {
            $this->addFlash('error', 'Nous ne trouvons pas l\'article demandé');

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        $articles = $articleRepository->findArticleWithSameTags($article->getTags());

        return $this->renderForm('article/show.html.twig', [
            'article' => $article,
            'articles' => $articles,
        ]);
    }

    #[Route('/liked/{id}', name: 'app_article_liked', methods: ['GET'])]
    public function likedArticle(?Article $article, ArticleLikedRepository $articleFavRepo, ArticleRepository $articleRepo, Security $security): Response
    {
        $articleFav = new ArticleLiked();
        $follow = $articleRepo->find($article);

        $user = $security->getUser();
        if(!$user) {
            $this->addFlash('error', 'Veuillez vous connecter pour liker un article');

            return new Response('authentification requise', 403);
        }

        if ($follow) {
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
    public function signalerarticle(?Article $article, string $message, Security $security, ArticleRepository $artRepo, ArticleSuggestionRepository $artSignalRepo): Response
    {
        $user = $security->getUser();
        if(!$user) {
            $this->addFlash('error', 'Veuillez vous connecter pour liker un article');

            return new Response('authentification requise', 403);
        }
        if ($article) {
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

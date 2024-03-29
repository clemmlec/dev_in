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
    public function __construct(
        private ArticleRepository $articleRepository
        ) {
    }

    #[Route('/', name: 'app_article_index')]
    public function index(Request $request): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        $selectArticle = $this->articleRepository->findArticle($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_articles.html.twig', [
                    'articles' => $selectArticle,
                    'couper' => "couper"
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

    #[Route('/liked/{id}', name: 'app_article_liked', methods: ['GET'])]
    public function likedArticle(
        ?Article $article, 
        ArticleLikedRepository $articleFavRepo, 
        Security $security
    ): Response
    {
        $articleFav = new ArticleLiked();
        $articleExist = $this->articleRepository->find($article);

        $user = $security->getUser();
        if(!$user) {
            $this->addFlash('error', 'Veuillez vous connecter pour liker un article');

            return new Response('authentification requise', 403);
        }

        if (!$articleExist) {
            return new Response('demande de favoris non valide', 404);
        }

        $follow = $articleFavRepo->findOneBy(['user' => $user, 'article' => $article]);
        if (!$follow) {
            $articleFav->setUser($user)
                ->setarticle($articleExist);
            $articleFavRepo->add($articleFav, true);

            return new Response('article ajouté', 201);
        }
        $articleFavRepo->remove($follow, true);

        return new Response('article retiré des favoris', 201);
    }

    #[Route('/{id}/{slug}', name: 'article_show', methods: ['GET'])]
    public function show(?Article $article,string $slug): Response
    {
        if(!$article instanceof Article) {
            $this->addFlash('error', 'Nous ne trouvons pas l\'article demandé');

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        $articles = $this->articleRepository->findArticleWithSameTags($article->getTags());

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'articles' => $articles,
        ]);
    }
    
    #[Route('/suggest/{id}/{message}', name: 'user.article.suggest', methods: ['GET'])]
    public function signalerarticle(?Article $article, string $message, Security $security, ArticleSuggestionRepository $artSignalRepo): Response
    {
        $user = $security->getUser();
        if(!$user) {
            $this->addFlash('error', 'Veuillez vous connecter pour faire une suggestion');

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

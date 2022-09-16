<?php

namespace App\Controller\users;

use App\Entity\Article;
use App\Entity\ArticleFavoris;
use App\Entity\ArticleSignaler;
use App\Entity\NoteArticle;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleFavorisRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleSignalerRepository;
use App\Repository\CategorieRepository;
use App\Repository\NoteArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/article')]
class ArticleController extends AbstractController
{
    // #[Route('/', name: 'user_article_index', methods: ['GET', 'POST'])]
    // public function index(Request $request, ArticleRepository $articleRepository, Security $security ): Response
    // {

        //     $selectArticle = $articleRepository->findAll();

        //     $article = new Article();
        //     $form = $this->createForm(ArticleType::class, $article);
        //     $form->handleRequest($request);

        //     if ($form->isSubmitted() && $form->isValid()) {

        //         $article->setUserId($security->getUser());
        //         $articleRepository->add($article, true);

        //         return $this->redirectToRoute('user_article_index', [], Response::HTTP_SEE_OTHER);
        //     }

        //     return $this->renderForm('article/index.html.twig', [
        //         'articles' => $selectArticle,
        //         // 'note' => $note,

        //         'form' => $form,
        //     ]);

    // }

    #[Route('/note/{note}/{articleId}', name: 'app_article_note', methods: ['GET'])]
    public function noteArticle(int $note, int $articleId, ArticleRepository $articleRepository, NoteArticleRepository $noteRepo, Security $security)
    {
        $newNote = new NoteArticle();
        // $note = explode('-', $id)[0];
        // $articleId = explode('-', $id)[1];

        $article = $articleRepository->find($articleId);

        $user = $security->getUser();

        // dd($follow);
        // dd($user, $follow);
        if ($note < 0 || $note > 5) {
            return new Response('La note rentré n\est pas valide', 201);
        }
        if ($article && $user) {
            $dejaNoter = $noteRepo->findOneBy(['user' => $user, 'article' => $articleId]);
            if (!$dejaNoter) {
                $newNote->setUser($user)
                ->setArticle($article)
                ->setNote($note);

                $noteRepo->add($newNote, true);

                return new Response('note envoyer', 201);
            }

            return new Response('déjà noter preparer la modification', 201);
        }

        return new Response('note non valide', 404);
    }
    // #[Route('/new', name: 'user_article_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, CategorieRepository $categorieRepo ,ArticleRepository $articleRepository, Security $security): Response
    // {
        // dd($request);
        // $article = new Article();
        // $param=$request->request->all('article');
        // $categorie=$categorieRepo->find($param['categorie_id']);
        // // $imageFile=$request->request->all('imageFile');
        // dd( $request );

        // $form = $this->createForm(ArticleType::class, $article);
        // $form->handleRequest($request);

            // $article->setUserId($security->getUser())
                // ->setNom($param['nom'])
                // ->setDescription($param['description'])
                // ->setCategorieId($categorie)
                // ->setImageFile($imageName)
            //    ;
            // $articleRepository->add($article, true);
        //     $articleRepository->add($article, true);
            // $dejaAmis=$friendsRepo->findOneBy(array('user' => $user_id ,'friend' => $id));
            // if(!$dejaAmis){
                //  $newFollow->setUser($user)
                // ->setFriend($follow);
                // $articleRepository->add($article, true);

            // return new JsonResponse($article);
                // return new Response('articl créer', 201);
            // }else{

            // }

       // if ($form->isSubmitted() && $form->isValid()) {

        //     $article->setUserId($security->getUser());
        //     $articleRepository->add($article, true);

        //     return $this->redirectToRoute('user_article_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->renderForm('article/new.html.twig', [
        //     'article' => $article,
        //     'form' => $form,
        // ]);
    // }

    #[Route('/{id}', name: 'user_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/edit/name/{id}/{name}', name: 'user_articleName_edit', methods: ['GET'])]
    public function edit(int $id, string $name, ArticleRepository $articleRepository, Security $security): Response
    {
        $user = $security->getUser();
        $article = $articleRepository->find($id);
        if ($article->getUserId() !== $user) {
            return new Response('vous n\avez pas le droit de modifier cet article', 404);
        }
        if ($article) {
            $article->setNom($name);
            $articleRepository->add($article, true);

            return new Response('le nom d\'article à bien été modifié', 201);
        }

        return new Response('l\article n\'est pas disponible', 404);
    }

    #[Route('/edit/content/{id}/{content}', name: 'user_articleContent_edit', methods: ['GET'])]
    public function editContent(int $id, string $content, ArticleRepository $articleRepository, Security $security): Response
    {
        $user = $security->getUser();
        $article = $articleRepository->find($id);
        if ($article->getUserId() !== $user) {
            return new Response('vous n\avez pas le droit de modifier cet article', 404);
        }
        if ($article) {
            $article->setDescription($content);
            $articleRepository->add($article, true);

            return new Response('la description de l\'article à bien été modifié', 201);
        }

        return new Response('l\article n\'est pas disponible', 404);
    }

    // #[Route('/{id}/edit', name: 'user_article_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    // {
        //     $form = $this->createForm(ArticleType::class, $article);
        //     $form->handleRequest($request);

        //     if ($form->isSubmitted() && $form->isValid()) {
        //         $articleRepository->add($article, true);

        //         return $this->redirectToRoute('user_article_index', [], Response::HTTP_SEE_OTHER);
        //     }

        //     return $this->renderForm('article/edit.html.twig', [
        //         'article' => $article,
        //         'form' => $form,
        //     ]);
    // }

    #[Route('/{id}', name: 'user_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('user_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/follow/{id}', name: 'app_article_follow', methods: ['GET'])]
    public function followArticle(?Article $article, ArticleFavorisRepository $articleFavRepo, ArticleRepository $articleRepo, Security $security)
    {
        $articleFav = new ArticleFavoris();
        $follow = $articleRepo->find($article);

        $user = $security->getUser();

        if ($follow && $user) {
            $dejaAmis = $articleFavRepo->findOneBy(['user' => $user, 'article' => $article]);
            if (!$dejaAmis) {
                $articleFav->setUser($user)
                ->setArticle($follow);
                $articleFavRepo->add($articleFav, true);

                return new Response('article ajouté', 201);
            }
            $articleFavRepo->remove($dejaAmis, true);

            return new Response('article retiré des favoris', 201);
        }

        return new Response('demande de favoris non valide', 404);
    }

    #[Route('/signaler/{id}', name: 'user.article.signaler', methods: ['GET'])]
    public function signalerArticle(?Article $article, Security $security, ArticleRepository $artRepo, ArticleSignalerRepository $artSignalRepo)
    {
        $user = $security->getUser();
       
        if ($article && $user) {
            $dejaSignaler = $artSignalRepo->findOneBy(['user' => $user, 'article' => $article]);
            if (!$dejaSignaler) {
                $newSignal = new ArticleSignaler();
                $newSignal->setUser($user)
               ->setArticle($article);
                $artSignalRepo->add($newSignal, true);

                return new Response('article signaler', 201);
            }
            $artSignalRepo->remove($dejaSignaler, true);

            return new Response('article dé signaler', 201);
        }

        return new Response('article non trouvé', 404);
    }
}

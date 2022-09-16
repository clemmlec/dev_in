<?php

namespace App\Controller\users;

use App\Entity\Meme;
use App\Entity\MemeFavoris;
use App\Entity\MemeSignaler;
use App\Entity\NoteMeme;
use App\Entity\User;
use App\Form\MemeType;
use App\Repository\MemeFavorisRepository;
use App\Repository\MemeRepository;
use App\Repository\MemeSignalerRepository;
use App\Repository\CategorieRepository;
use App\Repository\NoteMemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/meme')]
class MemeController extends AbstractController
{
    // #[Route('/', name: 'user_meme_index', methods: ['GET', 'POST'])]
    // public function index(Request $request, MemeRepository $memeRepository, Security $security ): Response
    // {

        //     $selectMeme = $memeRepository->findAll();

        //     $meme = new Meme();
        //     $form = $this->createForm(MemeType::class, $meme);
        //     $form->handleRequest($request);

        //     if ($form->isSubmitted() && $form->isValid()) {

        //         $meme->setUserId($security->getUser());
        //         $memeRepository->add($meme, true);

        //         return $this->redirectToRoute('user_meme_index', [], Response::HTTP_SEE_OTHER);
        //     }

        //     return $this->renderForm('meme/index.html.twig', [
        //         'memes' => $selectMeme,
        //         // 'note' => $note,

        //         'form' => $form,
        //     ]);

    // }

    #[Route('/note/{note}/{memeId}', name: 'app_meme_note', methods: ['GET'])]
    public function noteMeme(int $note, int $memeId, MemeRepository $memeRepository, NoteMemeRepository $noteRepo, Security $security)
    {
        $newNote = new NoteMeme();
        // $note = explode('-', $id)[0];
        // $memeId = explode('-', $id)[1];

        $meme = $memeRepository->find($memeId);

        $user = $security->getUser();

        // dd($follow);
        // dd($user, $follow);
        if ($note < 0 || $note > 5) {
            return new Response('La note rentré n\est pas valide', 201);
        }
        if ($meme && $user) {
            $dejaNoter = $noteRepo->findOneBy(['user' => $user, 'meme' => $memeId]);
            if (!$dejaNoter) {
                $newNote->setUser($user)
                ->setMeme($meme)
                ->setNote($note);

                $noteRepo->add($newNote, true);

                return new Response('note envoyer', 201);
            }

            return new Response('déjà noter preparer la modification', 201);
        }

        return new Response('note non valide', 404);
    }
    // #[Route('/new', name: 'user_meme_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, CategorieRepository $categorieRepo ,MemeRepository $memeRepository, Security $security): Response
    // {
        // dd($request);
        // $meme = new Meme();
        // $param=$request->request->all('meme');
        // $categorie=$categorieRepo->find($param['categorie_id']);
        // // $imageFile=$request->request->all('imageFile');
        // dd( $request );

        // $form = $this->createForm(MemeType::class, $meme);
        // $form->handleRequest($request);

            // $meme->setUserId($security->getUser())
                // ->setNom($param['nom'])
                // ->setDescription($param['description'])
                // ->setCategorieId($categorie)
                // ->setImageFile($imageName)
            //    ;
            // $memeRepository->add($meme, true);
        //     $memeRepository->add($meme, true);
            // $dejaAmis=$friendsRepo->findOneBy(array('user' => $user_id ,'friend' => $id));
            // if(!$dejaAmis){
                //  $newFollow->setUser($user)
                // ->setFriend($follow);
                // $memeRepository->add($meme, true);

            // return new JsonResponse($meme);
                // return new Response('articl créer', 201);
            // }else{

            // }

       // if ($form->isSubmitted() && $form->isValid()) {

        //     $meme->setUserId($security->getUser());
        //     $memeRepository->add($meme, true);

        //     return $this->redirectToRoute('user_meme_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->renderForm('meme/new.html.twig', [
        //     'meme' => $meme,
        //     'form' => $form,
        // ]);
    // }

    #[Route('/{id}', name: 'user_meme_show', methods: ['GET'])]
    public function show(Meme $meme): Response
    {
        return $this->render('meme/show.html.twig', [
            'meme' => $meme,
        ]);
    }

    #[Route('/edit/name/{id}/{name}', name: 'user_memeName_edit', methods: ['GET'])]
    public function edit(int $id, string $name, MemeRepository $memeRepository, Security $security): Response
    {
        $user = $security->getUser();
        $meme = $memeRepository->find($id);
        if ($meme->getUserId() !== $user) {
            return new Response('vous n\avez pas le droit de modifier cet meme', 404);
        }
        if ($meme) {
            $meme->setNom($name);
            $memeRepository->add($meme, true);

            return new Response('le nom d\'meme à bien été modifié', 201);
        }

        return new Response('l\meme n\'est pas disponible', 404);
    }

    #[Route('/edit/content/{id}/{content}', name: 'user_memeContent_edit', methods: ['GET'])]
    public function editContent(int $id, string $content, MemeRepository $memeRepository, Security $security): Response
    {
        $user = $security->getUser();
        $meme = $memeRepository->find($id);
        if ($meme->getUserId() !== $user) {
            return new Response('vous n\avez pas le droit de modifier cet meme', 404);
        }
        if ($meme) {
            $meme->setDescription($content);
            $memeRepository->add($meme, true);

            return new Response('la description de l\'meme à bien été modifié', 201);
        }

        return new Response('l\meme n\'est pas disponible', 404);
    }

    // #[Route('/{id}/edit', name: 'user_meme_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Meme $meme, MemeRepository $memeRepository): Response
    // {
        //     $form = $this->createForm(MemeType::class, $meme);
        //     $form->handleRequest($request);

        //     if ($form->isSubmitted() && $form->isValid()) {
        //         $memeRepository->add($meme, true);

        //         return $this->redirectToRoute('user_meme_index', [], Response::HTTP_SEE_OTHER);
        //     }

        //     return $this->renderForm('meme/edit.html.twig', [
        //         'meme' => $meme,
        //         'form' => $form,
        //     ]);
    // }

    #[Route('/{id}', name: 'user_meme_delete', methods: ['POST'])]
    public function delete(Request $request, Meme $meme, MemeRepository $memeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meme->getId(), $request->request->get('_token'))) {
            $memeRepository->remove($meme, true);
        }

        return $this->redirectToRoute('user_meme_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/follow/{id}', name: 'app_meme_follow', methods: ['GET'])]
    public function followMeme(?Meme $meme, MemeFavorisRepository $memeFavRepo, MemeRepository $memeRepo, Security $security)
    {
        $memeFav = new MemeFavoris();
        $follow = $memeRepo->find($meme);

        $user = $security->getUser();

        if ($follow && $user) {
            $dejaAmis = $memeFavRepo->findOneBy(['user' => $user, 'meme' => $meme]);
            if (!$dejaAmis) {
                $memeFav->setUser($user)
                ->setMeme($follow);
                $memeFavRepo->add($memeFav, true);

                return new Response('meme ajouté', 201);
            }
            $memeFavRepo->remove($dejaAmis, true);

            return new Response('meme retiré des favoris', 201);
        }

        return new Response('demande de favoris non valide', 404);
    }

    #[Route('/signaler/{id}', name: 'user.meme.signaler', methods: ['GET'])]
    public function signalerMeme(?Meme $meme, Security $security, MemeRepository $artRepo, MemeSignalerRepository $artSignalRepo)
    {
        $user = $security->getUser();
       
        if ($meme && $user) {
            $dejaSignaler = $artSignalRepo->findOneBy(['user' => $user, 'meme' => $meme]);
            if (!$dejaSignaler) {
                $newSignal = new MemeSignaler();
                $newSignal->setUser($user)
               ->setMeme($meme);
                $artSignalRepo->add($newSignal, true);

                return new Response('meme signaler', 201);
            }
            $artSignalRepo->remove($dejaSignaler, true);

            return new Response('meme dé signaler', 201);
        }

        return new Response('meme non trouvé', 404);
    }
}

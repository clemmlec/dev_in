<?php

namespace App\Controller\admin;

use App\Entity\Meme;
use App\Form\Meme1Type;
use App\Repository\MemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/meme')]
class MemeController extends AbstractController
{
    #[Route('/', name: 'app_meme_index', methods: ['GET'])]
    public function index(MemeRepository $memeRepository): Response
    {
        return $this->render('admin/meme/index.html.twig', [
            'memes' => $memeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_meme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MemeRepository $memeRepository): Response
    {
        $meme = new Meme();
        $form = $this->createForm(Meme1Type::class, $meme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memeRepository->add($meme, true);

            return $this->redirectToRoute('app_meme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/meme/new.html.twig', [
            'meme' => $meme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_meme_show', methods: ['GET'])]
    public function show(Meme $meme): Response
    {
        return $this->render('admin/meme/show.html.twig', [
            'meme' => $meme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_meme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Meme $meme, MemeRepository $memeRepository): Response
    {
        $form = $this->createForm(Meme1Type::class, $meme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memeRepository->add($meme, true);

            return $this->redirectToRoute('app_meme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/meme/edit.html.twig', [
            'meme' => $meme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_meme_delete', methods: ['POST'])]
    public function delete(Request $request, Meme $meme, MemeRepository $memeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meme->getId(), $request->request->get('_token'))) {
            $memeRepository->remove($meme, true);
        }

        return $this->redirectToRoute('app_meme_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/switch/{id}', name: 'admin.meme.switch', methods: ['GET'])]
    public function switchVisibilityMeme(int $id, MemeRepository $artRepo)
    {
        $meme = $artRepo->find($id);
        if ($meme) {
            $meme->isVisible() ? $meme->setVisible(false) : $meme->setVisible(true);
            $artRepo->add($meme, true);

            return new Response('visibility changed', 201);
        }

        return new Response('commentaire non trouvé', 404);
    }
}

<?php

namespace App\Controller;

use App\Entity\Meme;
use App\Entity\Comment;
use App\Form\MemeType;
use App\Form\CommentType;
use App\Repository\MemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, MemeRepository $memeRepository, Security $security): Response
    {
        $selectMeme = $memeRepository->findActiveMeme();
        // $commentaire = new Comment();
        // $formCom = $this->createForm(CommentType::class, $commentaire);

        $meme = new Meme();
        $form = $this->createForm(MemeType::class, $meme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meme->setUserId($security->getUser());
            $memeRepository->add($meme, true);

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('index.html.twig', [
            'memes' => $selectMeme,
            // 'note' => $note,

            'form' => $form,
            // 'formCom' => $formCom,
        ]);
    }
}

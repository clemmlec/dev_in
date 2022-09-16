<?php

namespace App\Controller\users;

use App\Entity\Comment;
use App\Entity\CommentLike;
use App\Entity\CommentSignaler;
use App\Repository\MemeRepository;
use App\Repository\CommentLikeRepository;
use App\Repository\CommentRepository;
use App\Repository\CommentSignalerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MemeRepository $memeRepo, CommentRepository $commentRepository, Security $security): Response
    {
        $param = $request->request->all();
        // dd($param ['meme']);
        $comment = new Comment();

        $meme = $memeRepo->find($param['meme']);

        $user = $security->getUser();
        // dd($follow);
        // dd($user, $follow);

        if ($meme && $user) {
            $comment->setUser($user)
            ->setMeme($meme)
            ->setComment($param['com']);
            $commentRepository->add($comment, true);

            // return new JsonResponse($comment);
            return new Response('commantaire envoyé', 201);
        }

        return new Response('commantaire non valide', 404);
    }

    #[Route('/jaime/{id}', name: 'user.comment.jaime', methods: ['GET'])]
    public function switchVisibilityMeme(?Comment $com, Security $security, CommentRepository $comRepo, CommentLikeRepository $comLikeRepo)
    {

        $user = $security->getUser();

        if ($com && $user) {
            $dejaLiker = $comLikeRepo->findOneBy(['user' => $user, 'comment' => $com]);
            if (!$dejaLiker) {
                $newLike = new CommentLike();
                $newLike->setUser($user)
               ->setComment($com);
                $comLikeRepo->add($newLike, true);

                return new Response('commantaire liké', 201);
            }
            $comLikeRepo->remove($dejaLiker, true);

            return new Response('commantaire déliker', 201);
        }

        return new Response('commentaire non trouvé', 404);
    }

    #[Route('/signaler/{id}', name: 'user.comment.signaler', methods: ['GET'])]
    public function signalerComment(?Comment $com, Security $security, CommentRepository $comRepo, CommentSignalerRepository $comSignalRepo)
    {

        $user = $security->getUser();

        if ($com && $user) {
            $dejaSignaler = $comSignalRepo->findOneBy(['user' => $user, 'comment' => $com]);
            if (!$dejaSignaler) {
                $newSignal = new CommentSignaler();
                $newSignal->setUser($user)
               ->setComment($com);
                $comSignalRepo->add($newSignal, true);

                return new Response('commantaire signaler', 201);
            }
            $comSignalRepo->remove($dejaSignaler, true);

            return new Response('signalement supprimé', 201);
        }

        return new Response('commentaire non trouvé', 404);
    }
}

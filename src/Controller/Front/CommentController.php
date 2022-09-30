<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Entity\CommentLike;
use App\Entity\CommentReport;
use App\Repository\CommentLikeRepository;
use App\Repository\CommentReportRepository;
use App\Repository\CommentRepository;
use App\Repository\SubjectRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/new', name: 'app_comment_new', methods: ['POST'])]
    public function new(Request $request, SubjectRepository $subjectRepo, CommentRepository $commentRepository, Security $security): Response
    {
        $param = $request->request->all();
        // dd($param ['subject']);
        $comment = new Comment();

        $subject = $subjectRepo->find($param['subject']);

        $user = $security->getUser();
        // dd($follow);

        try {
            $comment->setUser($user)
            ->setSubject($subject)
            ->setMessage($param['com']);
            $commentRepository->add($comment, true);

            // return new JsonResponse($comment);
        } catch (Exception $e) {
            return new Response('maximum 255 caracteres', 500);
        }
        $id = $commentRepository->findLastProducts();
        
       
        return new Response( strval($id[0]->getId())
        , 201);
    }

    #[Route('/jaime/{id}', name: 'user.comment.jaime', methods: ['GET'])]
    public function switchVisibilitySubject(?Comment $com, Security $security, CommentRepository $comRepo, CommentLikeRepository $comLikeRepo)
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

    #[Route('/signaler/{id}/{message}', name: 'user.comment.signaler', methods: ['GET'])]
    public function signalerComment(?Comment $com, string $message, Security $security, CommentRepository $comRepo, CommentReportRepository $comSignalRepo)
    {
        $user = $security->getUser();

        if ($com && $user) {
            $newSignal = new CommentReport();
            $newSignal->setUser($user)
            ->setComment($com)
            ->setMessage($message);
            $comSignalRepo->add($newSignal, true);

            return new Response('commantaire signaler', 201);
        }

        return new Response('commentaire non trouvé', 404);
    }
}

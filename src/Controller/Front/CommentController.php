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
    public function __construct(
        private CommentRepository $commentRepository
        ) {
    }
    #[Route('/new', name: 'app_comment_new', methods: ['POST'])]
    public function new(Request $request, SubjectRepository $subjectRepo, Security $security): Response
    {
        $param = $request->request->all();
        $comment = new Comment();

        $subject = $subjectRepo->find($param['subject']);

        $user = $security->getUser();
        if($user->getCredibility() < -10){
            // $this->addFlash('error', 'Il semble que vous avez une mauvaise crédibilité, ameliorez la pour pouvoir de nouveau poster.');
            return new Response('Il semble que vous avez une mauvaise crédibilité, ameliorez la pour pouvoir de nouveau poster.', 403);
        }
        try {
            $comment->setUser($user)
            ->setSubject($subject)
            ->setMessage($param['com']);
            $this->commentRepository->add($comment, true);

        } catch (Exception $e) {
            return new Response('maximum 255 caracteres', 500);
        }
        $id = $this->commentRepository->findLastProducts();
        
       
        return new Response( strval($id[0]->getId()), 201);
    }

    #[Route('/jaime/{id}', name: 'user.comment.jaime', methods: ['GET'])]
    public function switchVisibilitySubject(?Comment $com, Security $security, CommentLikeRepository $comLikeRepo)
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
    public function signalerComment(?Comment $com, string $message, Security $security, CommentReportRepository $comSignalRepo)
    {
        $user = $security->getUser();
        // if deja signaler -> modifier
        if ($com && $user) {
            $dejaReport = $comSignalRepo->findOneBy(['user' => $user, 'comment' => $com]);
            if(!$dejaReport){
                $newSignal = new CommentReport();
                $newSignal->setUser($user)
                    ->setComment($com)
                    ->setMessage($message);
                $comSignalRepo->add($newSignal, true);
                if($user->getCredibility() > 20 ){
                    $com->setActive(0);
                    $this->commentRepository->add($com, true);
                }
                return new Response('commantaire signaler', 201);
            }else{
                $dejaReport->setUser($user)
                ->setComment($com)
                ->setMessage($message);
                $comSignalRepo->add($dejaReport, true);
                return new Response('Signalement modifié', 201);
            }

        }

        return new Response('commentaire non trouvé', 404);
    }

    #[Route('/delete/{id}', name: 'user_comment_delete', methods: ['DELETE'])]
    public function delete( ?Comment $comment, Security $security, Request $request): Response
    {
        $user = $security->getUser();
        
        if ($comment->getUser() !== $user) {
            return new Response('vous n\avez pas les droits pour supprimer ce commantaire', 404);
        }

        try {
            $this->commentRepository->remove($comment, true);
        } catch (\Throwable $th) {
            return new Response('commantaire non supprimé', 403);
        }
   
        return new Response('commantaire supprimé', 201);
    }
}

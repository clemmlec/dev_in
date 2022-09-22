<?php

namespace App\Controller\users;

use App\Entity\Subject;
use App\Entity\SubjectFavoris;
use App\Entity\SubjectReport;
use App\Entity\NoteSubject;
use App\Entity\User;
use App\Form\SubjectType;
use App\Repository\SubjectFavorisRepository;
use App\Repository\SubjectRepository;
use App\Repository\SubjectReportRepository;
use App\Repository\ForumRepository;
use App\Repository\NoteSubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/subject')]
class SubjectController extends AbstractController
{
    // #[Route('/', name: 'user_subject_index', methods: ['GET', 'POST'])]
    // public function index(Request $request, SubjectRepository $subjectRepository, Security $security ): Response
    // {

        //     $selectSubject = $subjectRepository->findAll();

        //     $subject = new Subject();
        //     $form = $this->createForm(SubjectType::class, $subject);
        //     $form->handleRequest($request);

        //     if ($form->isSubmitted() && $form->isValid()) {

        //         $subject->setUserId($security->getUser());
        //         $subjectRepository->add($subject, true);

        //         return $this->redirectToRoute('user_subject_index', [], Response::HTTP_SEE_OTHER);
        //     }

        //     return $this->renderForm('subject/index.html.twig', [
        //         'subjects' => $selectSubject,
        //         // 'note' => $note,

        //         'form' => $form,
        //     ]);

    // }

    #[Route('/note/{note}/{subjectId}', name: 'app_subject_note', methods: ['GET'])]
    public function noteSubject(int $note, int $subjectId, SubjectRepository $subjectRepository, NoteSubjectRepository $noteRepo, Security $security)
    {
        $newNote = new NoteSubject();
        // $note = explode('-', $id)[0];
        // $subjectId = explode('-', $id)[1];

        $subject = $subjectRepository->find($subjectId);

        $user = $security->getUser();

        // dd($follow);
        // dd($user, $follow);
        if ($note < 0 || $note > 5) {
            return new Response('La note rentré n\est pas valide', 201);
        }
        if ($subject && $user) {
            $dejaNoter = $noteRepo->findOneBy(['user' => $user, 'subject' => $subjectId]);
            if (!$dejaNoter) {
                $newNote->setUser($user)
                ->setSubject($subject)
                ->setNote($note);

                $noteRepo->add($newNote, true);

                return new Response('note envoyer', 201);
            }

            return new Response('déjà noter preparer la modification', 201);
        }

        return new Response('note non valide', 404);
    }
    // #[Route('/new', name: 'user_subject_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, ForumRepository $forumRepo ,SubjectRepository $subjectRepository, Security $security): Response
    // {
        // dd($request);
        // $subject = new Subject();
        // $param=$request->request->all('subject');
        // $forum=$forumRepo->find($param['forum']);
        // // $imageFile=$request->request->all('imageFile');
        // dd( $request );

        // $form = $this->createForm(SubjectType::class, $subject);
        // $form->handleRequest($request);

            // $subject->setUserId($security->getUser())
                // ->setNom($param['nom'])
                // ->setDescription($param['description'])
                // ->setForumId($forum)
                // ->setImageFile($imageName)
            //    ;
            // $subjectRepository->add($subject, true);
        //     $subjectRepository->add($subject, true);
            // $dejaAmis=$friendsRepo->findOneBy(array('user' => $user_id ,'friend' => $id));
            // if(!$dejaAmis){
                //  $newFollow->setUser($user)
                // ->setFriend($follow);
                // $subjectRepository->add($subject, true);

            // return new JsonResponse($subject);
                // return new Response('articl créer', 201);
            // }else{

            // }

       // if ($form->isSubmitted() && $form->isValid()) {

        //     $subject->setUserId($security->getUser());
        //     $subjectRepository->add($subject, true);

        //     return $this->redirectToRoute('user_subject_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->renderForm('subject/new.html.twig', [
        //     'subject' => $subject,
        //     'form' => $form,
        // ]);
    // }

    #[Route('/{id}', name: 'user_subject_show', methods: ['GET'])]
    public function show(Subject $subject): Response
    {
        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
        ]);
    }

    #[Route('/edit/name/{id}/{name}', name: 'user_subjectName_edit', methods: ['GET'])]
    public function edit(int $id, string $name, SubjectRepository $subjectRepository, Security $security): Response
    {
        $user = $security->getUser();
        $subject = $subjectRepository->find($id);
        if ($subject->getUserId() !== $user) {
            return new Response('vous n\avez pas le droit de modifier cet subject', 404);
        }
        if ($subject) {
            $subject->setNom($name);
            $subjectRepository->add($subject, true);

            return new Response('le nom d\'subject à bien été modifié', 201);
        }

        return new Response('l\subject n\'est pas disponible', 404);
    }

    #[Route('/edit/content/{id}/{content}', name: 'user_subjectContent_edit', methods: ['GET'])]
    public function editContent(int $id, string $content, SubjectRepository $subjectRepository, Security $security): Response
    {
        $user = $security->getUser();
        $subject = $subjectRepository->find($id);
        if ($subject->getUserId() !== $user) {
            return new Response('vous n\avez pas le droit de modifier cet subject', 404);
        }
        if ($subject) {
            $subject->setDescription($content);
            $subjectRepository->add($subject, true);

            return new Response('la description de l\'subject à bien été modifié', 201);
        }

        return new Response('l\subject n\'est pas disponible', 404);
    }

    // #[Route('/{id}/edit', name: 'user_subject_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Subject $subject, SubjectRepository $subjectRepository): Response
    // {
        //     $form = $this->createForm(SubjectType::class, $subject);
        //     $form->handleRequest($request);

        //     if ($form->isSubmitted() && $form->isValid()) {
        //         $subjectRepository->add($subject, true);

        //         return $this->redirectToRoute('user_subject_index', [], Response::HTTP_SEE_OTHER);
        //     }

        //     return $this->renderForm('subject/edit.html.twig', [
        //         'subject' => $subject,
        //         'form' => $form,
        //     ]);
    // }

    #[Route('/{id}', name: 'user_subject_delete', methods: ['POST'])]
    public function delete(Request $request, Subject $subject, SubjectRepository $subjectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $subjectRepository->remove($subject, true);
        }

        return $this->redirectToRoute('user_subject_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/follow/{id}', name: 'app_subject_follow', methods: ['GET'])]
    public function followSubject(?Subject $subject, SubjectFavorisRepository $subjectFavRepo, SubjectRepository $subjectRepo, Security $security)
    {
        $subjectFav = new SubjectFavoris();
        $follow = $subjectRepo->find($subject);

        $user = $security->getUser();

        if ($follow && $user) {
            $dejaAmis = $subjectFavRepo->findOneBy(['user' => $user, 'subject' => $subject]);
            if (!$dejaAmis) {
                $subjectFav->setUser($user)
                ->setSubject($follow);
                $subjectFavRepo->add($subjectFav, true);

                return new Response('subject ajouté', 201);
            }
            $subjectFavRepo->remove($dejaAmis, true);

            return new Response('subject retiré des favoris', 201);
        }

        return new Response('demande de favoris non valide', 404);
    }

    #[Route('/signaler/{id}', name: 'user.subject.signaler', methods: ['GET'])]
    public function signalerSubject(?Subject $subject, Security $security, SubjectRepository $artRepo, SubjectReportRepository $artSignalRepo)
    {
        $user = $security->getUser();
       
        if ($subject && $user) {
            $dejaReport = $artSignalRepo->findOneBy(['user' => $user, 'subject' => $subject]);
            if (!$dejaReport) {
                $newSignal = new SubjectReport();
                $newSignal->setUser($user)
               ->setSubject($subject);
                $artSignalRepo->add($newSignal, true);

                return new Response('subject signaler', 201);
            }
            $artSignalRepo->remove($dejaReport, true);

            return new Response('subject dé signaler', 201);
        }

        return new Response('subject non trouvé', 404);
    }
}
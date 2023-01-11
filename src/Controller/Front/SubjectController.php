<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Entity\Subject;
use App\Form\SearchType;
use App\Form\SubjectType;
use App\Filter\SearchData;
use App\Form\Subject1Type;
use App\Entity\NoteSubject;
use App\Entity\SubjectReport;
use App\Entity\SubjectFavoris;
use App\Repository\SubjectRepository;
use App\Repository\NoteSubjectRepository;
use App\Repository\SubjectReportRepository;
use App\Repository\SubjectFavorisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SebastianBergmann\ObjectEnumerator\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/subject')]
class SubjectController extends AbstractController
{
    public function __construct(
        private SubjectRepository $subjectRepository
        ) {
    }

    #[Route('/', name: 'app_subject_index')]
    public function index(Request $request, Security $security): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $forms = $this->createForm(SearchType::class, $data);
        $forms->handleRequest($request);

        $selectSubject = $this->subjectRepository->findActiveSubject($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('Components/_subjectsNoters.html.twig', [
                    'subjects' => $selectSubject,
                ]),
                'pagination' => $this->renderView('Components/filter/_paginationSubject.html.twig', [
                    'subjects' => $selectSubject,
                ]),
                'count' => $this->renderView('Components/filter/_countSubject.html.twig', [
                    'subjects' => $selectSubject,
                ]),
                'pages' => ceil($selectSubject->getTotalItemCount() / $selectSubject->getItemNumberPerPage()),
            ]);
        }

        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subject->setUser($security->getUser());
            $this->subjectRepository->add($subject, true);

            $this->addFlash('success', 'Sujet créer avec success');

            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('subject/index.html.twig', [
            'subjects' => $selectSubject,
            'form' => $form,
            'forms' => $forms,
        ]);
    }

    #[Route('/note/{note}/{subjectId}', name: 'app_subject_note', methods: ['GET'])]
    public function noteSubject(
        int $note, 
        Subject $subjectId, 
        NoteSubjectRepository $noteRepo, 
        Security $security)
    {

        if( !$subjectId instanceOf Subject){
            return new Response('Ce sujet n\'existe pas', 404);
        }
    
        $user = $security->getUser();

        if (!$user) {
            return new Response('utilisateur non connecté', 403);
        }

        $dejaNoter = $noteRepo->findOneBy(['user' => $user, 'subject' => $subjectId->getId()]);
        $isUserSubject = $this->subjectRepository->findOneBy(['user' => $user,'id' => $subjectId->getId()]);

        if ($dejaNoter || $isUserSubject ) {
            return new Response('fraude suspecté ⛔' , 403);
        }

        $newNote = new NoteSubject();
        $newNote->setUser($user)
            ->setSubject($subjectId)
            ->setNote($note);

        $noteRepo->add($newNote, true);

        return new Response('note envoyer', 201);

    }

    #[Route('/edit/{id}', name: 'user_subject_edit', methods: ['GET','POST'])]
    public function edit(int $id, Security $security, Request $request): Response
    {
        $user = $security->getUser();
        $subject = $this->subjectRepository->find($id);
        
        if ($subject->getUser() !== $user) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à modifier ce sujet');
            return $this->redirectToRoute('user_subject_show', ['id' => $subject->getId(), 'slug' => $subject->getSlug()], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(Subject1Type::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->subjectRepository->add($subject, true);
            } catch (Exception $e) {
                return new Response('maximum 255 caracteres', 500);
            }
            
            $this->addFlash('succes', 'Sujet modifié avec succes');
            
            return $this->redirectToRoute('user_subject_show', ['id' => $subject->getId(), 'slug' => $subject->getSlug()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('subject/edit.html.twig', [
            'subject' => $subject,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'user_subject_delete', methods: ['POST'])]
    public function delete( ?Subject $subject, Request $request): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $this->subjectRepository->remove($subject, true);
        }else{
            $this->addFlash('error', 'Vous n\'etes pas autorisé à supprimer ce sujet');
            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }


        $this->addFlash('success', 'Sujet supprimé avec success');
        return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/follow/{id}', name: 'app_subject_follow', methods: ['GET'])]
    public function followSubject(?Subject $subject, SubjectFavorisRepository $subjectFavRepo, Security $security)
    {
        $subjectFav = new SubjectFavoris();
        $follow = $this->subjectRepository->find($subject);

        $user = $security->getUser();
        if(!$user) {
            $this->addFlash('error', 'Veuillez vous connecter pour ajouter un sujet en favoris');

            return new Response('authentification requise', 403);
        }
        if ($follow) {
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

    #[Route('/{id}/{slug}', name: 'user_subject_show', methods: ['GET', 'POST'])]
    public function show(?Subject $subject, string $slug, Security $security, Request $request): Response
    {
        $subjects = $this->subjectRepository->findArticleWithSameForum($subject->getForum());

        return $this->renderForm('subject/show.html.twig', [
            'subject' => $subject,
           'subjects' => $subjects,
        ]);
    }

    #[Route('/signaler/{id}/{message}', name: 'user.subject.signaler', methods: ['GET'])]
    public function signalerSubject(?Subject $subject, string $message, Security $security, SubjectReportRepository $subjectReportRepository)
    {
        $user = $security->getUser();
        if(!$user) {
            $this->addFlash('error', 'Veuillez vous connecter pour faire une suggestion');

            return new Response('authentification requise', 403);
        }

        if ($subject) {
            $dejaReport = $subjectReportRepository->findOneBy(['user' => $user, 'subject' => $subject]);
            if(!$dejaReport){
                $newSignal = new SubjectReport();
                $newSignal->setUser($user)
                    ->setSubject($subject)
                    ->setMessage($message);
                $subjectReportRepository->add($newSignal, true);
                if($user->getCredibility() > 20 ){
                    $subject->setActive(0);
                    $this->subjectRepository->add($subject, true);
                }
                return new Response('subject signaler', 201);
            }else{
                $dejaReport->setUser($user)
                ->setSubject($subject)
                ->setMessage($message);
                $subjectReportRepository->add($dejaReport, true);
                return new Response('signalement modifié', 201);
            }
            
        }

        return new Response('subject non trouvé', 404);
    }
}

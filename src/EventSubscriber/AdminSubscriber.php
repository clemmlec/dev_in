<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Subject;
use App\Entity\CommentReport;
use App\Entity\SubjectReport;
use App\Entity\ArticleSuggestion;
use App\Repository\UserRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class AdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
        private SubjectRepository $subjectRepository

    ) {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUser'],
            BeforeEntityDeletedEvent::class => ['setCredibility'],
        ];
    }

    public function setUser(BeforeEntityPersistedEvent $event)
    {
        $entityInstance = $event->getEntityInstance();

        if (!$entityInstance instanceof Subject && !$entityInstance instanceof Article) {
            return;
        }

        $entityInstance->setUser($this->security->getUser());
        // dd($entityInstance);
    }

    public function setCredibility(BeforeEntityDeletedEvent $event)
    {
        $entityInstance = $event->getEntityInstance();

        if (
            !$entityInstance instanceof Comment 
            && !$entityInstance instanceof CommentReport
            && !$entityInstance instanceof Subject
            && !$entityInstance instanceof SubjectReport
            && !$entityInstance instanceof ArticleSuggestion
            ) {
            return;
        }


        switch(true) {  
            case $entityInstance instanceof Comment:
                $entityInstance->getCommentReports();
                foreach ($entityInstance->getCommentReports() as $report) {
                    $user = $this->userRepository->find($report->getUser());
                    $user -> setCredibility( $user->getCredibility()+1);
                    $this->userRepository->add($user,true);
                }
                $user = $entityInstance->getUser();
                $user -> setCredibility( $user->getCredibility()-1);
                break;
                
            case $entityInstance instanceof CommentReport:
                $user = $entityInstance->getUser();
                $user -> setCredibility( $user->getCredibility()-1);
                $this->userRepository->add($user,true);
                break;

            case $entityInstance instanceof Subject:
                $entityInstance->getSubjectReports();
                foreach ($entityInstance->getSubjectReports() as $report) {
                    // $report->getUser();
                    $user = $this->userRepository->find($report->getUser());
                    $user -> setCredibility( $user->getCredibility()+1);
                    $this->userRepository->add($user,true);
                }
                break;

            case $entityInstance instanceof SubjectReport:
                $user = $entityInstance->getUser();
                $user -> setCredibility( $user->getCredibility()-1);
                $this->userRepository->add($user,true);
                break;

            case $entityInstance instanceof ArticleSuggestion:
                $user = $entityInstance->getUser();
                if ($entityInstance->isUtil()){
                    $user -> setCredibility( $user->getCredibility()+1);
                    $this->userRepository->add($user,true);
                }else{
                    $user -> setCredibility( $user->getCredibility()-1);
                    $this->userRepository->add($user,true);
                }
                break;
        }
        
        // dd($entityInstance);
    }
}

<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Subject;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class AdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository
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

        if (!$entityInstance instanceof Comment) {
            return;
        }

        $entityInstance->getCommentReports();
        // dd( $entityInstance->getCommentReports());
        foreach ($entityInstance->getCommentReports() as $report) {
            // $report->getUser();
            $user = $this->userRepository->find($report->getUser());
            $user -> setCredibility( $user->getCredibility()+1);
            $this->userRepository->add($user,true);
        }
        
        // dd($entityInstance);
    }
}

<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Subject;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class AdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    public static function getSubscribedEvents() : array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUser'],
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
}

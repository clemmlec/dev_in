<?php

namespace App\EventSubscriber;

use App\Entity\Meme;
use App\Entity\Categorie;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;



class AdminSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private Security $security
    ){

    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUserId'],
        ];
    }

    public function setUserId(BeforeEntityPersistedEvent $event)
    {
        $entityInstance = $event->getEntityInstance();
        
        if(!$entityInstance instanceof Meme && !$entityInstance instanceof Categorie) return;

        $entityInstance->setUserId($this->security->getUser());
    }

}
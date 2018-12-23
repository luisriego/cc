<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Mensagem;

class MessageIncoming
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Mensagem" entity
        if (!$entity instanceof Mensagem) {
            return;
        }

        $entityManager = $args->getEntityManager();
        // ... do something with the Message
    }
}
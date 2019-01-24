<?php

namespace App\EventListener;

use App\Entity\Interfaces\CreatedUpdatedInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntitiesCreatedUpdatedListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof CreatedUpdatedInterface) {
            $entity->setCreatedAt($this->getNowUTC());
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof CreatedUpdatedInterface) {
            $entity->setUpdatedAt($this->getNowUTC());
        }
    }

    private function getNowUTC(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            'now',
            new \DateTimeZone('UTC')
        );
    }
}

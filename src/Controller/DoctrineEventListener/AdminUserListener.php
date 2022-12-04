<?php

namespace App\DoctrineEventListener;

use App\Entity\AdminUser;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserListener
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
        # code...
    }

    public function preUpdated(LifecycleEventArgs $event): void {
        $this->setHashedPassword($event);
    }

    public function prePersist(LifecycleEventArgs $event): void {
        $this->setHashedPassword($event);
    }

    public function setHashedPassword(LifecycleEventArgs $event): void
    {
        $participant = $event->getObject();
        if (!$participant instanceof AdminUser) {
            return;
        }

        if (!empty($participant->getPlainPassword())) {
            $participant->setPassword(
               $this->passwordHasher->hashPassword($participant, $participant->getPlainPassword())
            );
        }
    }
}
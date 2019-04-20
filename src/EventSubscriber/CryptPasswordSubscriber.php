<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CryptPasswordSubscriber implements EventSubscriberInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function CryptPassword(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($entity instanceof User && in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])) {
            $entity->setPassword($this->passwordEncoder->encodePassword(
                $entity,
                $entity->getPassword()
            ));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.view' => ['CryptPassword',EventPriorities::PRE_WRITE]
        ];
    }
}

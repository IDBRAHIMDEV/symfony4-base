<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Post;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GetAuthPostSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function setAuthInPostWithAuth(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $token = $this->tokenStorage->getToken();

        if($token == null) {
            return;
        }

        if($entity instanceof Post && $method == Request::METHOD_POST) {
            $entity->setAuthor($token->getUser());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.view' => ['setAuthInPostWithAuth', EventPriorities::PRE_WRITE],
        ];
    }
}

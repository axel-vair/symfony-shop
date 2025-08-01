<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents() : array
    {
        return [
            InteractiveLoginEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(InteractiveLoginEvent $event) : void
    {
        $request = $event->getRequest();

        /** @var Session $session */
        $session = $request->getSession();

        if (!$session instanceof Session) {
            return;
        }

        $session->getFlashBag()->add('success', 'Vous êtes bien connecté.e');
    }
}

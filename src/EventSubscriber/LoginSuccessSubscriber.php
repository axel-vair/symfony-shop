<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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

        $request->getSession()->getFlashBag()->add('success', 'Vous êtes bien connecté.e');
    }
}
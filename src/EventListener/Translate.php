<?php


namespace App\EventListener;


use Symfony\Component\HttpKernel\Event\RequestEvent;

class Translate
{

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (array_key_exists("HTTP_ACCEPT_LANGUAGE", $_SERVER)) {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            // some logic to determine the $locale
            $request->setLocale($lang);
        }
    }
}

<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class AppRequestListener
 * @package AppBundle\EventListener
 */
class AppRequestListener
{
    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event) : void
    {
        if ($content = $event->getRequest()->getContent()) {
            $data = json_decode($content, true);
            if (is_array($data)) {
                $event->getRequest()->request->replace($data);
            }
        }
    }
}

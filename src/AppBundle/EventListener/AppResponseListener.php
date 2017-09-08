<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Doctrine\DBAL\Logging\SQLLogger;

/**
 * Class AppResponseListener
 * @package AppBundle\EventListener
 */
class AppResponseListener
{
    /** @var SQLLogger $logger */
    protected $logger;

    /**
     * AppResponseListener constructor.
     * @param SQLLogger $logger
     */
    public function __construct(SQLLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event) : void
    {

        $response = $event->getResponse();
        if (strpos($response->headers->get('Content-Type'), 'application/json') === 0) {
            if (filter_var($event->getRequest()->get('debug'), FILTER_VALIDATE_BOOLEAN) === true) {
                $jsonData = json_decode($response->getContent(), true);
                $jsonData['__debug'] = $this->logger->queries;
                $response->setContent(json_encode($jsonData));
            }
        }
    }
}

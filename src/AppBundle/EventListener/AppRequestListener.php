<?php

namespace AppBundle\EventListener;

use Doctrine\DBAL\Logging\SQLLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class AppRequestListener
 * @package AppBundle\EventListener
 */
class AppRequestListener
{
    /** @var ContainerInterface $container */
    protected $container;

    /** @var SQLLogger $logger */
    protected $logger;

    /**
     * AppRequestListener constructor.
     * @param ContainerInterface $container
     * @param SQLLogger $logger
     */
    public function __construct(
        ContainerInterface $container,
        SQLLogger $logger
    )
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event) : void
    {
        $this->container->get('doctrine')->getConnection()
            ->getConfiguration()
            ->setSQLLogger($this->logger);

        if ($content = $event->getRequest()->getContent()) {
            $data = json_decode($content, true);
            if (is_array($data)) {
                $event->getRequest()->request->replace($data);
            }
        }
    }
}

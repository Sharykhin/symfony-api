<?php

namespace AppBundle\EventListener;

use AppBundle\Contract\Repository\User\IUserRepository;
use AppBundle\Contract\Service\Token\IJWTManager;
use Doctrine\DBAL\Logging\SQLLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

    /** @var IJWTManager $tokenManager */
    protected $tokenManager;

    /** @var IUserRepository $userRepository */
    protected $userRepository;

    /**
     * AppRequestListener constructor.
     * @param ContainerInterface $container
     * @param SQLLogger $logger
     * @param IJWTManager $tokenManager
     * @param IUserRepository $userRepository
     */
    public function __construct(
        ContainerInterface $container,
        SQLLogger $logger,
        IJWTManager $tokenManager,
        IUserRepository $userRepository
    )
    {
        $this->container = $container;
        $this->logger = $logger;
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
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

        $authHeader = $event->getRequest()->headers->get('Authorization');
        if (strpos(mb_strtolower($authHeader), 'bearer') === 0) {
            $token = substr($authHeader, mb_strlen('Bearer '));
            $payload = $this->tokenManager->decode($token);
            if (!is_null($payload)) {
                $userId = $payload->sub->id;
                $user = $this->userRepository->findById($userId);
                $token = new UsernamePasswordToken($user, $user->getPassword(), "api", $user->getRoles());
                /** @var TokenStorage $tokenStorage */
                $tokenStorage = $this->container->get('security.token_storage');
                $tokenStorage->setToken($token);
            }
        }
    }
}

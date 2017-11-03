<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Service\Auth\IUserAuthenticate;
use AppBundle\Contract\Service\Token\IJWTManager;
use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Event\User\UserRegisteredEvent;
use AppBundle\FilterRequest\UserRequest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class AuthController
 * @package AppBundle\Controller
 */
class AuthController extends AbstractController
{
    /**
     * @param Request $request
     * @param IUserAuthenticate $userAuthenticate
     * @param IJWTManager $authManager
     * @return JsonResponse
     *
     * @Route("/api/login", name="login")
     */
    public function loginAction(
        Request $request,
        IUserAuthenticate $userAuthenticate,
        IJWTManager $authManager
    ) : JsonResponse
    {
        $user = $userAuthenticate->execute($request->request->all());

        $token = $authManager->encode(['id' => $user->getId(), 'roles' => $user->getRoles()]);

        return $this->success(compact('token', 'user'), JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }

    /**
     * @param Request $request
     * @param IUserCreate $userCreate
     * @return JsonResponse
     *
     * @Route("/api/register", name="post_login")
     * @Method("POST")
     */
    public function registerAction(
        Request $request,
        IUserCreate $userCreate,
        UserRequest $userRequest,
        EventDispatcherInterface $dispatcher
    ) : JsonResponse
    {

        $parameters = $userRequest->filterRequest($request->request->all(), UserRequest::REGISTER_ACTION);

        $user = $userCreate->execute($parameters);

        $event = new UserRegisteredEvent($user);
        $dispatcher->dispatch(UserRegisteredEvent::NAME, $event);

        return $this->success([
            'success' => true,
            'data' => $user
        ], JsonResponse::HTTP_CREATED, [], ['groups' => ['list']]);
    }
}

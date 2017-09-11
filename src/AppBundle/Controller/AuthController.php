<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Service\Auth\IUserAuthenticate;
use AppBundle\Contract\Service\Token\IJWTManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        $user = $userAuthenticate->authenticate($request->request->all());

        $token = $authManager->encode(['id' => $user->getId(), 'roles' => $user->getRoles()]);

        return $this->success(compact('token', 'user'), JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }
}

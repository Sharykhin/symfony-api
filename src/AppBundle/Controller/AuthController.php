<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Service\Auth\IUserAuthenticate;
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
     * @Route("/api/login", name="login")
     */
    public function loginAction(
        Request $request,
        IUserAuthenticate $userAuthenticate
    ) : JsonResponse
    {
        $user = $userAuthenticate->authenticate($request->request->all());

        return $this->success($user, JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }
}

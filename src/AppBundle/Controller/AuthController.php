<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/api/login", name="login")
     */
    public function loginAction(Request $request) : JsonResponse
    {
        return $this->success(['user' => $this->getUser()], JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }
}
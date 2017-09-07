<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends Controller
{

    /**
     * @param Request $request
     * @param IUserCreate $userCreate
     * @return JsonResponse
     *
     * @Route("/users", name="post_users")
     * @Method("POST")
     */
    public function create(
        Request $request,
        IUserCreate $userCreate
    ) : JsonResponse
    {
        $username = $request->request->get('username');
        $user = $userCreate->execute(['username' => $username]);
        return $this->json(['success' => true, 'data' => $user->getUserName()]);
    }
}
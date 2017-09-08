<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends AbstractController
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
        $parameters = $request->request->all();
        $user = $userCreate->execute($parameters);
        return $this->json(['success' => true, 'data' => $user]);
    }

    /**
     * @return JsonResponse
     * @Route("/users", name="get_users")
     * @Method("GET")
     */
    public function index() : JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        $repository->findBy(['username' => 'Mike']);
        return $this->success($users, JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }
}
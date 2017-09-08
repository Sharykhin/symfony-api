<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Repository\User\IUserRepository;
use AppBundle\Contract\Service\User\IUserCreate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @Route("/api/users", name="post_users")
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
     * @param Request $request
     * @param IUserRepository $userRepository
     * @return JsonResponse
     * @Route("/api/users", name="get_users")
     * @Method("GET")
     */
    public function index(
        Request $request,
        IUserRepository $userRepository
    ) : JsonResponse
    {
        $limit = (int) $request->get('limit', 5);
        $offset = (int) $request->get('offset', 0);
        $users = $userRepository->findAll([], $limit, $offset);
        $total = $userRepository->count([]);
        $count = sizeof($users);
        return $this->success($users, JsonResponse::HTTP_OK, [], ['groups' => ['list']], compact('total', 'count', 'limit', 'offset'));
    }
}
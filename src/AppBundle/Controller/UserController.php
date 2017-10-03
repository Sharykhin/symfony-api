<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Repository\User\IUserRepository;
use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Entity\User;
use AppBundle\Security\Voter\UserVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->success(['success' => true, 'data' => $user], JsonResponse::HTTP_CREATED, [], ['groups' => ['list']]);
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

    /**
     * @param IUserRepository $userRepository
     * @param string $userId
     * @return JsonResponse
     *
     * @Route("/api/users/{userId}", name="get_user")
     * @Method("GET")
     * Security("has_role('ROLE_USER')")
     */
    public function getOneUser(
        string $userId,
        IUserRepository $userRepository
    ) : JsonResponse
    {
        $this->denyAccessUnlessGranted(['IS_AUTHENTICATED_FULLY'], $this->getUser());

        $user = $userRepository->findById($userId);
        if (!$user instanceof User) {
            return $this->notFound('User was not found');
        }

        $this->denyAccessUnlessGranted(UserVoter::READ, $user);

        return $this->success($user, JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }
}
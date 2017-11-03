<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Repository\User\IUserRepository;
use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\Contract\Service\User\IUserDelete;
use AppBundle\Contract\Service\User\IUserUpdate;
use AppBundle\Entity\User;
use AppBundle\Event\User\UserRemovedEvent;
use AppBundle\FilterRequest\UserRequest;
use AppBundle\Security\Voter\UserVoter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @param Request $request
     * @param IUserCreate $userCreate
     * @param UserRequest $userRequest
     * @return JsonResponse
     *
     * @Route("/api/users", name="post_users")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function create(
        Request $request,
        IUserCreate $userCreate,
        UserRequest $userRequest
    ) : JsonResponse
    {

        $parameters = $userRequest->filterRequest($request->request->all(), UserRequest::CREATE_ACTION);

        $user = $userCreate->execute($parameters);
        return $this->success([
            'success' => true,
            'data' => $user
        ], JsonResponse::HTTP_CREATED, [], ['groups' => ['list']]);
    }

    /**
     * @param string $userId
     * @param IUserRepository $userRepository
     * @param IUserUpdate $userUpdate
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/api/users/{userId}", name="put_user")
     * @Method("PUT")
     * @Security("has_role('ROLE_USER')")
     */
    public function updateUser(
        string $userId,
        IUserRepository $userRepository,
        IUserUpdate $userUpdate,
        Request $request,
        UserRequest $userRequest
    ) : JsonResponse
    {
        $user = $userRepository->findById($userId);

        $this->denyAccessUnlessGranted(UserVoter::UPDATE, $user);

        if (!$user instanceof User) {
            return $this->notFound('User was not found');
        }

        // TODO: this about better way for filtering income data;
//        $fields = ['first_name'];
//        $adminFields = array_merge($fields, ['last_name']);
//
//        $fields = $this->isGranted(['ROLE_ADMIN'], $this->getUser()) ? $adminFields : $fields;
//        $parameters = request_intersect($request->request->all(), $fields);

        $parameters = $userRequest->filterRequest($request->request->all(), UserRequest::UPDATE_ACTION);

        $user = $userUpdate->execute($user, $parameters);

        return $this->success($user, JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }

    /**
     * @param IUserRepository $userRepository
     * @param string $userId
     * @return JsonResponse
     *
     * @Route("/api/users/{userId}", name="get_user")
     * @Method("GET")
     * Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function getOneUser(
        string $userId,
        IUserRepository $userRepository
    ) : JsonResponse
    {
        // TODO: use security
        $this->denyAccessUnlessGranted(['IS_AUTHENTICATED_FULLY'], $this->getUser());

        $user = $userRepository->findById($userId);
        if (!$user instanceof User) {
            return $this->notFound('User was not found');
        }

        $this->denyAccessUnlessGranted(UserVoter::READ, $user);

        return $this->success($user, JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }

    /**
     * @param string $userId
     * @param IUserRepository $userRepository
     * @param IUserDelete $userDelete
     * @param EventDispatcherInterface $dispatcher
     * @return JsonResponse
     *
     * @Route("/api/users/{userId}", name="delete_user")
     * @Method("DELETE")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function deleteUser(
        string $userId,
        IUserRepository $userRepository,
        IUserDelete $userDelete,
        EventDispatcherInterface $dispatcher
    ) : JsonResponse
    {
        $user = $userRepository->findById($userId);
        if (!$user instanceof User) {
            return $this->notFound('User was not found');
        }
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $userDelete->execute($user);

        $event = new UserRemovedEvent($user);
        $dispatcher->dispatch(UserRemovedEvent::NAME, $event);

        return $this->success($user, JsonResponse::HTTP_OK);
    }
}

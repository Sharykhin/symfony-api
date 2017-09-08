<?php

namespace AppBundle\Controller;

use AppBundle\Contract\Service\User\IUserCreate;
use AppBundle\CustomObjectNormalizer;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

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

        $encoders = array(new JsonEncoder());
        $normalizers = array(new CustomObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize(['success' => true, 'data' => $users], 'json');
        return new JsonResponse($json, 200, [], true);
//        return $this->json(['success' => true, 'data' => $users], JsonResponse::HTTP_OK, [], ['groups' => ['list']]);
    }
}
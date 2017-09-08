<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
    }
}
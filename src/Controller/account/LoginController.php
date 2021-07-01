<?php

namespace App\Controller\account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/account/login", name="login")
     */
    public function index(): Response
    {
        return $this->render('account/login.html.twig', [
            'title' => 'LOGIN',
            'subtitle' => 'MANAGEMENT USER',
        ]);
    }
}

<?php

namespace App\Controller\account;

use Psr\Log\LoggerInterface;
use App\Controller\DefaultController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/account/login", name="login")
     */
    public function loginAction(LoggerInterface $logger, Request $request): Response
    {
        $defaultcontroller = new DefaultController();
        $checksession = $defaultcontroller->checkSession();

        $resultlogout = null;

        $resultlogout = $request->get('resultlogout');

        if ($checksession == null || $checksession == "") {
            return $this->render('account/login.html.twig', [
                'title' => 'LOGIN',
                'subtitle' => 'MANAGEMENT USER',
                'resultlogout' => $resultlogout,
            ]);
        } else {
            return $this->redirectToRoute('user');
        }
    }
}

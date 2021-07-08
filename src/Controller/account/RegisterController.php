<?php

namespace App\Controller\account;

use Psr\Log\LoggerInterface;
use App\Controller\DefaultController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/account/register", name="register")
     */
    public function registerAction(LoggerInterface $logger, Request $request): Response
    {
        $defaultcontroller = new DefaultController();
        $checksession = $defaultcontroller->checkSession();

        if ($checksession == null || $checksession == "") {
            return $this->render('account/register.html.twig', [
                'title' => 'REGISTER',
                'subtitle' => 'MANAGEMENT USER',
            ]);
        } else {
            return $this->redirectToRoute('user');
        }
    }
}

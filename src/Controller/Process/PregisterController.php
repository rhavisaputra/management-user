<?php

namespace App\Controller\Process;

use Psr\Log\LoggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PregisterController extends AbstractController
{
    /**
     * @Route("/process/pregister", methods={"POST"}, name="pregister")
     */
    public function puserUpdateAction(LoggerInterface $logger, Request $request): Response
    {
        $dbconnection = $this->getDoctrine()->getConnection();

        $name = null;
        $email = null;
        $password = null;

        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');
        $md5password = md5($password);

        // REGISTER USER
        $querycheckregister = "
        	INSERT INTO users (id, name, email, password) 
            VALUES (DEFAULT, :name, :email, :password)
        ";

        $stmtcheckregister = $dbconnection->prepare($querycheckregister);
        $stmtcheckregister->bindParam(':name', $name);
        $stmtcheckregister->bindParam(':email', $email);
        $stmtcheckregister->bindParam(':password', $md5password);
        $stmtcheckregister->execute();

        $checkregister = $stmtcheckregister->fetchAll();

        if ($checkregister) {
            return $this->redirectToRoute('login');
        }
    }
}

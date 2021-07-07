<?php

namespace App\Controller\Process;

use Psr\Log\LoggerInterface;
use App\Controller\DefaultController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PuserUpdateController extends AbstractController
{
    /**
     * @Route("/process/puser_update/{id}", methods={"POST"}, name="puser_update")
     */
    public function puserUpdateAction(LoggerInterface $logger, Request $request, $id): Response
    {
    	$defaultcontroller = new DefaultController();
        $startsession = $defaultcontroller->startSession();
        $checksession = $defaultcontroller->checkSession();

        if ($checksession == null || $checksession == "") {
            return $this->redirectToRoute('plogout');
        } else {
            $dbconnection = $this->getDoctrine()->getConnection();

            $name = null;
            $email = null;

            $name = $request->get('name');
            $email = $request->get('email');

            // UPDATE USER
            $querycheckupdate = "
                UPDATE users SET name = :name, email = :email WHERE id = :id
            ";

            $stmtcheckupdate = $dbconnection->prepare($querycheckupdate);
            $stmtcheckupdate->bindParam(':name', $name);
            $stmtcheckupdate->bindParam(':email', $email);
            $stmtcheckupdate->bindParam(':id', $id);
            $stmtcheckupdate->execute();

            $checkupdate = $stmtcheckupdate->fetchAll();

            if ($checkupdate) {
                $startsession->set('name', $name);
                return $this->redirectToRoute('user');
            }
        }
    }
}

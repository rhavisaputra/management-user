<?php

namespace App\Controller\Process;

use Psr\Log\LoggerInterface;
use App\Controller\DefaultController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PloginController extends AbstractController
{
    /**
     * @Route("/process/plogin", name="plogin")
     */
    public function ploginAction(LoggerInterface $logger, Request $request): Response
    {
    	$defaultcontroller = new DefaultController();
    	$startsession = $defaultcontroller->startSession();
    	$checksession = $defaultcontroller->checkSession();
        $dbconnection = $this->getDoctrine()->getConnection();

        $email = null;
        $password = null;
        $resultlogin = null;

        $email = $request->get('email');
		$password = $request->get('password');
		$md5password = md5($password);

		$querychecklogin = "
			SELECT * FROM users WHERE (email = :email)
		";
		$stmtchecklogin = $dbconnection->prepare($querychecklogin);
		$stmtchecklogin->bindParam(':email', $email);
		$stmtchecklogin->execute();

		$checklogin = $stmtchecklogin->fetchAll();
		$countchecklogin = count($checklogin);

		/*
		echo "<pre>";
        print_r($checklogin);
        echo "</pre>";
        exit;
        */
	
		// RESULT LOGIN
        // 0 = success
        // 1 = failed -> More than one user found
        // 2 = failed -> Email Not Found
        // 3 = failed -> Invalid Password

		if ($countchecklogin == 1) {
			$resultemail = $checklogin[0]['email'];
			$resultpassword = $checklogin[0]['password'];

			if ($resultpassword == $password) {
				$resultlogin = 0;
			} else {
				$resultlogin = 3;
			}

		} elseif ($countchecklogin == 0) {
			$resultlogin = 2;
		} else {
			$resultlogin = 1;
		}

		if ($resultlogin == 0) {
			if ($checksession == null || $checksession == "") {
				$startsession->set('isLoggedin', 1);
				$startsession->set('email', $resultemail);
				/*
				echo "<pre>";
				echo $startsession->get('isLoggedin');
				echo "<br>";
				echo $startsession->get('email');
				echo "</pre>";
				exit;
				*/
				return $this->redirectToRoute(
					'user', ['resultlogin' => $resultlogin]
				);
			} else {
				return $this->redirectToRoute(
					'user', ['resultlogin' => $resultlogin]
				);
			}
		} else {
			return $this->redirectToRoute(
				'login', ['resultlogin' => $resultlogin]
			);
		}
    }
}

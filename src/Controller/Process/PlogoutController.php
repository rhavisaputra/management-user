<?php

namespace App\Controller\Process;

use Psr\Log\LoggerInterface;
use App\Controller\DefaultController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlogoutController extends AbstractController
{
    /**
     * @Route("/process/plogout", name="plogout")
     */
    public function plogoutAction(LoggerInterface $logger, Request $request): Response
    {
        $defaultcontroller = new DefaultController();
        $startsession = $defaultcontroller->startSession();
        $checksession = $defaultcontroller->checkSession();

        // 0 = success
	    $resultlogout = null;

	    if ($checksession != null || $checksession != "") {
	    	$startsession->invalidate();
	    	$resultlogout = 0;
	    	$logger->info('session-invalidate');
	    } else {
	    	$logger->info('session-already-empty');
	    }

	    return $this->redirectToRoute('login', ['resultlogout' => $resultlogout]);
    }
}

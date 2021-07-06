<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends AbstractController
{
	public function startSession()
	{
		$session = new Session();

		return $session;
	}

	public function getSession()
	{
		$session = $this->startSession();

		$sessionemail = null;
		$sessionisloggedin = null;

		if (
			($session->get('email') != null || $session->get('email') != "")
			&&
			($session->get('isLoggedin') != null || $session->get('isLoggedin') != "")
		) {
			$sessionemail = $session->get('email');
			$sessionisloggedin = $session->get('isLoggedin');

			$sessiondata = $sessionemail.'#'.$sessionisloggedin;

			return $sessiondata;
		}
	}

	public function checkSession()
	{
		$sessiondata = $this->getSession();
		$checksession = str_replace('#', '', $sessiondata);

		return $checksession;
	}

    public function index()
    {
    	$checksession = $this->checkSession();

    	if ($checksession == null || $checksession == '') {
    		return $this->redirectToRoute('plogout');
    	} else {
    		return $this->redirectToRoute('user');
    	}
    }
}

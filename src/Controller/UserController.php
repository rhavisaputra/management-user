<?php

namespace App\Controller;

use App\Entity\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        $defaultcontroller = new DefaultController();
        $startsession = $defaultcontroller->startSession();
        $checksession = $defaultcontroller->checkSession();

        if ($checksession == null || $checksession == "") {
            return $this->redirectToRoute('plogout');
        } else {
            # USING QUERY BUILDER
            // $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
            
            # USING RAW QUERY
            $dbconnection = $this->getDoctrine()->getConnection();
            
            $querycheckselect = "
                SELECT * FROM users
            ";
            $stmtcheckselect = $dbconnection->prepare($querycheckselect);
            $stmtcheckselect->execute();

            $users = $stmtcheckselect->fetchAll();

            return $this->render('user/index.html.twig', [
                'title' => 'LIST',
                'subtitle' => 'MANAGEMENT USER',
                'users' => $users,
                'userlogin' => $startsession->get('name'),
            ]);
        }
    }
    
    /**
     * @Route("/user/{id}", name="user_detail")
     */
    public function detail($id): Response
    {
        $defaultcontroller = new DefaultController();
        $startsession = $defaultcontroller->startSession();
        $checksession = $defaultcontroller->checkSession();

        if ($checksession == null || $checksession == "") {
            return $this->redirectToRoute('plogout');
        } else {
            $user = $this->getDoctrine()->getRepository(Users::class)->find($id);

            return $this->render('user/detail.html.twig', [
                'title' => 'DETAIL LIST',
                'subtitle' => 'MANAGEMENT USER',
                'user' => $user,
                'userlogin' => $startsession->get('name'),
            ]);
        }
    }

    /**
    * @Route("/user/edit/{id}", methods={"GET"}, name="user_update")
    */
    public function update(Request $request, $id): Response
    {
        $defaultcontroller = new DefaultController();
        $startsession = $defaultcontroller->startSession();
        $checksession = $defaultcontroller->checkSession();

        if ($checksession == null || $checksession == "") {
            return $this->redirectToRoute('plogout');
        } else {
            $dbconnection = $this->getDoctrine()->getConnection();
            
            // GET USER
            $querycheckselect = "
                SELECT * FROM users WHERE id = :id
            ";
            $stmtcheckselect = $dbconnection->prepare($querycheckselect);
            $stmtcheckselect->bindParam(':id', $id);
            $stmtcheckselect->execute();

            $checkselect = $stmtcheckselect->fetchAll();
            
            return $this->render('user/edit.html.twig', [
                'title' => 'UPDATE USER',
                'subtitle' => 'MANAGEMENT USER',
                'form' => $checkselect,
                'userlogin' => $startsession->get('name'),
            ]);
        }
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function delete(Request $request, $id)
    {
        $defaultcontroller = new DefaultController();
        $checksession = $defaultcontroller->checkSession();

        if ($checksession == null || $checksession == "") {
            return $this->redirectToRoute('plogout');
        } else {
            $user = $this->getDoctrine()->getRepository(Users::class)->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->send();
        }
    }

    // /**
    // * @Route("/user/save")
    // */
    // public function save()
    // {
    //     $entityManager = $this->getDoctrine()->getManager();

    //     $users = new Users();
    //     $users->setName('User3');
    //     $users->setEmail('user3@email.com');
    //     $users->setPassword('123');

    //     $entityManager->persist($users);
    //     $entityManager->flush();

    //     return new Response('Success create new users'.$users->getId());
    // }
}

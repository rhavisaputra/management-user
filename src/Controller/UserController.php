<?php

namespace App\Controller;

use App\Entity\User;

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
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('user/index.html.twig', [
            'title' => 'LIST',
            'subtitle' => 'MANAGEMENT USER',
            'users' => $users,
        ]);
    }
    
    /**
     * @Route("/user/{id}", name="user_detail")
     */
    public function detail($id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('user/detail.html.twig', [
            'title' => 'DETAIL LIST',
            'subtitle' => 'MANAGEMENT USER',
            'user' => $user,
        ]);
    }

    /**
    * @Route("/user/edit/{id}", methods={"GET","POST"}, name="user_update")
    */
    public function update(Request $request, $id)
    {
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Update',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('user');
            }

        return $this->render('user/edit.html.twig', [
            'title' => 'UPDATE USER',
            'subtitle' => 'MANAGEMENT USER',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function delete(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->send();
    }

    // /**
    // * @Route("/user/save")
    // */
    // public function save()
    // {
    //     $entityManager = $this->getDoctrine()->getManager();

    //     $users = new User();
    //     $users->setName('User3');
    //     $users->setEmail('user3@email.com');
    //     $users->setPassword('123');

    //     $entityManager->persist($users);
    //     $entityManager->flush();

    //     return new Response('Success create new users'.$users->getId());
    // }
}

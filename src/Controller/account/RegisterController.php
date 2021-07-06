<?php

namespace App\Controller\account;

use App\Entity\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterController extends AbstractController
{
    /**
     * @Route("/account/register", methods={"GET","POST"}, name="register")
     */
    public function create(Request $request)
    {
        $users = new Users();

        $form = $this->createFormBuilder($users)
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control mb-2']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Register',
                'attr' => ['class' => 'btn btn-warning mt-3']
            ])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $users = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($users);
                $entityManager->flush();

                return $this->redirectToRoute('login');
            }

        return $this->render('account/register.html.twig', [
            'title' => 'REGISTER',
            'subtitle' => 'MANAGEMENT USER',
            'form' => $form->createView(),
        ]);
    }
}

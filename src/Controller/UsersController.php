<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Entity\Group;

class UsersController extends AbstractController {

    public function index() {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('Users/index.html.twig', [
            'users' => $users,
        ]);
    }

    public function add(Request $request) {

        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Create User'
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => [
                    'onClick' => 'closePopup(event);'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $group = $this->getDoctrine()
                ->getRepository(Group::class)
                ->find(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $group->addUser($user);
            $entityManager->persist($group);

            $entityManager->flush();
        
            return $this->json(['success' => True]);
        }

        return $this->render('Users/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function update(Request $request, $id) {

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Save User'
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => [
                    'onClick' => 'closePopup(event);'
                ]
            ])
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        
            return $this->json(['success' => True]);
        }

        return $this->render('Users/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $user->getId()
        ]);
    }
}
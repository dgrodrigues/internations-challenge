<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Group;
use App\Entity\User;

class GroupsController extends AbstractController {

    public function index() {

        $groups = $this->getDoctrine()
            ->getRepository(Group::class)
            ->findAll();

        return $this->render('Groups/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    public function add(Request $request) {

        $group = new Group();
        $form = $this->createFormBuilder($group)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Create Group'
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
            $group = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();

            return $this->json(['success' => True]);
        }

        return $this->render('Groups/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function update(Request $request, $id) {

        $entityManager = $this->getDoctrine()->getManager();
        $group = $entityManager->getRepository(Group::class)->find($id);

        $form = $this->createFormBuilder($group)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Save Group'
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
            $group = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();
        
            return $this->json(['success' => True]);
        }

        return $this->render('Groups/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $group->getId()
        ]);
    }

    public function users(Request $request, $id) {

        $entityManager = $this->getDoctrine()->getManager();
        $group_users = $entityManager->getRepository(Group::class)
            ->find($id)
            ->getUsers();
        $users_query = $entityManager->getRepository(User::class)->findAll();

        $users = [];
        for ($i = 0; $i < count($users_query); $i++) {
            if (!$group_users->contains($users_query[$i])) {
                $users[] = $users_query[$i];
            }
        }
    
        return $this->render('Groups/users.html.twig', [
            'group_users' => $group_users,
            'users' => $users,
            'group' => $id
        ]);
    }
}

?>
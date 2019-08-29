<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Group;
use App\Entity\User;

class APIController extends AbstractController {

    public function deleteGroup($id) {
        
        // Do not delete Group All
        if ($id == 1) {
            return $this->json(['success' => False]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $group = $entityManager->getRepository(Group::class)->find($id);

        if (!$group) {
            return $this->json(['success' => False]);
        }

        $entityManager->remove($group);
        $entityManager->flush();

        return $this->json(['success' => True]);
    }

    public function listGroups() {
        $entityManager = $this->getDoctrine()->getManager();
        $groups = $entityManager->getRepository(Group::class)->findAll();

        if (!$groups) {
            return $this->json(['groups' => []]);
        }

        return $this->json(['groups' => $groups]);
    }

    public function deleteUser($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['success' => False]);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => True]);
    }

    public function listUsers() {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();

        if (!$users) {
            return $this->json(['users' => []]);
        }

        return $this->json(['users' => $users]);
    }

    public function updateUsers(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $group = $entityManager->getRepository(Group::class)->find($id);

        if (!$group) {
            return $this->json(['success' => False]);
        }

        // Remove all current users in group
        $current_users = $group->getUsers();
        foreach ($current_users as $user) {
            $group->removeUser($user);
        }

        // Add all users choosen to group
        $users_ids = $request->request->get('users');
        $users = $entityManager->getRepository(User::class)
        ->findById(explode(',', $users_ids));
        for ($e=0; $e < count($users); $e++) { 
            $group->addUser($users[$e]);
        }

        $entityManager->flush();

        return $this->json(['success' => True]);
    }
    
}

?>
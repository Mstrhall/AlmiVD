<?php
// src/Controller/UserAuth.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class UserAuth extends AbstractController
{
    #[Route('/api/users/auth', name: 'user_auth', methods: ['POST'])]
    public function authenticate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['error' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy([
            'email' => $data['email'],
            'password' => $data['password'] // Vérification du mot de passe en clair
        ]);

        if (!$user) {
            return $this->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'message' => 'Authentication successful'
        ]);
    }

    #[Route('/api/users/{id}', name: 'update_user', methods: ['PUT', 'PATCH'])]
    public function updateUser(int $id, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $user->setPassword($data['password']); // Mot de passe en clair
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'User updated successfully']);
    }
}

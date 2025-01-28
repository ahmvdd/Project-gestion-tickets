<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;



#[Route('/api', name: 'user_')]
final class UserController extends AbstractController{

    #le getall des user
    #[Route('/users', name: 'list', methods: ['GET'])]
    public function listUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users, context: [
            AbstractNormalizer::GROUPS => ['user:read']
        ]);

    }

    #le post d'un user
    #[Route('/post/user', name: 'create', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des données requises
        if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['password'])) {
            return $this->json(['error' => 'Missing required fields.'], 400); // Bad Request
        }

        // Création de l'utilisateur
        $user = new User();
        $user->setFirstname($data['firstname'])
            ->setLastname($data['lastname'])
            ->setEmail($data['email'])
            ->setPassword(password_hash($data['password'], PASSWORD_BCRYPT))
            ->setRole($data['role'] ?? 'etudiant') // Rôle par défaut si non fourni
            ->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201); // 201: Created
    }


    #le update d'un user
    #[Route('/user/{id}', name: 'update', methods: ['PATCH'])]
    public function updateUser(
        int $id,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found.'], 404); // Not Found
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['firstname'])) {
            $user->setFirstname($data['firstname']);
        }

        if (isset($data['lastname'])) {
            $user->setLastname($data['lastname']);
        }

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        }

        if (isset($data['role'])) {
            $user->setRole($data['role']);
        }

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, 400); // Bad Request
        }

        $entityManager->flush();

        return $this->json($user, 200, [], [
            AbstractNormalizer::GROUPS => ['user:read']
        ]);
    }

    #le delete d'un user
    #[Route('/user/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteUser(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // Recherche de l'utilisateur
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found.'], 404); // Not Found
        }

        // Suppression de l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['message' => 'User deleted successfully.'], 200);
    }





}

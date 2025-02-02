<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;



final class CategoryController extends AbstractController{
    #[Route('/api/categories', name: 'category_list', methods: ['GET'])]
    public function listCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        return $this->json($categories, 200, [], [
            AbstractNormalizer::GROUPS => ['category:read']
        ]);
    }


    #[Route('/api/categories', name: 'category_create', methods: ['POST'])]
    public function createCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Validation des données
        if (empty($data['nameCategory']) || empty($data['userId'])) {
            return $this->json(['error' => 'Missing required fields.'], 400); // Bad Request
        }

        $user = $userRepository->find($data['userId']);
        if (!$user) {
            return $this->json(['error' => 'User not found.'], 404); // Not Found
        }

        // Création de la catégorie
        $category = new Category();
        $category->setNameCategory($data['nameCategory']);
        $category->setAssignedTo($user);

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, 201, [], [
            AbstractNormalizer::GROUPS => ['category:read']
        ]);
    }



    #[Route('/api/categories/{id}', name: 'category_update', methods: ['PATCH'])]
    public function updateCategory(
        int $id,
        Request $request,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): JsonResponse {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->json(['error' => 'Category not found.'], 404); // Not Found
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nameCategory'])) {
            $category->setNameCategory($data['nameCategory']);
        }

        if (isset($data['userId'])) {
            $user = $userRepository->find($data['userId']);
            if (!$user) {
                return $this->json(['error' => 'User not found.'], 404); // Not Found
            }
            $category->setAssignedTo($user);
        }

        $entityManager->flush();

        return $this->json($category, 200, [], [
            AbstractNormalizer::GROUPS => ['category:read']
        ]);
    }



    #[Route('/api/categories/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function deleteCategory(
        int $id,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->json(['error' => 'Category not found.'], 404); // Not Found
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json(['message' => 'Category deleted successfully.'], 200);
    }





}

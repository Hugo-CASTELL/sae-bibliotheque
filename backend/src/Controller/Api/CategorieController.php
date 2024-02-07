<?php

namespace App\Controller\Api;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/api/categories', name: 'app_api_categorie')]
    public function index(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        return $this->json($categories, 200, [], ['groups' => 'categorie:read']);
    }

    #[Route('/api/categories/{id}', name: 'app_api_categorie_show')]
    public function show(int $id, CategorieRepository $categorieRepository): JsonResponse
    {
        $categorie = $categorieRepository->find($id);
        if (!$categorie) {
            return $this->json(['error' => 'Categorie not found'], 404);
        }
        return $this->json($categorie, 200, [], ['groups' => 'categorie:read']);
    }
}

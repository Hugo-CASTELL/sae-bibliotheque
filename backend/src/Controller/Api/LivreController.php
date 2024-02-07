<?php

namespace App\Controller\Api;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class LivreController extends AbstractController
{
    #[Route('/api/livres', name: 'app_api_livre')]
    public function index(LivreRepository $livreRepository): JsonResponse
    {
        $livres = $livreRepository->findAll();
        return $this->json($livres, 200, [], ['groups' => 'livre:read']);
    }

    #[Route('/api/livres/{id}', name: 'app_api_livre_show')]
    public function show(int $id, LivreRepository $livreRepository): JsonResponse
    {
        $livre = $livreRepository->find($id);
        if (!$livre) {
            return $this->json(['error' => 'Livre not found'], 404);
        }
        return $this->json($livre, 200, [], ['groups' => 'livre:read']);
    }
}

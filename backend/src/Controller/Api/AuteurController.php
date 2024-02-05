<?php

namespace App\Controller\Api;

use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuteurController extends AbstractController
{
    #[Route('/api/auteurs', name: 'app_api_auteur')]
    public function index(AuteurRepository $auteurRepository): JsonResponse
    {
        $auteurs = $auteurRepository->findAll();
        return $this->json($auteurs, 200, [], ['groups' => 'auteur:read']);
    }

    #[Route('/api/auteurs/{id}', name: 'app_api_auteur_show')]
    public function show(int $id, AuteurRepository $auteurRepository): JsonResponse
    {
        $auteur = $auteurRepository->find($id);
        if (!$auteur) {
            return $this->json(['error' => 'Auteur not found'], 404);
        }
        return $this->json($auteur, 200, [], ['groups' => 'auteur:read']);
    }
}

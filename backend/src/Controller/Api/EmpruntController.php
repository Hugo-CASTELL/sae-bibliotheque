<?php

namespace App\Controller\Api;

use App\Repository\EmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class EmpruntController extends AbstractController
{
    #[Route('/api/emprunts/{id}', name: 'app_api_emprunt_id')]
    public function index(int $id, EmpruntRepository $empruntRepository): JsonResponse
    {
        $emprunt = $empruntRepository->findOneBy(['id' => $id]);

        return $this->json($emprunt, 200, [], ['groups' => 'emprunt:read']);
    }
}

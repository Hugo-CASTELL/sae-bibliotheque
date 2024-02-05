<?php

namespace App\Controller\Api;

use App\Repository\AdherentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    #[Route('/api/user/me', name: 'app_api_user')]
    public function index(AdherentRepository $adherentRepository): JsonResponse
    {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $adherent = $adherentRepository->findOneBy(['email' => $user->getEmail()]);

        if (!$adherent) {
            return $this->json(['message' => 'Adherent not found'], 404);
        }

        return $this->json($adherent, 200, [], ['groups' => 'adherent:read']);
    }
}

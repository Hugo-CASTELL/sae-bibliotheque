<?php

namespace App\Controller\Api;

use App\Repository\AdherentRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[AsController]
class UserController extends AbstractController
{
    #[Route('/api/user/me', name: 'app_api_user')]
    public function index(AdherentRepository $adherentRepository, UtilisateurRepository $utilisateurRepository): JsonResponse
    {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $utilisateur = $utilisateurRepository->findOneBy(['email' => $user->getEmail()]);
        // $adherent = $adherentRepository->findOneBy(['email' => $user->getEmail()]);

        if (!$utilisateur) {
            return $this->json(['message' => 'Adherent not found'], 404);
        }

        return $this->json($utilisateur, 200, [], ['groups' => 'adherent:read']);
    }

    #[Route('/api/user/me/update', name: 'app_api_user_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, AdherentRepository $adherentRepository, UtilisateurRepository $utilisateurRepository): JsonResponse {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $adherent = $adherentRepository->findOneBy(['email' => $user->getEmail()]);
        $utilisateur = $utilisateurRepository->findOneBy(['email' => $user->getEmail()]);

        if (!$adherent) {
            return $this->json(['message' => 'Adherent not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['adressePostale'])) {
            $adherent->setAdressePostale($data['adressePostale']);
        }
        if (isset($data['numTel'])) {
            $adherent->setNumTel($data['numTel']);
        }
        if (isset($data['photo'])) {
            $adherent->setPhoto($data['photo']);
        }
        if (isset($data['email'])) {
            $adherent->setEmail($data['email']);
            $utilisateur->setEmail($data['email']);
        }

        $entityManager->persist($adherent);
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return $this->json($adherent, 200, [], ['groups' => 'adherent:read']);
    }
}

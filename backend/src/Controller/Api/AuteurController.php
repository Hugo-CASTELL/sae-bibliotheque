<?php

namespace App\Controller\Api;

use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
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

    #[Route('/api/auteurs/livres/search', name: 'app_api_auteur_livres_search')]
    public function searchLivres(Request $request,AuteurRepository $auteurRepository): JsonResponse
    {
        $param = [];

        $nom = $request->query->get('nom');

        if ($nom) {
            $param['nom'] = $nom;
        }
        $prenom = $request->query->get('prenom');
        if ($prenom) {
            $param['prenom'] = $prenom;
        }
        $nationalite = $request->query->get('nationalite');
        if ($nationalite) {
            $param['nationalite'] = $nationalite;
        }

        $auteurs = $auteurRepository->findBy($param);

        $livres = [];

        foreach ($auteurs as $auteur) {
            $livres = array_merge($livres, $auteur->getLivres()->toArray());
        }

        return $this->json($livres, 200, [], ['groups' => 'auteur:read']);
    }
}

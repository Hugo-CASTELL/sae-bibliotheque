<?php

namespace App\Controller\Api;

use App\Repository\AuteurRepository;
use App\Repository\CategorieRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/api/livres/search', name: 'app_api_livres_search')]
    public function searchLivres(Request $request, LivreRepository $livreRepository): JsonResponse
    {
        $param = [];

        $titre = $request->query->get('titre');

        if ($titre) {
            $param['titre'] = $titre;
        }

        $langue = $request->query->get('langue');

        if ($langue) {
            $param['langue'] = $langue;
        }

        $beforeDate = $request->query->get('before_date');

        if ($beforeDate) {
            $param['before_date'] = $beforeDate;
        }

        $afterDate = $request->query->get('after_date');

        if ($afterDate) {
            $param['after_date'] = $afterDate;
        }

        $categorie = $request->query->get('categorie');
        if ($categorie) {
            $param['categorie_nom'] = $categorie;
        }

        $auteurNom = $request->query->get('auteur_nom');

        if ($auteurNom) {
            $param['auteurs_nom'] = $auteurNom;
        }

        $auteurPrenom = $request->query->get('auteur_prenom');

        if ($auteurPrenom) {
            $param['auteurs_prenom'] = $auteurPrenom;
        }

        $auteurNationalite = $request->query->get('auteur_nationalite');

        if ($auteurNationalite) {
            $param['auteurs_nationalite'] = $auteurNationalite;
        }

        $sort = $request->query->get('sort');

        $sortParam = null;
        if ($sort) {
            $param['sort'] = explode(':', $sort);
        }

        $offset = $request->query->get('offset');
        $param['offset'] = $offset ? $offset : 0;
        
        $limit = $request->query->get('limit');
        $param['limit'] = $limit ? $limit : 10;

        $livres = $livreRepository->search($param);

        return $this->json($livres, 200, [], ['groups' => 'livre:read']);
    }

    #[Route('/api/livres/total', name: 'app_api_livres_total')]
    public function total(LivreRepository $livreRepository): JsonResponse
    {
        $total = $livreRepository->count([]);
        return $this->json($total, 200);
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

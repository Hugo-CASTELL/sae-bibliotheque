<?php

namespace App\Controller\Api;

use App\Repository\AdherentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reservations;
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use App\Repository\ReservationsRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class ReservationController extends AbstractController
{
    #[Route('/api/user/reservations', name: 'app_api_user_reservations')]
    public function index(AdherentRepository $adherentRepository): JsonResponse
    {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $adherent = $adherentRepository->findOneBy(['email' => $user->getEmail()]);

        $reservations = $adherent->getReservations();

        return $this->json($reservations, 200, [], ['groups' => 'reservations:read']);
    }

    #[Route('/api/user/reservations/create', name: 'app_api_user_reservation_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, AdherentRepository $adherentRepository, LivreRepository $livreRepository, ReservationsRepository $reservationsRepository, EmpruntRepository $empruntRepository): JsonResponse
    {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $adherent = $adherentRepository->findOneBy(['email' => $user->getEmail()]);

        if ($adherent->getReservations()->count() >= 3) {
            return $this->json(['message' => 'You can\'t have more than 3 reservations'], 400);
        }

        $data = json_decode($request->getContent(), true);

        $livre = $livreRepository->find($data['livre']);

        if ($reservationsRepository->findOneBy(['livre' => $livre])) {
            return $this->json(['message' => 'There is already a reservation for this book'], 400);
        }

        $empruntLivres = $empruntRepository->findBy(['livre' => $livre]);

        foreach ($empruntLivres as $empruntLivre) {
            if ($empruntLivre->getDateRetour() === null) {
                return $this->json(['message' => 'This book is already borrowed'], 400);
            }
        }

        $reservation = new Reservations();
        $reservation->setDateResa(new \DateTimeImmutable());
        $reservation->setAdherent($adherent);
        $reservation->setLivre($livre);

        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->json($reservation, 201, [], ['groups' => 'reservations:read']);
    }

    #[Route('/api/user/reservations/{id}', name: 'app_api_user_reservation_id', methods: ['GET'])]
    public function show(int $id, AdherentRepository $adherentRepository, ReservationsRepository $reservationsRepository): JsonResponse
    {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $adherent = $adherentRepository->findOneBy(['email' => $user->getEmail()]);

        $reservation = $reservationsRepository->findOneBy(['id' => $id, 'adherent' => $adherent]);

        return $this->json($reservation, 200, [], ['groups' => 'reservations:read']);
    }

    #[Route('/api/user/reservations/{id}', name: 'app_api_user_reservation_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager, AdherentRepository $adherentRepository, ReservationsRepository $reservationsRepository): JsonResponse
    {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $adherent = $adherentRepository->findOneBy(['email' => $user->getEmail()]);

        $reservation = $reservationsRepository->findOneBy(['id' => $id, 'adherent' => $adherent]);

        if ($reservation === null) {
            return $this->json(['message' => 'Reservation not found'], 404);
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        return $this->json(null, 204);
    }
}

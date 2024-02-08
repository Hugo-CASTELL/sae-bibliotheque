import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Reservations } from '../models/reservations';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-reservation',
  templateUrl: './reservation.component.html',
  styleUrl: './reservation.component.css'
})
export class ReservationComponent {

  public reservations: Reservations[] = [];

  constructor(private authService: AuthService, private router: Router, private route: ActivatedRoute, private apiService: ApiService) {
    this.authService.isLogged().subscribe((isLogged) => {
      if (!isLogged) {
        this.navigateToLoginPage();
      }
    });

    this.apiService.getUser().subscribe((response) => {
      if(!response.adherent) {
        this.navigateToAccountPage();
      }
      else {
        this.reservations = response.adherent.reservations;
        console.log("Réservations utilisateur (page réservation) :");
        console.log(this.reservations);
      }
    });
  }

  deleteReservation(id: number) {
    console.log("Delete réservation " + id);
  }

  navigateToLoginPage() {
    this.router.navigate(['../login'], { relativeTo: this.route });
  }

  navigateToAccountPage() {
    this.router.navigate(['../account'], { relativeTo: this.route });
  }
  
}

document.addEventListener("DOMContentLoaded", function () {
  // Récupère les éléments des cartes et du bouton
  const cards: NodeListOf<Element> = document.querySelectorAll('.card');
  const reservationButton: HTMLElement | null = document.getElementById('reservationButton');

  // Vérifie si toutes les cartes ont la classe "hide"
  function areAllCardsVisible(): boolean {
    return Array.from(cards).every((card: Element) => !card.classList.contains('hide'));
  }

  // Fonction pour mettre à jour la visibilité du bouton
  function updateReservationButtonVisibility(): void {
    if (reservationButton) {
      if (areAllCardsVisible()) {
        reservationButton.classList.add('hide');
      } else {
        reservationButton.classList.remove('hide');
      }
    }
  }

  // Ajoute un écouteur d'événements pour chaque carte
  cards.forEach((card: Element) => {
    card.addEventListener('transitionend', updateReservationButtonVisibility);
  });

  // Appelle la fonction initiale pour définir l'état initial du bouton
  updateReservationButtonVisibility();
});


import { Component } from '@angular/core';

@Component({
  selector: 'app-reservation',
  templateUrl: './reservation.component.html',
  styleUrl: './reservation.component.css'
})
export class ReservationComponent {
  
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


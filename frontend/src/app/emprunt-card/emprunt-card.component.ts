import { Component, Input } from '@angular/core';
import { Emprunt } from '../models/emprunt';

@Component({
  selector: 'app-emprunt-card',
  templateUrl: './emprunt-card.component.html',
  styleUrl: './emprunt-card.component.css'
})
export class EmpruntCardComponent {

  @Input() emprunt?: Emprunt;

  public get dateEmpruntString(): string {
    if (!this.emprunt || !this.emprunt.dateEmprunt) {
      return "";
    }

    let date = new Date(this.emprunt.dateEmprunt);
    return date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
  }

  public get tempsRestant(): number {
    if (!this.emprunt || !this.emprunt.dateEmprunt) {
      return 0;
    }

    let dateEmprunt = new Date(this.emprunt.dateEmprunt);
    let dateActuelle = new Date();

    let Difference_In_Time = dateActuelle.getTime() - dateEmprunt.getTime();
    let Difference_In_Days = Math.round (Difference_In_Time / (1000 * 3600 * 24));
    let joursRestants = 22 - Difference_In_Days;

    return joursRestants;
  }

  public get tempsRestantString(): string {
    let joursRestants = this.tempsRestant;

    return joursRestants < 0 ? `${-joursRestants} jours de retard` : `${joursRestants} jours restants`;

  }
}

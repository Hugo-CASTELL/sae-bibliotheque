import { Component, Input } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../services/api.service';
import { Reservations } from '../models/reservations';

@Component({
  selector: 'app-reservation-card',
  templateUrl: './reservation-card.component.html',
  styleUrl: './reservation-card.component.css'
})
export class ReservationCardComponent {

  @Input() id?: number;

  idLivre?: number = undefined;
  photoLivre?: string = "";
  titreLivre?: string = "";
  dateReservation?: string = "";
  joursRestants?: number = undefined;

  cancelReservationFailed: boolean = false;


  constructor(private authService: AuthService, private router: Router, private route: ActivatedRoute, private apiService: ApiService) {
  }

  ngOnInit() {
    console.log(this.id);
    this.apiService.getReservation(this.id).subscribe((res: Reservations) => {
      this.idLivre = res.livre.id;
      this.photoLivre = res.livre.photoCouverture;
      this.titreLivre = res.livre.titre;
      if (res && res.dateResa) {
        this.dateReservation = new Date(res.dateResa).getDate() + "/" + (new Date(res.dateResa).getMonth() + 1) + "/" + new Date(res.dateResa).getFullYear();
        let Difference_In_Time = new Date().getTime() - new Date(res.dateResa).getTime();
        let Difference_In_Days = Math.round (Difference_In_Time / (1000 * 3600 * 24));
        this.joursRestants = 7 - Difference_In_Days;
        if (this.joursRestants < 0) {this.joursRestants = 0}
      }
    });
  }

  deleteReservation() {
    this.apiService.deleteReservation(this.id).subscribe((response) => {
      window.location.reload();
    },
    (error) => {
      this.cancelReservationFailed = true;
    });
  }

  bookDetails() {
    this.router.navigate(['../livre/'+this.idLivre], { relativeTo: this.route });
  }
}

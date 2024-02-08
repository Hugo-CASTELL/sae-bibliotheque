import { Component, Input } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../services/api.service';
import { Reservations } from '../models/reservations';
import { Livre } from '../models/livre';

@Component({
  selector: 'app-reservation-card',
  templateUrl: './reservation-card.component.html',
  styleUrl: './reservation-card.component.css'
})
export class ReservationCardComponent {

  @Input() id?: number;

  photoLivre?: string = "";


  constructor(private authService: AuthService, private router: Router, private route: ActivatedRoute, private apiService: ApiService) {
  }

  ngOnInit() {
    console.log(this.id);
    this.apiService.getReservation(this.id).subscribe((res: Reservations) => {
      console.log("RESERVATION card id " + this.id);
      console.log(res);
    });
  }

  deleteReservation() {
    console.log("Delete réservation " + this.id);
  }
}

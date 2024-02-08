import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../services/api.service';
import { Emprunt } from '../models/emprunt';

@Component({
  selector: 'app-emprunt',
  templateUrl: './emprunt.component.html',
  styleUrl: './emprunt.component.css'
})
export class EmpruntComponent {

  public emprunts: Emprunt[] = [];

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
        this.emprunts = response.adherent.emprunts.filter((emprunt: any) => { return emprunt.dateRetour === null});

        // for (let emprunt of this.emprunts) {
        //   if(emprunt.id){
        //     this.apiService.getEmprunt(emprunt.id).subscribe((response: any) => {
        //       if (response.livre) {
        //         emprunt.livre = response.livre;
        //       }
        //     })
        //   }
        // }
        console.log("Emprunts utilisateur (page emprunt) :");
        console.log(this.emprunts);
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

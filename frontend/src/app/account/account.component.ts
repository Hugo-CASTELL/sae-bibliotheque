import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';
import { inputUpdateAccount } from '../models/api/input/inputUpdateAccount';
import { ApiService } from '../services/api.service';
import { catchError, map, switchMap, throwError } from 'rxjs';
import { Adherent } from '../models/adherent';

@Component({
  selector: 'app-account',
  templateUrl: './account.component.html',
  styleUrl: './account.component.css'
})
export class AccountComponent {

  //TODO : récupérer ici les informations de l'utilisateur connecté
  form = new inputUpdateAccount();
  public user: Adherent = new Adherent();
  public errorMessage: string = "";
  public confirmationMessage: string = "";
  public isAdherent: boolean = true;

  private mailBeforeUpdate: string = "";

  constructor(private authService: AuthService, private router: Router, private route: ActivatedRoute, private apiService: ApiService) {
    this.authService.isLogged().subscribe((isLogged) => {
      if (!isLogged) {
        this.navigateToLoginPage();
      }
    });

    this.apiService.getUser().subscribe((response) => {
      console.log(response);
      if(!response.adherent) {
        this.isAdherent = false;
      }
      else {
        this.mailBeforeUpdate = response.adherent.email;
        this.user = response.adherent;
      }
    });
  }

  onSubmit() {

    this.apiService.updateAccount(this.user).subscribe(
      data => {
        this.confirmationMessage = "Vos informations personnelles ont bien été mises à jour !";
        this.errorMessage = "";
        console.log(this.mailBeforeUpdate);
        console.log(this.user.email);
        if (this.mailBeforeUpdate != this.user.email) {
          this.authService.logOut();
          this.navigateToLoginPage();
        }
      },
      error => {
        this.confirmationMessage = "";
        this.errorMessage = "Une erreur s'est produite lors de la mise à jour";
      }
    );

  }

  logout() {
    localStorage.removeItem('token');
    this.navigateToLoginPage();
  }

  navigateToLoginPage() {
    this.router.navigate(['../login'], { relativeTo: this.route });
  }

}

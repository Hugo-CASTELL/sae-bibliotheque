import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';
import { inputUpdateAccount } from '../models/api/input/inputUpdateAccount';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-account',
  templateUrl: './account.component.html',
  styleUrl: './account.component.css'
})
export class AccountComponent {

  //TODO : récupérer ici les informations de l'utilisateur connecté
  form = new inputUpdateAccount();

  constructor(private authService: AuthService, private router: Router, private route: ActivatedRoute, private apiService: ApiService) {
    if(this.authService.getToken() == null) {
      this.router.navigate(['../login'], { relativeTo: this.route });
    }
  }

  onSubmit() {

    this.apiService.updateAccount(this.form).subscribe((success) => {
      if (success.status == 200) {
        console.log('Mise à jour réussie');
        this.router.navigate(['../account'], { relativeTo: this.route });
      } else {
        console.log('Échec de la mise à jour');
      }
    });

  }

  logout() {
    localStorage.removeItem('token');
    this.router.navigate(['../login'], { relativeTo: this.route });
  }

}
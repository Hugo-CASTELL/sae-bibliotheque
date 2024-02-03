import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  user = {
    email: '',
    password: ''
  };

  constructor(private authService: AuthService, private router: Router, private route: ActivatedRoute) {
    if(this.authService.getToken() != null) {
      this.router.navigate(['../account'], { relativeTo: this.route });
    }
  }

  onSubmit() {
    this.authService.login(this.user).subscribe((success) => {
      if (success) {
        console.log('Connexion réussie');
        this.router.navigate(['../livres'], { relativeTo: this.route });
      } else {
        console.log('Échec de la connexion');
      }
    });
  }
}

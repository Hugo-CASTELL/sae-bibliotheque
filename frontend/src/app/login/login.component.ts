import { Component } from '@angular/core';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute, Router } from '@angular/router';
import { inputLogin } from '../models/api/input/inputLogin';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  input: inputLogin = { username : "", password : ""}
  errorMessage: string = "";

  constructor(private authService: AuthService, private router: Router, private route: ActivatedRoute) {
    this.authService.isLogged().subscribe((isLogged) => {
      if (isLogged) {
        this.navigateToAccountPage();
      }
    });
  }

  onSubmit() {
    this.authService.login(this.input).subscribe((success) => {
      if (success) {
        this.router.navigate(['../livres'], { relativeTo: this.route });
      } else {
        this.errorMessage = "Adresse mail ou mot de passe invalide. Veuillez réessayer";
      }
    });
  }

  navigateToAccountPage() {
    this.router.navigate(['../account'], { relativeTo: this.route });
  }
}

/*import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor() { }
}*/

import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private token: string | null = null;

  login(user: { email: string, password: string }): Observable<boolean> {
    if (user.email && user.password) {
      // Appel à l'API pour obtenir le token
      localStorage.setItem('token','abcdefg')
      return of(true);
    }
    return of(false);
  }

  getToken(): string | null {
    console.log('Token:', localStorage.getItem('token'));
    return localStorage.getItem('token');
  }
}

/*import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor() { }
}*/

import { Injectable } from '@angular/core';
import { Observable, catchError, of, switchMap } from 'rxjs';
import { ApiService } from './api.service';
import { inputLogin } from '../models/api/input/inputLogin';
import { outputLogin } from '../models/api/output/outputLogin';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private token: string | null = null;

  constructor(
    private apiService: ApiService
  ) {}

  login(input : inputLogin): Observable<boolean> {
    if (input.username && input.password) {
      return this.apiService.getToken(input).pipe(
        switchMap((response: outputLogin) => {
          if (response) {
            localStorage.setItem("token", response.token);
            return of(true);
          }
          return of(false);
        }),
        catchError(error => {
          console.error("Error while obtaining token", error);
          return of(false);
        })
      );
    } else {
      return of(false);
    }
  }

  getToken(): string | null {
    console.log('Token:', localStorage.getItem('token'));
    return localStorage.getItem('token');
  }
}

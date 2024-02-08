import { Injectable } from '@angular/core';
import { HttpClient, HttpEvent, HttpHeaders } from '@angular/common/http';
import { Observable, retry } from 'rxjs';

import { Livre } from '../models/livre';
import { Auteur } from '../models/auteur';
import { inputUpdateAccount } from '../models/api/input/inputUpdateAccount';
import { inputLogin } from '../models/api/input/inputLogin';
import { outputLogin } from '../models/api/output/outputLogin';
import { Categorie } from '../models/categorie';
import { Adherent } from '../models/adherent';
import { AuthService } from './auth.service';
import { Reservations } from '../models/reservations';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  private apiUrl = 'https://localhost:8008/api'; // URL de l'API Symfony

  constructor(
    private http: HttpClient
  ) {}

  getToken(data : inputLogin): Observable<outputLogin> {
    return this.http.post<outputLogin>(`${this.apiUrl}/login_check`,data).pipe();
  }

  getUser(): Observable<any> {
    return this.http.get<Adherent>(`${this.apiUrl}/user/me`, this.getHttpHeader());
  }

  getLivres(): Observable<Livre[]> {
    return this.http.get<Livre[]>(`${this.apiUrl}/livres`);
  }

  updateAccount(data: inputUpdateAccount): Observable<any> {
    return this.http.put(`${this.apiUrl}/user/me/update`, data, this.getHttpHeader());
  }

  getAuteur(id: string): Observable<Auteur> {
    return this.http.get<Auteur>(`${this.apiUrl}/auteurs/${id}`);
  }

  getCategories(): Observable<Categorie[]> {
    return this.http.get<Categorie[]>(`${this.apiUrl}/categories`)
  }

  getReservation(id?: number) : Observable<any> {
    return this.http.get<Reservations>(`${this.apiUrl}/user/reservations/${id}`, this.getHttpHeader());
  }

  private getHttpHeader(): any {
    return {
      headers: new HttpHeaders({
        'Authorization': `Bearer ${localStorage.getItem('token')}` // Ajouter le token JWT dans l'en-tête d'autorisation
      })
    };
  }

}

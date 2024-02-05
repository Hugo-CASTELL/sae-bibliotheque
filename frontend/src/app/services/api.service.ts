import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, retry } from 'rxjs';

import { Livre } from '../models/livre';
import { inputUpdateAccount } from '../models/api/inputUpdateAccount';
import { Auteur } from '../models/auteur';
import { inputUpdateAccount } from '../models/api/input/inputUpdateAccount';
import { inputLogin } from '../models/api/input/inputLogin';
import { outputLogin } from '../models/api/output/outputLogin';

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

  getLivres(): Observable<Livre[]> {
    return this.http.get<Livre[]>(`${this.apiUrl}/livres`);
  }

  updateAccount(data: inputUpdateAccount): Observable<any> {
    console.log(data);
    return this.http.put(`${this.apiUrl}/member/submit`, data);
  }

  getAuteur(id: string): Observable<Auteur> {
    return this.http.get<Auteur>(`${this.apiUrl}/auteurs/${id}`);
  }

}

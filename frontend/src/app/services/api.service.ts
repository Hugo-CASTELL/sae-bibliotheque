import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

import { Livre } from '../models/livre';
import { inputUpdateAccount } from '../models/api/inputUpdateAccount';
import { Auteur } from '../models/auteur';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  private apiUrl = 'https://localhost:8008/api'; // URL de l'API Symfony

  constructor(
    private http: HttpClient
  ) {}

  // Lister les catégories
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

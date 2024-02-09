import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Livre } from '../models/livre';
import { ApiService } from '../services/api.service';
import { Location } from '@angular/common';

@Component({
  selector: 'app-livre-details',
  templateUrl: './livre-details.component.html',
  styleUrl: './livre-details.component.css'
})
export class LivreDetailsComponent {
  id: number = 0;

  public livre: Livre = new Livre([], []);
  public get dateSortie() { return this.livre.dateSortie ? new Date(this.livre.dateSortie).toLocaleDateString() : 'Non spécifiée' ; }
  public get auteurs() { return this.livre.auteurs.map(auteur => `${auteur.prenom} ${auteur.nom}`).join(' - '); }
  public get categories() { return this.livre.categories.map(cat => `${cat.nom}`).join(' - '); }
  public get langue() { return this.livre.langue ? this.livre.langue : 'Non spécifiée'; }

  constructor(private route: ActivatedRoute, private apiService: ApiService, private location: Location) {}

  ngOnInit() {
    // Récupération de l'id
    this.route.params.subscribe(params => {
      this.id = +params['id'];

      // Récupération du livre
      this.apiService.getLivre(this.id).subscribe((data: Livre) => {
        this.livre = data;
      });
    });
  }

  backToLastPage(){
    this.location.back();
  }

}

import { Component } from '@angular/core';
import { Livre } from '../models/livre';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';
import { Categorie } from '../models/categorie';
import { Adherent } from '../models/adherent';

@Component({
  selector: 'app-livres-list',
  templateUrl: './livres-list.component.html',
  styleUrl: './livres-list.component.css'
})
export class LivresListComponent {

  livres: Livre[] = [];
  categories: Categorie[] = [];
  selectedCategory?: string = "";
  searchText: string = '';
  isAvailable: boolean = false;
  public user: Adherent | null = null;

  constructor(private apiService: ApiService, private authService: AuthService) {
    this.apiService.getUser().subscribe((response) => {
      this.user = response.adherent;
    });
  }

  ngOnInit(): void {

    //Récupération des livres
    this.apiService.getLivres().subscribe((data: Livre[]) => {
      this.livres = data;
    });

    //Récupération des catégories
    this.apiService.getCategories().subscribe((data: Categorie[]) => {
      this.categories = data;
    });
  }

  filterLivres(): Livre[] {

    let filteredResults;

    //Recherche par titre/auteur
    filteredResults = this.livres.filter(livre => livre.titre?.toLowerCase().includes(this.searchText.toLowerCase()) ||
                                                  livre.auteurs.some(auteur => (auteur.nom + " " + auteur.prenom).toLowerCase().includes(this.searchText.toLowerCase())) ||
                                                  livre.auteurs.some(auteur => (auteur.prenom + " " + auteur.nom).toLowerCase().includes(this.searchText.toLowerCase())));

    //Recherche par catégorie
    if(this.selectedCategory) {
      filteredResults = filteredResults.filter(livre => livre.categories.some(categorie => categorie.id == this.selectedCategory));
    }

    //Recherche par disponibilité
    if(this.isAvailable) {

      console.log("1/3 : " + filteredResults.length);

      //Le livre ne doit avoir aucune réservation en cours
      filteredResults = filteredResults.filter(livre => livre.reservations == null);

      console.log("2/3 : " + filteredResults.length);

      //Le livre doit pas être emprunté actuellement
      filteredResults = filteredResults.filter(livre => !livre.emprunts?.some(emprunt => emprunt?.dateRetour == null));

      console.log("3/3 : " + filteredResults.length);

    }

    return filteredResults;
  }

}

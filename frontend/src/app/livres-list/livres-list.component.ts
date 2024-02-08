import { Component } from '@angular/core';
import { Livre } from '../models/livre';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';
import { Categorie } from '../models/categorie';
import { Adherent } from '../models/adherent';
import { Reservations } from '../models/reservations';

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
  public reservationSuccess: boolean = false;

  public currentPage: number = 0;
  public nbPages: number = 0;
  public nbLivresOnPage: number = 25;
  public nbLivresTotal: number = this.nbLivresOnPage;
  public pages: number[] = [];

  constructor(private apiService: ApiService, private authService: AuthService) {}

  ngOnInit(): void {
    // Variables de pagination
    this.currentPage = 0;

    // Récupération des livres et de la pagination
    this.reloadLivres();

    //Récupération des catégories
    this.apiService.getCategories().subscribe((data: Categorie[]) => {
      this.categories = data;
    });

    // Récupération de l'utilisateur s'il est connecté
    this.reloadUser();
  }

  swapToPage(page: number) {
    this.currentPage = page;
    this.reloadLivres();
  }

  reloadLivres() {
    // Récupération du nombre total de livres
    this.apiService.getNbTotalLivres().subscribe((nb: number) => {

      // Récupération des livres dans l'intervalle de pagination
      this.apiService.getFilteredLivres(this.currentPage*this.nbLivresOnPage, this.nbLivresOnPage).subscribe((data: Livre[]) => {
        this.livres = data;
      });

      // Mise à jour de la pagination
      this.nbLivresTotal = nb;
      this.reloadPages();
    });
  }

  reloadPages() {
    this.nbPages = Math.ceil(this.nbLivresTotal / this.nbLivresOnPage);
    this.pages = Array(this.nbPages).fill(0).map((x,i)=>i);
  }

  reloadUser(){
    this.authService.isLogged().subscribe((isLogged) => {
      if(isLogged) {
        this.apiService.getUser().subscribe((response) => {
          // Récupération de l'utilisateur
          this.user = response.adherent;
        });
      }
    });
  }

  canReserveBook(idLivre: number) {
    let livre = this.livres.find(livre => livre.id == idLivre);
    let canReserve = false;

    if(livre){
      let isLivreDejaReserve = livre.reservations != null;

      // Si le livre est disponible
      if(!isLivreDejaReserve){
        if(this.user && this.user.reservations){
          let isUserDejaReserve = this.user.reservations.some(reservation => reservation.livre.id == idLivre);
          let isUserDejaTroisReservations = this.user.reservations.length >= 3;

          // On définit s'il peut réserver un livre
          canReserve = !isUserDejaReserve && !isUserDejaTroisReservations;
        }
      }
    }

    return canReserve;
  }

  createReservation(idLivre: any) {
    this.reservationSuccess = false;
    this.apiService.createReservation({livre: idLivre}).subscribe((response: any) => {
      this.reservationSuccess = true;
      this.reloadUser();

      // Reload du livre
      let livre = this.livres.find(livre => livre.id == idLivre)
      if(livre){
        livre.reservations = new Reservations(response.adherent, response.livre, response.id, response.dateResa);
      }
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

import { Component } from '@angular/core';
import { Livre } from '../models/livre';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';
import { Categorie } from '../models/categorie';
import { Adherent } from '../models/adherent';
import { Reservations } from '../models/reservations';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-livres-list',
  templateUrl: './livres-list.component.html',
  styleUrl: './livres-list.component.css'
})
export class LivresListComponent {

  livres: Livre[] = [];
  categories: Categorie[] = [];
  selectedCategory?: string = "";
  searchTitre: string = '';
  searchAuteurPrenom: string = '';
  searchAuteurNom: string = '';
  isAvailable: boolean = false;
  public user: Adherent | null = null;
  public reservationSuccess: boolean = false;

  public currentPage: number = 0;
  public nbPages: number = 0;
  public nbLivresOnPage: number = 25;
  public nbLivresTotal: number = 0;
  public pages: number[] = [];
  public noResult: boolean = false;

  constructor(private apiService: ApiService, private authService: AuthService, private router: Router, private route: ActivatedRoute) {}

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

  search(){
    this.currentPage = 0;
    this.reloadLivres();
  }

  reloadLivres() {
    // Récupération du nombre total de livres
    this.apiService.getNbTotalLivres(this.constructAdditionalFilter()).subscribe((nb: number) => {

      // Récupération des livres dans l'intervalle de pagination
      this.apiService.getFilteredLivres(this.currentPage, this.nbLivresOnPage, this.constructAdditionalFilter()).subscribe((data: Livre[]) => {
        this.livres = data;
        console.log(data);
        if (data.length == 0) {
          this.noResult = true;
        } else {
          this.noResult = false;
        }
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
      let isLivreDejaEmprunte = livre.emprunts != null && livre.emprunts.some(emprunt => emprunt.dateRetour == null);

      // Si le livre est disponible
      if(!isLivreDejaReserve && !isLivreDejaEmprunte){
        if(this.user && this.user.reservations){
          let isUserDejaReserve = this.user.reservations.some(reservation => reservation.livre && reservation.livre.id && reservation.livre.id == idLivre);
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

  constructAdditionalFilter(): string {

    let additionalFilter: string = '';

    // Définir les conditions de filtrage dans la requête
    if(this.selectedCategory){
      additionalFilter += "&categorie=" + this.categories.find(categorie => categorie.id == this.selectedCategory)?.nom;
    }

    if(this.searchTitre != ''){
      additionalFilter += "&titre=" + this.searchTitre;
    }

    if(this.searchAuteurNom != ''){
      additionalFilter += "&auteur_nom=" + this.searchAuteurNom;
    }

    if(this.searchAuteurPrenom != ''){
      additionalFilter += "&auteur_prenom=" + this.searchAuteurPrenom;
    }

    console.log("Additional filter");
    console.log(additionalFilter);
    return additionalFilter;
  }

  bookDetails(idLivre?: number) {
    this.router.navigate(['../livre/'+idLivre], { relativeTo: this.route });
  }

}

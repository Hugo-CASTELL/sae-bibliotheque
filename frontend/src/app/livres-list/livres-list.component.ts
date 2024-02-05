import { Component } from '@angular/core';
import { Livre } from '../models/livre';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';
import { Auteur } from '../models/auteur';

@Component({
  selector: 'app-livres-list',
  templateUrl: './livres-list.component.html',
  styleUrl: './livres-list.component.css'
})
export class LivresListComponent {
  
  livres: Livre[] = [];
  selectedCategory: string = 'Catégorie';
  searchText: string = '';
  isAvailable: boolean = true;

  constructor(private apiService: ApiService, private authService: AuthService) {}

  ngOnInit(): void {
    console.log("ok");
    this.apiService.getLivres().subscribe((data: any) => {
      this.livres = data["hydra:member"];
      
      /*this.apiService.getAuteur("8").subscribe((data2: any) => {
        console.log(data2);
      });*/

    });
  }

  filterLivres(): Livre[] {
    console.log("Recherche");

    this.livres.forEach(livre => {
      console.log(livre.auteurs);
    });

    return this.livres.filter(livre =>
      /*(this.selectedCategory === 'Catégorie' || livre.categories[0].nom == this.selectedCategory) &&*/
      (livre.titre?.toLowerCase().includes(this.searchText.toLowerCase()) ||
       livre.auteurs?.toString().toLowerCase().includes(this.searchText.toLowerCase())) &&
      (this.isAvailable || livre.emprunts?.length === 0 && livre.reservations === null)
    );
  }

}

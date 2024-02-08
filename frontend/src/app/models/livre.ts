import { Auteur } from "./auteur";
import { Categorie } from "./categorie";
import { Emprunt } from "./emprunt";
import { Reservations } from "./reservations";

export class Livre {
    constructor(
        public categories: Categorie[],
        public auteurs: Auteur[],
        public reservations?: Reservations,
        public emprunts?: Emprunt[],
        public id?: number,
        public titre?: string,
        public dateSortie?: Date,
        public langue?: string,
        public photoCouverture?: string,
    ) {}
}

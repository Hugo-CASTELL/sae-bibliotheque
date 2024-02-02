import { Adherent } from "./adherent";
import { Livre } from "./livre";

export class Emprunt {
    constructor(
        public adherent: Adherent,
        public livre: Livre,
        public id?: number,
        public dateEmprunt?: Date,
        public dateRetour?: Date,
    ) {}
}
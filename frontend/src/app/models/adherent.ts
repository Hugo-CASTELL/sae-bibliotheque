import { Emprunt } from "./emprunt";
import { Reservations } from "./reservations";

export class Adherent {
    constructor(
        public reservations?: Reservations[],
        public emprunts?: Emprunt[],
        public id?: number,
        public dateAdhesion?: Date,
        public nom?: string,
        public prenom?: string,
        public dateNaissance?: Date,
        public email?: string,
        public adressePostale?: string,
        public numTel?: string,
        public photo?: string,
    ) {}
}
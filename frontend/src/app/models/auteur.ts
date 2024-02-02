import { Livre } from "./livre";

export class Auteur {
    constructor(
        public livres: Livre[],
        public id?: number,
        public nom?: string,
        public prenom?: string,
        public dateNaissance?: Date,
        public dateDeces?: Date,
        public nationalite?: string,
        public photo?: string,
        public description?: string,
    ) {}
}
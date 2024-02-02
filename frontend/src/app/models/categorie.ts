import { Livre } from "./livre";

export class Categorie {
    constructor(
        public livres: Livre[],
        public id?: number,
        public nom?: string,
        public description?: string,
    ) {}
}
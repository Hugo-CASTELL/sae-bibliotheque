import { Adherent } from "./adherent";
import { Livre } from "./livre";

export class Reservations {
    constructor(
        public adherent: Adherent,
        public livre: Livre,
        public id?: number,
        public dateResa?: Date,
    ) {}
}
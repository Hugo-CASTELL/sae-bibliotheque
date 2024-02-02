export class Utilisateur {
    constructor(
        public id?: number,
        public email?: string,
        public roles?: string[],
        public password?: string,
    ) {}
}
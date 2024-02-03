export class inputUpdateAccount {
    constructor(
        public nom?: string,
        public prenom?: string,
        public email?: string,
        public dateNaissance?: Date,
        public adressePostale?: string,
        public numTel?: string,
        public photo?: string,
        public newPassword?: string,
    ) {}
}
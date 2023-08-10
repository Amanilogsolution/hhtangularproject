
export class User {
    constructor(
        public userName: string,
        public userId: string,
        private _token: string,
        private _tokenExpirationDate: Date,
        public warehouseLocation:any
    ) { }

    get token() {
        if(!this._tokenExpirationDate || new Date()> this._tokenExpirationDate){
            return null
        }
        return this._token;
    }
}

export default class Cookie {
    constructor(name, value, expiryTime = null, path = '/') {
        this._name = name;
        this._value = value;
        this._expiryTime = expiryTime;
        this._path = path;
    }

    getName() {
        return this._name;
    }

    getValue() {
        return this._value;
    }

    getExpiryTime() {
        return this._expiryTime;
    }

    getPath() {
        return this._path;
    }
}
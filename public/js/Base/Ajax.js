class Ajax {
    xhttp = new XMLHttpRequest();

    url;

    successCallback;

    errorCallback;

    constructor(url) {
        this.url = url;

        return this;
    }

    setSuccessCallback = (successCallback) => {
        this.successCallback = successCallback;

        return this;
    }

    setErrorCallback = (errorCallback) => {
        this.errorCallback = errorCallback;

        return this;
    }

    execute = () => {
        if (!this._isValid()) {
            return;
        }

        this._applyOnreadystatechange();

        this.xhttp.open("POST", this.url, true);
        this.xhttp.send();
    }

    _isValid = () => {
        if (!this._isFunction(this.successCallback)) {
            throw new Error('No success callback provided');
        }

        if (!this._isFunction(this.errorCallback)) {
            throw new Error('No error callback provided');
        }

        return true;
    }

    _isFunction = (callback) => {
        return callback && {}.toString.call(callback) === '[object Function]';
    }

    _applyOnreadystatechange = () => {
        const me = this;

        this.xhttp.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE) {
                // TODO: REMOVE AFTER DEBUG
                console.log(this);
                // TODO: REMOVE AFTER DEBUG
                if (this.status === 200) {
                    me.successCallback(JSON.parse(this.responseText));
                } else {
                    me.errorCallback(JSON.parse(this.responseText));
                }
            }
        };
    }
}
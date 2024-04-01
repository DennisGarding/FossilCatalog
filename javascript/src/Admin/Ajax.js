export default class Ajax {
    xhttp;

    url;

    successCallback;

    errorCallback;

    constructor(url) {
        this.xhttp = new XMLHttpRequest()
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
                if (me.xhttp.status === 204) {
                    return;
                }

                let response;

                try {
                    response = JSON.parse(me.xhttp.response);
                } catch (e) {
                    // throw new Error(me.url);
                    throw new Error(
                        'Invalid response from server. Expected JSON.',
                        `URL: ${me.url} MESSAGE: ${e.message} RESPONSE: ${me.xhttp.response}`,
                        e.trace
                    );
                }

                if (this.status === 200) {
                    me.successCallback(response);
                } else {
                    me.errorCallback(response);
                }
            }
        };
    }
}
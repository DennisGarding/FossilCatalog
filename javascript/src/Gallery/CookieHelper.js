export default class CookieHelper {
    oneYear = 31536000000;
    sixHours = 21600;

    getCookie(name) {
        const cookies = `; ${document.cookie}`;
        const value = cookies.split(`; ${name}=`);

        if (value.length === 2) {
            return value.pop().split(';').shift();
        }

        return null;
    }

    setCookie(cookie) {
        let expiryTime = cookie.getExpiryTime();
        if (expiryTime === null) {
            expiryTime = this.getDefaultExpireTime();
        }

        document.cookie = `${cookie.getName()}=${cookie.getValue()}; path=${cookie.getPath()}; expires=${expiryTime};`;
    }

    getDefaultExpireTime() {
        return new Date(new Date().getTime() + this.oneYear).toGMTString()
    }
}
import Ajax from "./Ajax";

export default class KeepSessionAlive {
    selector = '[data-keep-session="true"]';

    interval = 60000;

    counter = 0;

    maxCounter = 30;

    intervalId = null;

    url = null;

    logoutUrl = null;

    constructor() {
        const element = document.querySelector(this.selector);
        this.url = element.dataset.url;
        this.logoutUrl = element.dataset.logouturl;

        if (!this.url) {
            throw new Error('No url provided');
        }

        if (!this.logoutUrl) {
            throw new Error('No logout url provided');
        }

        this.start();
    }

    start() {
        this.intervalId = setInterval(() => {
            // this.counter++;
            // if (this.counter > this.maxCounter) {
            //     this.stop();
            //     window.location.href = this.logoutUrl;
            //
            //     return;
            // }

            const ajax = new Ajax(this.url);
            ajax.setSuccessCallback(() => {});
            ajax.setErrorCallback(() => {
                window.location.reload();
            });
            ajax.execute();
        }, this.interval);
    }

    stop() {
        clearInterval(this.intervalId);
    }
}
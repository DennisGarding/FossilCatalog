import SetLoading from "./SetLoading";

export default class SubmitButton {
    disabledClass = 'disabled';

    submitButton;

    constructor(button) {
        this.submitButton = button;

        this.registerEvents();
    }

    registerEvents() {
        this.submitButton.addEventListener('click', this.onSubmit.bind(this));
    }

    onSubmit() {
        this.submitButton.classList.add(this.disabledClass);
        new SetLoading(this.submitButton);
    }
}
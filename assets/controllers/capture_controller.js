import { Controller } from "@hotwired/stimulus";

export default class extends Controller {

    static targets = ["container"];

    connect() {
        console.log('Capture controller');
        if (!this.index) {
            this.index = this.element.querySelectorAll('fieldset').length;
        }
    }

    add(event) {
        event.preventDefault();
        const prototype = this.element.dataset.prototype;
        const html = prototype.replace(/__name__/g, this.index);
        const temp = document.createElement('div');
        temp.innerHTML = html;
        const fieldset = temp.firstElementChild;

        this.element.insertBefore(fieldset, event.target);
        this.index++;
    }

    remove(event) {
        event.preventDefault();
        event.target.closest('fieldset').remove();
    }
}

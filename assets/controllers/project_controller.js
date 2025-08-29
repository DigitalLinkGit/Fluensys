import { Controller } from "@hotwired/stimulus";

export default class extends Controller {

    static targets = ["container"];

    connect() {
        console.log('Project controller');
        if (!this.index) {
            this.index = this.element.querySelectorAll('fieldset').length;
        }
    }

    add(event) {
        console.log("CLIC ADD");
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
        console.log("CLIC DELETE");
        event.preventDefault();
        event.target.closest('fieldset').remove();
    }
}

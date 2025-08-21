// controllers/capture_element_controller.js
/*
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["modalBody"];

    connect() {
        this.index = 0;
    }

    showModal() {
        const proto = document.querySelector('#field-prototype').innerHTML;
        const html = proto.replace(/__name__/g, this.index);
        this.modalBodyTarget.innerHTML = html;
    }



    validate(event) {
        event.preventDefault();

        // Ici, le champ a été injecté dans le DOM. Symfony le prendra.
        this.index++;
        document.querySelector('#addFieldModal .btn-close').click();
    }
}*/



/*import { Controller } from "@hotwired/stimulus";

export default class extends Controller {

    static targets = ["container"];

    connect() {
        console.log('CE controller');
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

        this.element.insertBefore(fieldset, event.target); // avant le bouton "Ajouter"
        this.index++;
    }

    remove(event) {
        console.log("CLIC DELETE");
        event.preventDefault();
        event.target.closest('fieldset').remove();
    }
}*/

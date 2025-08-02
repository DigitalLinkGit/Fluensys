// controllers/alert_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static values = {
        autodismiss: Number
    }

    connect() {
        const delay = this.autodismissValue || 3000;
        setTimeout(() => {
            this.element.classList.add("fade");
            this.element.addEventListener("transitionend", () => this.element.remove());
        }, delay);
    }
}

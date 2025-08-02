// controllers/sidebar_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["panel", "toggle"]

    connect() {
        document.addEventListener("click", this.closeIfOutside.bind(this))
    }

    disconnect() {
        document.removeEventListener("click", this.closeIfOutside.bind(this))
    }

    toggle() {
        const isOpen = getComputedStyle(this.panelTarget).transform === "matrix(1, 0, 0, 1, 0, 0)"
        this.panelTarget.style.transform = isOpen ? "translateX(-100%)" : "translateX(0)"
    }

    closeIfOutside(event) {
        if (!this.panelTarget.contains(event.target) &&
            !this.toggleTarget.contains(event.target) &&
            getComputedStyle(this.panelTarget).transform === "matrix(1, 0, 0, 1, 0, 0)") {
            this.panelTarget.style.transform = "translateX(-100%)"
        }
    }
}

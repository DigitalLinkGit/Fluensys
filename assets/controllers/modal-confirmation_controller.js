// controllers/confirmation_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["modal", "form", "body", "token"]
    static values = {
        action: String,
        token: String,
        message: String
    }

    open(event) {
        event.preventDefault()

        this.formTarget.action = this.actionValue
        this.tokenTarget.value = this.tokenValue
        this.bodyTarget.textContent = this.messageValue || "Êtes-vous sûr de vouloir continuer ?"

        new bootstrap.Modal(this.modalTarget).show()
    }
}

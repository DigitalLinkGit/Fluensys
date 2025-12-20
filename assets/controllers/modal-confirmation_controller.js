// controllers/modal-confirmation_controller.js
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

        const btn = event.currentTarget
        this.formTarget.action = btn.dataset.modalConfirmationActionValue
        this.tokenTarget.value = btn.dataset.modalConfirmationTokenValue
        this.bodyTarget.textContent = btn.dataset.modalConfirmationMessageValue
        console.log('action : ' + this.actionValue)
        console.log('value : ' + this.tokenTarget.value)
        console.log('textContent : ' + this.bodyTarget.textContent)
        new bootstrap.Modal(this.modalTarget).show()
    }

}

import { Controller } from "@hotwired/stimulus";
const Modal = window.bootstrap.Modal;


export default class extends Controller {
    static targets = ["modal", "modalBody"];
    static values = {
        url: String
    }

    connect() {
        this.bsModal = new Modal(this.modalTarget);
    }

    async open(event) {
        event.preventDefault()
        const url = this.urlValue // 🔥 ici

        const response = await fetch(url)
        const html = await response.text()
        console.log("💬 Contenu reçu :", html)
        this.modalBodyTarget.innerHTML = html
        this.bsModal.show()
    }



    submit(event) {
        event.preventDefault()

        const form = this.modalBodyTarget.querySelector("form")
        console.log("📋 Form trouvé :", form)

        if (!form) {
            console.error("⛔ Aucun formulaire trouvé dans modalBodyTarget")
            return
        }

        const url = form.action
        const formData = new FormData(form)

        fetch(url, {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload() // comportement validé avec toi
                } else {
                    console.error("⛔ Erreur lors de la création")
                }
            })
            .catch(error => {
                console.error("⛔ Erreur réseau :", error)
            })
    }

}

import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["flash"];

    connect() {
        this.isDeleting = false;
    }

    async remove(event) {
        event.preventDefault();

        if (this.isDeleting) return;

        const button = event.currentTarget;
        const url = button.dataset.deleteUrl;
        const csrf = button.dataset.csrf;

        if (!url || !csrf) {
            this.notify("Configuration invalide (URL/CSRF manquants).", "danger");
            return;
        }

        const confirmed = window.confirm("Confirmer la suppression de cet élément ?");
        if (!confirmed) return;

        this.isDeleting = true;
        button.disabled = true;

        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json, text/plain, */*",
                },
                body: new URLSearchParams({ _token: csrf }).toString(),
            });

            if (!response.ok) {
                throw new Error(`Delete failed with status ${response.status}`);
            }

            const card = button.closest(".card");
            const row = button.closest(".row");

            (row || card)?.remove();

            this.notify("Élément supprimé.", "success");
        } catch (e) {
            console.error(e);
            this.notify("Erreur lors de la suppression.", "danger");
            button.disabled = false;
        } finally {
            this.isDeleting = false;
        }
    }

    notify(message, level = "info") {
        if (!this.hasFlashTarget) return;

        this.flashTarget.innerHTML = "";

        const alert = document.createElement("div");
        alert.className = `alert alert-${level} mb-2`;
        alert.setAttribute("role", "alert");
        alert.textContent = message;

        this.flashTarget.appendChild(alert);

        window.setTimeout(() => {
            alert.remove();
        }, 4000);
    }
}

import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["panel", "toggle"]

    connect() {
        this._onDocClick = this.closeIfOutside.bind(this)
        document.addEventListener("click", this._onDocClick)

        // Start collapsed
        this.setOpen(false)
    }

    disconnect() {
        document.removeEventListener("click", this._onDocClick)
    }

    toggle() {
        const isOpen = this.panelTarget.dataset.open === "1"
        this.setOpen(!isOpen)
    }

    setOpen(isOpen) {
        // Persist state
        this.panelTarget.dataset.open = isOpen ? "1" : "0"

        // Width change (no transform)
        this.panelTarget.style.transform = "none"
        this.panelTarget.style.width = isOpen ? "250px" : "64px"

        // Show/hide labels in the same column
        const labels = this.panelTarget.querySelectorAll(".sidebar-link-label, .section-label")
        labels.forEach((el) => {
            el.style.display = isOpen ? "" : "none"
        })

        // Optional: keep section spacing cleaner when collapsed
        const titles = this.panelTarget.querySelectorAll(".sidebar-section-title")
        titles.forEach((el) => {
            el.style.justifyContent = isOpen ? "" : "center"
        })
    }

    closeIfOutside(event) {
        const isOpen = this.panelTarget.dataset.open === "1"
        if (!isOpen) return

        if (!this.panelTarget.contains(event.target) && !this.toggleTarget.contains(event.target)) {
            this.setOpen(false)
        }
    }
}

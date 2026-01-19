import { Controller } from "@hotwired/stimulus";

export default class extends Controller {

    toggle(event) {
        const fs = event.currentTarget.closest("fieldset");
        if (!fs) return;
        this.setCollapsed(fs, !fs.classList.contains("is-collapsed"));
    }

    setCollapsed(fieldset, collapsed) {
        const body = fieldset.querySelector(".field-card__body");
        const icon = fieldset.querySelector(".field-card__toggle i");
        if (!body) return;
        fieldset.classList.toggle("is-collapsed", collapsed);
        body.style.display = collapsed ? "none" : "";
        if (icon) {
            icon.classList.toggle("bi-chevron-down", collapsed);
            icon.classList.toggle("bi-chevron-up", !collapsed);
        }
    }
}

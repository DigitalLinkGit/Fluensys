// No French in code comments per project convention
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["input"];

    connect() {
        if (!this.hasInputTarget) return;
        const el = this.inputTarget;

        // CLICK SUR LE CONTENEUR → focus input
        this.element.addEventListener("click", () => {
            el.focus();
        });

        // Init
        this._toggleActive(el);
        this._autogrow(el);

        // Events
        el.addEventListener("focusin", () => this._activate(el));
        el.addEventListener("input", () => {
            this._autogrow(el);
            this._toggleActive(el);
        });
        el.addEventListener("blur", () => this._toggleActive(el));
    }


    _activate(el) {
        el.classList.add("is-active");
    }

    _toggleActive(el) {
        const hasValue = (el.value || "").trim().length > 0;
        const isFocused = document.activeElement === el;
        el.classList.toggle("is-active", hasValue || isFocused);
    }

    _autogrow(el) {
        if (!el || el.tagName !== "TEXTAREA") return;
        el.style.height = "auto";
        el.style.height = el.scrollHeight + "px";
    }
}

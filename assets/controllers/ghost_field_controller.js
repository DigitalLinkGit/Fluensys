// No French in code comments per project convention
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["input"];

    initialize() {
        // Keep per-element listeners to properly clean them up
        this._handlers = new WeakMap();
        this._onContainerClick = (e) => {
            const el = e.target.closest('[data-ghost-field-target="input"]');
            if (el && this.element.contains(el)) {
                el.focus();
            }
        };
    }

    connect() {
        // Delegate clicks to focus inputs when clicking their wrapper
        this.element.addEventListener("click", this._onContainerClick);
        // Setup existing targets
        this.inputTargets.forEach((el) => this._setupInput(el));
    }

    disconnect() {
        this.element.removeEventListener("click", this._onContainerClick);
        // Clean up all known inputs
        this.inputTargets.forEach((el) => this._teardownInput(el));
    }

    inputTargetConnected(el) {
        this._setupInput(el);
    }

    inputTargetDisconnected(el) {
        this._teardownInput(el);
    }

    _setupInput(el) {
        if (!el) return;
        // Avoid double-binding
        if (this._handlers.has(el)) return;

        // Initial state
        this._toggleActive(el);
        this._autogrow(el);

        const onFocusIn = () => this._activate(el);
        const onInput = () => {
            this._autogrow(el);
            this._toggleActive(el);
        };
        const onBlur = () => this._toggleActive(el);

        el.addEventListener("focusin", onFocusIn);
        el.addEventListener("input", onInput);
        el.addEventListener("blur", onBlur);

        this._handlers.set(el, { onFocusIn, onInput, onBlur });
    }

    _teardownInput(el) {
        const h = this._handlers.get(el);
        if (!h) return;
        el.removeEventListener("focusin", h.onFocusIn);
        el.removeEventListener("input", h.onInput);
        el.removeEventListener("blur", h.onBlur);
        this._handlers.delete(el);
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

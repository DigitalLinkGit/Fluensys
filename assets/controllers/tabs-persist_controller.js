// assets/controllers/tabs-persist_controller.js
import { Controller } from "@hotwired/stimulus";

/*
 * Persists the active Bootstrap tab across page reloads.
 * - By default: uses sessionStorage (no URL change).
 * - Optionally: can use the URL hash.
 */
export default class extends Controller {
    static values = {
        storageKey: { type: String, default: "activeTab" },
        defaultTab: { type: String, default: "" }, // e.g. "#capture-elements"
        mode: { type: String, default: "storage" } // "storage" | "hash"
    };

    connect() {
        this._onShown = this._onShown.bind(this);

        this._triggers = Array.from(
            this.element.querySelectorAll('[data-bs-toggle="tab"]')
        );

        this._triggers.forEach((el) => {
            el.addEventListener("shown.bs.tab", this._onShown);
        });

        this.restore();
    }

    disconnect() {
        if (!this._triggers) return;
        this._triggers.forEach((el) => {
            el.removeEventListener("shown.bs.tab", this._onShown);
        });
    }

    restore() {
        const selector = this._getPersistedSelector() || this.defaultTabValue;

        if (!selector) return;

        const trigger = this.element.querySelector(
            `[data-bs-toggle="tab"][href="${selector}"]`
        );

        if (!trigger) return;
        if (!window.bootstrap?.Tab) return;

        new bootstrap.Tab(trigger).show();
    }

    _onShown(event) {
        const selector = this._getSelectorFromTrigger(event.target);
        if (!selector) return;

        if (this.modeValue === "hash") {
            history.replaceState(null, "", selector);
            return;
        }

        sessionStorage.setItem(this._storageKey(), selector);
    }

    _getPersistedSelector() {
        if (this.modeValue === "hash") {
            return window.location.hash || "";
        }

        return sessionStorage.getItem(this._storageKey()) || "";
    }

    _storageKey() {
        return `${this.storageKeyValue}:${window.location.pathname}`;
    }

    _getSelectorFromTrigger(triggerEl) {
        const hrefAttr = triggerEl.getAttribute("href");
        if (hrefAttr && hrefAttr.startsWith("#")) return hrefAttr;

        try {
            const url = new URL(triggerEl.href);
            return url.hash || "";
        } catch (e) {
            return "";
        }
    }
}

import { Controller } from "@hotwired/stimulus";

/**
 * Maintains a per-tab stack of distinct paths and navigates back to the last different one.
 */
export default class extends Controller {
    static values = { fallbackUrl: String };

    connect() {
        // Prevent multiple pushes if several buttons are on the page
        if (window.__navStackPushed) return;
        window.__navStackPushed = true;

        const key = "navStack";
        const current = location.pathname + location.search;
        const stack = JSON.parse(sessionStorage.getItem(key) || "[]");

        if (stack.length === 0 || stack[stack.length - 1] !== current) {
            stack.push(current);
            sessionStorage.setItem(key, JSON.stringify(stack));
        }
    }

    go(event) {
        event.preventDefault();

        const key = "navStack";
        const stack = JSON.parse(sessionStorage.getItem(key) || "[]");

        // Remove current page
        if (stack.length) stack.pop();

        // Get last different page
        const target = stack.length ? stack.pop() : null;

        // Persist updated stack
        sessionStorage.setItem(key, JSON.stringify(stack));

        if (target) {
            location.assign(target);
            return;
        }

        // Same-origin referrer fallback
        try {
            if (document.referrer) {
                const ref = new URL(document.referrer);
                const here = location.pathname + location.search;
                if (ref.origin === location.origin && (ref.pathname + ref.search) !== here) {
                    location.assign(ref.pathname + ref.search);
                    return;
                }
            }
        } catch (_) {}

        // Final fallback
        location.assign(this.fallbackUrlValue || "/");
    }
}

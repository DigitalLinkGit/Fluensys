// controllers/sortable_order_controller.js
import { Controller } from "@hotwired/stimulus";
import Sortable from "sortablejs";

export default class extends Controller {
    static targets = ["list", "input"];

    connect() {
        if (!this.hasListTarget) return;

        console.log("[sortable-order] connect", {
            list: this.listTarget,
            items: this.listTarget.querySelectorAll("[data-sortable-id]").length,
            hasCreate: typeof Sortable?.create === "function",
            sortableType: typeof Sortable,
        });

        this.sortable = Sortable.create(this.listTarget, {
            animation: 150,
            handle: "[data-sortable-handle]",
            draggable: "[data-sortable-id]",
            ghostClass: "sortable-ghost",

            onStart: () => console.log("[Sortable] onStart"),
            onMove: () => console.log("[Sortable] onMove"),
            onEnd: () => console.log("[Sortable] onEnd"),
            onSort: () => {
                console.log("[Sortable] onSort");
                this.sync();
            },
            onChange: () => console.log("[Sortable] onChange"),
            onChoose: () => console.log("[Sortable] onChoose"),
            onUnchoose: () => console.log("[Sortable] onUnchoose"),
        });

        this.sync();
        this.toggleEmpty();
    }

    disconnect() {
        if (this.sortable) this.sortable.destroy();
    }

    sync() {
        if (!this.hasInputTarget) return;

        const ids = Array.from(this.listTarget.querySelectorAll("[data-sortable-id]"))
            .map((el) => el.getAttribute("data-sortable-id"))
            .filter(Boolean);

        this.inputTarget.value = JSON.stringify(ids);
        console.log("[sortable-order] sync", ids);

        // Notify other controllers (e.g., fields-ajax) so they can persist order via AJAX
        const evt = new CustomEvent('sortable:sync', {
            bubbles: true,
            detail: { ids }
        });
        this.listTarget.dispatchEvent(evt);
    }

    toggleEmpty() {
        const hasItems = this.listTarget.querySelector("[data-sortable-id]") !== null;
        this.listTarget.classList.toggle("is-empty", !hasItems);
    }
}

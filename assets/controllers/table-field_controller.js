import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["container"];

    connect() {
        if (!this.element.dataset.index) {
            this.element.dataset.index = String(
                this.element.querySelectorAll("[data-table-field-item]").length
            );
        }
    }

    add(e) {
        e.preventDefault();

        const proto = this.element.dataset.prototype;
        if (!proto) return;

        const name = this.element.dataset.prototypeName || "__name__";
        const index = parseInt(this.element.dataset.index || "0", 10);
        const html = proto.replaceAll(name, String(index));

        // CONTRIBUTOR MODE (table) => DO NOT TOUCH
        const tbody = this.element.querySelector(
            'tbody[data-table-field-target="container"]'
        );

        if (tbody) {
            const tr = document.createElement("tr");
            tr.setAttribute("data-table-field-item", "1");

            const tmp = document.createElement("div");
            tmp.innerHTML = html;

            tmp.querySelectorAll(".mb-3").forEach((group) => {
                const td = document.createElement("td");
                const control = group.querySelector("input, select, textarea");
                if (control) td.appendChild(control);
                tr.appendChild(td);
            });

            const actions = document.createElement("td");
            actions.className = "text-end";

            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "btn btn-outline-danger btn-sm";
            btn.setAttribute("data-action", "click->table-field#remove");
            btn.innerHTML = `<i class="bi bi-trash"></i>`;

            actions.appendChild(btn);
            tr.appendChild(actions);

            tbody.appendChild(tr);
            this.element.dataset.index = String(index + 1);
            return;
        }

        // TEMPLATE MODE (div) => insert prototype as-is (no extra wrapper)
        const htmlRow = html; // html already contains the full row div

        const container = this.hasContainerTarget ? this.containerTarget : this.element;
        container.insertAdjacentHTML("beforeend", htmlRow);

        this.element.dataset.index = String(index + 1);

    }


    remove(e) {
        e.preventDefault();

        const tr = e.currentTarget.closest("tr[data-table-field-item]");
        if (tr) {
            tr.remove();
            return;
        }

        const item = e.currentTarget.closest("[data-table-field-item]");
        if (item) item.remove();
    }
}

import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static values = {
        index: Number,
    };

    static targets = ["container", "prototype"];

    add(event) {
        event?.preventDefault();

        const html = this.prototypeTarget.innerHTML.replace(/__name__/g, String(this.indexValue));
        this.indexValue += 1;

        const wrapper = document.createElement("div");
        wrapper.setAttribute("data-listable-field-item", "1");
        wrapper.className = "mb-2 d-flex align-items-start gap-2";
        wrapper.innerHTML = `<div class="flex-grow-1">${html}</div>`;

        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "btn btn-outline-danger btn-sm";
        btn.setAttribute("data-action", "listable-field#remove");
        btn.setAttribute("title", "Supprimer");
        btn.setAttribute("aria-label", "Supprimer");
        btn.innerHTML = `<i class="bi bi-trash"></i>`;

        wrapper.appendChild(btn);
        this.containerTarget.appendChild(wrapper);
    }


    remove(event) {
        event?.preventDefault();
        const item = event.currentTarget.closest('[data-listable-field-item="1"]');
        if (item) {
            item.remove();
        }
    }
}

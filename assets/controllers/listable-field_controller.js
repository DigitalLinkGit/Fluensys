// assets/controllers/listable-field_controller.js
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["prototype", "container"];
    static values = {
        index: Number,
    };

    add() {
        const prototypeHtml = this.prototypeTarget.innerHTML.trim();
        if (!prototypeHtml) return;

        const index = Number.isFinite(this.indexValue) ? this.indexValue : this.containerTarget.children.length;

        // Replace Symfony collection placeholder
        const newFieldHtml = prototypeHtml.replace(/__name__/g, String(index));

        // Wrap exactly like existing items (so remove works the same)
        const wrapper = document.createElement("div");
        wrapper.setAttribute("data-listable-field-item", "1");
        wrapper.className = "mb-2 d-flex align-items-center gap-2";
        wrapper.innerHTML = `
      <div class="flex-grow-1">
        ${newFieldHtml}
      </div>
      <button type="button"
              class="btn btn-outline-danger btn-sm mb-3"
              title="Supprimer"
              aria-label="Supprimer"
              data-action="listable-field#remove">
        <i class="bi bi-trash"></i>
      </button>
    `;

        this.containerTarget.appendChild(wrapper);
        this.indexValue = index + 1;
    }

    remove(event) {
        const item = event.target.closest('[data-listable-field-item="1"]');
        if (item) item.remove();
    }
}

import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["list", "add"];
    static values = { fieldsUrl: String };

    connect() {
        if (!this.index) {
            this.index = this.element.querySelectorAll("fieldset").length;
        }
    }

    add(event) {
        if (!event || !event.isTrusted) return;
        event.preventDefault();

        const html = this.element.dataset.prototype.replace(/__name__/g, this.index);
        const tpl = document.createElement("template");
        tpl.innerHTML = html.trim();
        const entry = tpl.content.firstElementChild;

        entry.setAttribute("data-condition-entry", "");
        this.resetFieldsSelect(entry);

        this.listTarget.appendChild(entry);
        this.index++;
    }

    remove(event) {
        event.preventDefault();
        const fs = event.target.closest("fieldset");
        if (fs) fs.remove();
    }

    onTargetChange(event) {
        if (!event || !event.isTrusted) return;

        const targetSelect = event.target;
        const entry = this.entryOf(targetSelect);
        const sourceSelect = this.qs(entry, 'select[name$="[sourceElement]"]');
        if (!sourceSelect) return;

        const targetValue = targetSelect.value;
        const currentSource = sourceSelect.value;

        Array.from(sourceSelect.options).forEach((opt) => {
            opt.disabled = (targetValue && opt.value === targetValue && opt.value !== currentSource);
        });
    }



    async onSourceChange(event) {
        if (!event || !event.isTrusted) return;

        const sourceSelect = event.target;
        const entry = this.entryOf(sourceSelect);
        const fieldSelect = this.qs(entry, 'select[name$="[sourceField]"]');
        if (!fieldSelect) return;

        const sourceId = sourceSelect.value;
        if (!sourceId || !this.hasFieldsUrlValue || !this.fieldsUrlValue) {
            this.resetFieldsSelect(entry);
            return;
        }

        const previous = fieldSelect.value;

        try {
            const url = new URL(this.fieldsUrlValue, window.location.origin);
            url.searchParams.set("sourceElement", sourceId);

            const res = await fetch(url.toString(), { headers: { Accept: "application/json" } });
            const data = await res.json();

            fieldSelect.innerHTML = '<option value="">Sélectionner un champ...</option>';
            (data.fields || []).forEach((f) => {
                const opt = document.createElement("option");
                opt.value = String(f.id);
                opt.textContent = f.label;
                fieldSelect.appendChild(opt);
            });

            if (previous && Array.from(fieldSelect.options).some(o => o.value === previous)) {
                fieldSelect.value = previous;
            }

            fieldSelect.disabled = (data.fields || []).length === 0;
            fieldSelect.required = true;
        } catch {
            this.resetFieldsSelect(entry);
        }
    }


    entryOf(el) {
        return el.closest("[data-condition-entry]") || el.closest("fieldset") || this.element;
    }
    qs(root, selector) {
        return root ? root.querySelector(selector) : null;
    }
    resetFieldsSelect(entry) {
        const fieldSelect = this.qs(entry, 'select[name$="[sourceField]"]');
        if (!fieldSelect) return;
        fieldSelect.innerHTML = '<option value="">Sélectionner un champ...</option>';
        fieldSelect.disabled = true;
        fieldSelect.required = true;
    }
}

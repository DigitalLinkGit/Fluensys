import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        // cache container (node holding data-prototype) and init index once
        this.container = this.element.querySelector("[data-prototype]") || this.element;
        this.index = Number(this.container.dataset.index || this.container.querySelectorAll("fieldset").length || 0);
        this.draggedType = null;

        // init existing fieldsets (badge + subtype UI + collapsed)
        this.container.querySelectorAll("fieldset").forEach((fs) => {
            const typeVal = this.getTypeInput(fs)?.value || "";
            this.updateTypeBadge(fs, typeVal);
            this.renderSubtypeUI(fs, typeVal);
            this.setCollapsed(fs, true);
        });
    }

    // ---------- DnD ----------
    dragStart(event) {
        const type = event.currentTarget.dataset.type;
        this.draggedType = type;
        if (!event.dataTransfer) return;
        event.dataTransfer.setData("application/x-field-type", type);
        try {
            event.dataTransfer.setData("text/plain", type);
            event.dataTransfer.setData("text", type);
        } catch (_) {}
        event.dataTransfer.effectAllowed = "copy";
    }

    dragEnter(event) {
        event.preventDefault();
        if (event.dataTransfer) event.dataTransfer.dropEffect = "copy";
    }

    dragOver(event) {
        event.preventDefault();
        if (event.dataTransfer) event.dataTransfer.dropEffect = "copy";
    }

    drop(event) {
        event.preventDefault();
        let type = "";
        if (event.dataTransfer) {
            type =
                event.dataTransfer.getData("application/x-field-type") ||
                event.dataTransfer.getData("text/plain") ||
                event.dataTransfer.getData("text") ||
                "";
        }
        if (!type && this.draggedType) type = this.draggedType;
        if (!type) return console.warn("Aucun type trouvé pour le drop");
        this.addFieldOfType(type);
        this.draggedType = null;
    }

    // ---------- Create item ----------
    addFieldOfType(type) {
        const proto = this.container.dataset.prototype;
        if (!proto) return console.error("data-prototype manquant sur le conteneur");

        const html = proto.replace(/__name__/g, String(this.index));
        const wrapper = document.createElement("div");
        wrapper.innerHTML = html.trim();

        let fs = wrapper.firstElementChild;
        if (!fs || fs.tagName.toLowerCase() !== "fieldset") fs = wrapper.querySelector("fieldset");
        if (!fs) return console.error("Prototype ne contient pas de fieldset");

        // set hidden/select [type] and trigger change so Symfony/Stimulus listeners react
        const typeInput = this.getTypeInput(fs);
        if (typeInput) {
            typeInput.value = type;
            typeInput.dispatchEvent(new Event("change", { bubbles: true }));
        }

        // update badge + client-side subtype UI (if server hasn’t rendered it yet)
        this.updateTypeBadge(fs, type);
        this.renderSubtypeUI(fs, type);

        fs.setAttribute("data-collection-item", "");
        this.container.appendChild(fs);
        this.setCollapsed(fs, true);

        this.index++;
        this.container.dataset.index = String(this.index); // persist for next connects
    }

    // ---------- Events ----------
    typeChanged(event) {
        const fs = event.currentTarget.closest("fieldset");
        const type = event.currentTarget.value;
        this.updateTypeBadge(fs, type);
        this.renderSubtypeUI(fs, type);
    }

    // ---------- Subtype UI ----------
    renderSubtypeUI(fieldset, type) {
        const host = fieldset.querySelector(".subtype-config");
        if (!host) return;

        // do not overwrite server-rendered content (editing case)
        if (host.querySelector("textarea, input, select, [data-sf-form]")) return;

        host.innerHTML = "";
        const tpl = fieldset.querySelector(`script[data-subtype-template="${type}"]`);
        if (tpl) host.innerHTML = tpl.innerHTML;
    }

    // ---------- UI helpers ----------
    updateTypeBadge(fieldset, type) {
        const badge = fieldset.querySelector(".type-badge");
        if (!badge) return;
        const labels = {
            textarea: "texte long",
            text: "texte court",
            integer: "nombre entier",
            decimal: "nombre décimal",
            date: "date",
            checklist: "cases à cocher",
            system_component_collection: "composants de SI",
        };
        badge.textContent = `${labels[type] || type || "—"}`;
    }

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

    remove(event) {
        event.preventDefault();
        const fs = (event.currentTarget || event.target).closest("fieldset");
        if (fs) fs.remove();
    }

    // ---------- utils ----------
    getTypeInput(scope) {
        return scope.querySelector("select[name$='[type]'], input[name$='[type]']");
    }
}

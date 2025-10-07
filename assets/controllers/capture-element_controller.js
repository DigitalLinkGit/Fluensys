import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        // cache container (node holding data-prototype) and init index once
        this.container = this.element.querySelector("[data-prototype]") || this.element;
        this.index = Number(this.container.dataset.index || this.container.querySelectorAll("fieldset").length || 0);
        this.draggedType = null;
        this.draggedLabel = null;

        // init existing fieldsets (badge + subtype UI + collapsed)
        this.container.querySelectorAll("fieldset").forEach((fs) => {
            const typeVal = this.getTypeInput(fs)?.value || "";
            const labelVal = fs.dataset.typeLabel || this.readBadge(fs) || "";
            this.updateTypeBadge(fs, labelVal || typeVal);
            this.renderSubtypeUI(fs, typeVal);
            this.setCollapsed(fs, true);
        });
    }

    // ---------- Drag & Drop ----------
    dragStart(event) {
        const el = event.currentTarget;
        const type = el.dataset.type;
        const label = el.dataset.label || (el.textContent || "").trim();

        this.draggedType = type;
        this.draggedLabel = label;

        if (!event.dataTransfer) return;
        event.dataTransfer.setData("application/x-field-type", type);
        event.dataTransfer.setData("application/x-field-label", label);
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
        let label = "";

        if (event.dataTransfer) {
            type =
                event.dataTransfer.getData("application/x-field-type") ||
                event.dataTransfer.getData("text/plain") ||
                event.dataTransfer.getData("text") ||
                "";
            label = event.dataTransfer.getData("application/x-field-label") || "";
        }

        if (!type && this.draggedType) type = this.draggedType;
        if (!label && this.draggedLabel) label = this.draggedLabel;
        if (!type) return console.warn("No type found on drop");

        this.addFieldOfType(type, label);
        this.draggedType = null;
        this.draggedLabel = null;
    }

    // ---------- Create item ----------
    addFieldOfType(type, label) {
        const proto = this.container.dataset.prototype;
        if (!proto) return console.error("Missing data-prototype on container");

        const html = proto.replace(/__name__/g, String(this.index));
        const wrapper = document.createElement("div");
        wrapper.innerHTML = html.trim();

        let fs = wrapper.firstElementChild;
        if (!fs || fs.tagName.toLowerCase() !== "fieldset") fs = wrapper.querySelector("fieldset");
        if (!fs) return console.error("Prototype does not contain a fieldset");

        // set hidden/select [type] and trigger change so Symfony/Stimulus listeners react
        const typeInput = this.getTypeInput(fs);
        if (typeInput) {
            typeInput.value = type;
            typeInput.dispatchEvent(new Event("change", { bubbles: true }));
        }

        // store label for future connects/edits and update badge
        if (label) fs.dataset.typeLabel = label;
        this.updateTypeBadge(fs, label || type);

        // client-side subtype UI (if server hasn’t rendered it yet)
        this.renderSubtypeUI(fs, type);

        fs.setAttribute("data-collection-item", "");
        this.container.appendChild(fs);
        this.setCollapsed(fs, true);

        this.index++;
        this.container.dataset.index = String(this.index);
    }

    // ---------- Events ----------
    typeChanged(event) {
        const fs = event.currentTarget.closest("fieldset");
        const type = event.currentTarget.value;
        // do not compute label client-side; keep existing badge unless fieldset carries a typeLabel
        const label = fs?.dataset?.typeLabel || this.readBadge(fs) || type;
        this.updateTypeBadge(fs, label);
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
    updateTypeBadge(fieldset, labelOrType) {
        const badge = fieldset.querySelector(".type-badge");
        if (!badge) return;
        badge.textContent = `${labelOrType || "—"}`;
    }

    readBadge(fieldset) {
        const badge = fieldset.querySelector(".type-badge");
        return (badge && badge.textContent ? badge.textContent.trim() : "") || "";
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

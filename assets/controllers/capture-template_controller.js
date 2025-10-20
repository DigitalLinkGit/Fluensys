import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["collection"];

    connect() {
        this.container = this.hasCollectionTarget ? this.collectionTarget : this.element;
        this.index = this.container.querySelectorAll("fieldset").length;
        this.libraryItems = Array.from(this.element.querySelectorAll('button[data-action*="capture-template#add"]'))
            .map(btn => btn.closest("[data-id]"))
            .filter(Boolean);


        console.log("DEBUG -> libraryItems:", this.libraryItems.length);
        console.log("DEBUG -> existing ids:", this.getExistingIds());

        this.syncLibrary();

    }

    syncLibrary() {
        // Collect all existing element IDs already present in the collection
        const existingIds = Array.from(this.container.querySelectorAll('input[name$="[id]"]')).map(i => i.value);


        this.libraryItems.forEach(item => {
            const id = item.dataset.id;
            item.hidden = existingIds.includes(id);

        });
    }

    add(event) {
        event.preventDefault();

        const item = event.currentTarget.closest("[data-id]");
        const id = item?.dataset.id || "";
        const label = item?.dataset.label || "";
        const description = item?.dataset.description || "";

        const prototype = this.element.dataset.prototype;
        if (!prototype) return;

        const html = prototype.replace(/__name__/g, this.index);
        const temp = document.createElement("div");
        temp.innerHTML = html;
        const fieldset = temp.firstElementChild;

        // Optionally set a hidden input for ID if your prototype includes one
        const idInput = fieldset.querySelector('input[name$="[id]"]');
        if (idInput) idInput.value = id;

        const nameInput = fieldset.querySelector('input[name$="[name]"]');
        if (nameInput) nameInput.value = label;

        const descInput = fieldset.querySelector('textarea[name$="[description]"]');
        if (descInput) descInput.value = description;

        const addLink = this.container.querySelector("a.btn");
        if (!addLink) return;
        this.container.insertBefore(fieldset, addLink);

        this.container.insertBefore(fieldset, addLink);
        this.index++;

        this.syncLibrary();
    }

    remove(event) {
        event.preventDefault();

        const fieldset = event.target.closest("fieldset");
        if (!fieldset) return;

        // Retrieve id (preferred) or fallback to name
        const idInput = fieldset.querySelector('input[name$="[id]"]');
        const id = idInput?.value?.trim();

        fieldset.remove();

        this.libraryItems.forEach(item => {
            if (item.dataset.id === id) item.style.display = "";
        });

        this.syncLibrary();
    }

    getExistingIds() {
        return Array.from(this.container.querySelectorAll("fieldset"))
            .map(fs => {
                const hidden = fs.querySelector('input[name$="[id]"]');
                return (hidden?.value?.trim() || fs.dataset.id || "").toString();
            })
            .filter(Boolean);
    }

}

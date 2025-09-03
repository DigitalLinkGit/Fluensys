import { Controller } from "@hotwired/stimulus";

export default class extends Controller {

    connect() {
        // container = l'élément qui porte data-prototype (la collection)
        this.container = this.element.querySelector('[data-prototype]') || this.element;
        if (!this.index) {
            this.index = this.container.querySelectorAll('fieldset').length;
        }
        this.draggedType = null;
        // Met à jour les badges et hints pour les champs déjà présents
        this.container.querySelectorAll('fieldset').forEach((fs) => {
            const typeInput = fs.querySelector("select[name$='[type]'], input[name$='[type]']");
            const typeVal = typeInput ? typeInput.value : '';
            this.updateTypeBadge(fs, typeVal);
            this.toggleOptionsHint(fs);
        });
    }

    // Drag sources
    dragStart(event) {
        const type = event.currentTarget.dataset.type;
        this.draggedType = type;
        // Set custom and standard MIME types for broader browser support
        if (event.dataTransfer) {
            event.dataTransfer.setData('application/x-field-type', type);
            try {
                event.dataTransfer.setData('text/plain', type);
                event.dataTransfer.setData('text', type);
            } catch (e) {
                // some browsers may restrict setData for non-text
            }
            event.dataTransfer.effectAllowed = 'copy';
        }
    }

    // Dropzone handlers on the collection container
    dragEnter(event) {
        // Needed for some browsers to allow dropping
        event.preventDefault();
        if (event.dataTransfer) {
            event.dataTransfer.dropEffect = 'copy';
        }
    }

    dragOver(event) {
        event.preventDefault();
        if (event.dataTransfer) {
            event.dataTransfer.dropEffect = 'copy';
        }
    }

    drop(event) {
        event.preventDefault();
        let type = '';
        if (event.dataTransfer) {
            type = event.dataTransfer.getData('application/x-field-type')
                || event.dataTransfer.getData('text/plain')
                || event.dataTransfer.getData('text')
                || '';
        }
        if (!type && this.draggedType) {
            type = this.draggedType;
        }
        if (!type) {
            console.warn('Aucun type trouvé pour le drop');
            return;
        }
        this.addFieldOfType(type, event);
        this.draggedType = null;
    }

    addFieldOfType(type, event) {
        const container = this.container || this.element;
        const prototype = container.dataset.prototype;
        if (!prototype) {
            console.error('data-prototype manquant sur le conteneur');
            return;
        }
        const html = prototype.replace(/__name__/g, this.index);
        const temp = document.createElement('div');
        temp.innerHTML = html.trim();
        let fieldset = temp.firstElementChild;
        if (!fieldset || fieldset.tagName.toLowerCase() !== 'fieldset') {
            fieldset = temp.querySelector('fieldset');
        }
        if (!fieldset) {
            console.error('Prototype ne contient pas de fieldset');
            return;
        }

        // Set the [name$='[type]'] (hidden or select) to desired type
        const typeInput = fieldset.querySelector("select[name$='[type]'], input[name$='[type]']");
        if (typeInput) {
            typeInput.value = type;
            typeInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        // Update badge label
        this.updateTypeBadge(fieldset, type);

        // Toggle options hint if checklist
        this.toggleOptionsHint(fieldset);

        // Insert before dropzone if available, else append
        const dropzone = container.querySelector('.dropzone');
        if (dropzone) {
            container.insertBefore(fieldset, dropzone);
        } else {
            container.appendChild(fieldset);
        }

        this.index++;
    }

    typeChanged(event) {
        const fieldset = event.currentTarget.closest('fieldset');
        this.toggleOptionsHint(fieldset);
    }

    toggleOptionsHint(fieldset) {
        const typeInput = fieldset.querySelector("select[name$='[type]'], input[name$='[type]']");
        const hint = fieldset.querySelector('.checklist-options-hint');
        if (!hint || !typeInput) return;
        if (typeInput.value === 'checklist') {
            hint.classList.remove('d-none');
        } else {
            hint.classList.add('d-none');
        }
    }

    updateTypeBadge(fieldset, type) {
        const badge = fieldset.querySelector('.type-badge');
        if (!badge) return;
        const labels = {
            textarea: 'texte long',
            text: 'texte court',
            integer: 'nombre entier',
            decimal: 'nombre décimal',
            date: 'date',
            checklist: 'cases à cocher'
        };
        const label = labels[type] || type || '—';
        badge.textContent = `Type: ${label}`;
    }

    remove(event) {
        event.preventDefault();
        const btn = event.currentTarget || event.target;
        const fieldset = btn.closest('fieldset');
        if (fieldset) {
            fieldset.remove();
        }
    }
}

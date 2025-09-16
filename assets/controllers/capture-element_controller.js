import { Controller } from "@hotwired/stimulus";

export default class extends Controller {

    connect() {
        this.container = this.element.querySelector('[data-prototype]') || this.element;
        if (!this.index) {
            this.index = this.container.querySelectorAll('fieldset').length;
        }
        this.draggedType = null;
        this.container.querySelectorAll('fieldset').forEach((fs) => {
            const typeInput = fs.querySelector("select[name$='[type]'], input[name$='[type]']");
            const typeVal = typeInput ? typeInput.value : '';
            this.updateTypeBadge(fs, typeVal);
            this.renderSubtypeUI(fs, typeVal);
            this.setCollapsed(fs, true);
        });
    }

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

        // Render subtype UI (client-side template) if needed
        this.renderSubtypeUI(fieldset, type);

        container.appendChild(fieldset);
        this.setCollapsed(fieldset, true);
        this.index++;
    }

    typeChanged(event) {
        const fieldset = event.currentTarget.closest('fieldset');
        const type = event.currentTarget.value;
        this.renderSubtypeUI(fieldset, type);
    }

    renderSubtypeUI(fieldset, type) {
        const container = fieldset.querySelector('.subtype-config');
        if (!container) return;

        // If backend already rendered a subtype form, do nothing
        if (container.querySelector('textarea, input, select, [data-sf-form]')) {
            // Heuristic: if subtype was rendered server side (editing existing), don't overwrite
            return;
        }

        // Clean previous client-side content
        container.innerHTML = '';

        // Insert matching client-side template if any
        const tpl = fieldset.querySelector(`script[data-subtype-template="${type}"]`);
        if (tpl) {
            container.innerHTML = tpl.innerHTML;
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

    toggle(event) {
        const btn = event.currentTarget;
        const fieldset = btn.closest('fieldset');
        if (!fieldset) return;
        const collapsed = fieldset.classList.contains('is-collapsed');
        this.setCollapsed(fieldset, !collapsed);
    }

    setCollapsed(fieldset, collapsed) {
        const body = fieldset.querySelector('.field-card__body');
        const icon = fieldset.querySelector('.field-card__toggle i');
        if (!body) return;
        if (collapsed) {
            fieldset.classList.add('is-collapsed');
            body.style.display = 'none';
            if (icon) { icon.classList.remove('bi-chevron-up'); icon.classList.add('bi-chevron-down'); }
        } else {
            fieldset.classList.remove('is-collapsed');
            body.style.display = '';
            if (icon) { icon.classList.remove('bi-chevron-down'); icon.classList.add('bi-chevron-up'); }
        }
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

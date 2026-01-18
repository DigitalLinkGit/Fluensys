// assets/controllers/image_field_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['preview'];

    connect() {
        // Apply size on load when an existing image is already displayed
        if (this.previewTarget && this.previewTarget.getAttribute('src')) {
            this.previewTarget.style.display = 'block';
            this.applySize();
        }
    }

    preview(event) {
        const input = event.target;
        const file = input.files && input.files[0] ? input.files[0] : null;

        if (!file) {
            this.previewTarget.removeAttribute('src');
            this.previewTarget.style.display = 'none';
            return;
        }

        const url = URL.createObjectURL(file);
        this.previewTarget.src = url;
        this.previewTarget.style.display = 'block';

        this.applySize();
    }

    resize() {
        this.applySize();
    }

    applySize() {
        // Find the select inside this controller scope
        const select = this.element.querySelector('select');
        const mode = select ? select.value : 'medium';

        if (mode === 'small') {
            this.previewTarget.style.width = '25%';
        } else if (mode === 'large') {
            this.previewTarget.style.width = '100%';
        } else {
            this.previewTarget.style.width = '50%';
        }

        this.previewTarget.style.maxWidth = '100%';
        this.previewTarget.style.height = 'auto';
    }
}

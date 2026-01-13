// assets/controllers/document_preview_modal_controller.js
import { Controller } from '@hotwired/stimulus'

/**
 * Usage:
 * - Put data-controller on the modal root
 * - Provide an iframe target
 * - Provide a urlTemplate value containing "__ID__"
 *
 * This controller:
 * - On modal open: reads data-capture-id from the triggering button and sets iframe src
 * - On modal close: clears iframe src
 */
export default class extends Controller {
    static targets = ['iframe']
    static values = {
        urlTemplate: String, // e.g. "/capture/__ID__/render-preview"
    }

    connect() {
        this._onShow = this.onShow.bind(this)
        this._onHidden = this.onHidden.bind(this)

        this.element.addEventListener('show.bs.modal', this._onShow)
        this.element.addEventListener('hidden.bs.modal', this._onHidden)
    }

    disconnect() {
        this.element.removeEventListener('show.bs.modal', this._onShow)
        this.element.removeEventListener('hidden.bs.modal', this._onHidden)
    }

    onShow(event) {
        const trigger = event.relatedTarget
        if (!trigger) return

        const captureId = trigger.getAttribute('data-capture-id')
        if (!captureId) return

        const url = this.urlTemplateValue.replace('__ID__', captureId)
        this.iframeTarget.src = url
    }

    onHidden() {
        // Stop rendering/loading when modal closes
        this.iframeTarget.removeAttribute('src')
    }
}

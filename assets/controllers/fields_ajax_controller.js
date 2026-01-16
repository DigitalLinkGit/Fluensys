// assets/controllers/fields_ajax_controller.js
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = {
    addUrl: String,
    reorderUrl: String,
  }

  static targets = ["list"]

  connect() {
    // Listen to sortable sync events to persist order
    this.onSortableSync = (e) => {
      const ids = e.detail?.ids || [];
      if (!this.hasReorderUrlValue || !Array.isArray(ids) || ids.length === 0) return;
      fetch(this.reorderUrlValue, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order: ids.map((id) => parseInt(id, 10)) }),
      }).catch((err) => console.error('[fields-ajax] reorder failed', err));
    };
    this.element.addEventListener('sortable:sync', this.onSortableSync);
  }

  disconnect() {
    this.element.removeEventListener('sortable:sync', this.onSortableSync);
  }

  // Triggered by the select+button UI
  addFromSelect(e) {
    const select = this.element.querySelector('[data-capture-element-target="typeSelect"]');
    if (!select) return;
    const type = select.value;
    if (!type) return;
    this.add(type);
  }

  // Triggered by specific buttons with data-field-type
  add(type) {
    if (!this.hasAddUrlValue) return console.error('[fields-ajax] missing addUrl');
    const body = new URLSearchParams({ type: String(type) });

    fetch(this.addUrlValue, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body,
    })
      .then((r) => r.json())
      .then((json) => {
        if (json.status !== 'ok') return this.error(json.message || 'Add failed');
        const tpl = document.createElement('template');
        tpl.innerHTML = (json.html || '').trim();
        const node = tpl.content.firstElementChild;
        if (!node) return;
        const list = this.hasListTarget ? this.listTarget : this.element.querySelector('.sortable-list');
        if (!list) return console.error('[fields-ajax] list container not found');
        list.appendChild(node);

        // Optional: scroll to new item
        try { node.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (_) {}
      })
      .catch((err) => this.error(err.message));
  }

  delete(event) {
    const btn = event.currentTarget;
    const item = btn.closest('[data-sortable-id]');
    if (!item) return;
    const url = item.dataset.deleteUrl;
    const token = item.dataset.deleteToken;
    if (!url || !token) return console.error('[fields-ajax] missing delete url/token');

    fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ _token: token }),
    })
      .then((r) => r.json())
      .then((json) => {
        if (json.status !== 'ok') return this.error(json.message || 'Delete failed');
        item.remove();
      })
      .catch((err) => this.error(err.message));
  }

  error(message) {
    console.error('[fields-ajax]', message);
  }
}

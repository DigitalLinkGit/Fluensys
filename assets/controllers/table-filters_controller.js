// assets/controllers/table-filters_controller.js
// Dedicated Stimulus controller to manage table filters and sorting
// Note: The table Twig already wires inline handlers calling global functions
// filterTable(this) and sortTable(tableId, columnIndex). We expose these on window
// so we don't need to change the Twig file.

import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        // Expose global functions once. If already defined, don't overwrite.
        if (!window.filterTable) {
            window.filterTable = (inputEl) => this.#filterTable(inputEl);
        }
        if (!window.sortTable) {
            window.sortTable = (tableId, columnIndex) => this.#sortTable(tableId, columnIndex);
        }
    }

    // Private: filter rows based on text input for specific column
    #filterTable(inputEl) {
        try {
            const tableId = inputEl?.dataset?.tableId;
            const colIndex = parseInt(inputEl?.dataset?.filterColumn ?? "-1", 10);
            if (!tableId || isNaN(colIndex) || colIndex < 0) return;

            const table = document.getElementById(tableId);
            if (!table) return;
            const tbody = table.tBodies[0];
            if (!tbody) return;

            const query = (inputEl.value || "").toString().trim().toLowerCase();

            // For simple performance, loop rows and check cell text
            Array.from(tbody.rows).forEach((row) => {
                const cell = row.cells[colIndex];
                if (!cell) return; // if structure mismatch, skip
                const text = (cell.innerText || cell.textContent || "").toLowerCase();
                const match = query === "" || text.includes(query);
                row.style.display = match ? "" : "none";
            });
        } catch (e) {
            // fail-safe: don't crash UI
            console.error("filterTable error:", e);
        }
    }

    // Private: sort rows for specified column and toggle asc/desc
    #sortTable(tableId, columnIndex) {
        try {
            const table = document.getElementById(tableId);
            if (!table) return;
            const tbody = table.tBodies[0];
            if (!tbody) return;

            const colIndex = parseInt(columnIndex, 10);
            if (isNaN(colIndex) || colIndex < 0) return;

            const currentCol = parseInt(table.dataset.sortColumn ?? "-1", 10);
            let direction = table.dataset.sortDirection || "asc";
            if (currentCol === colIndex) {
                direction = direction === "asc" ? "desc" : "asc";
            } else {
                direction = "asc";
            }
            table.dataset.sortColumn = String(colIndex);
            table.dataset.sortDirection = direction;

            const rows = Array.from(tbody.rows).filter(r => r.style.display !== "none");
            const hiddenRows = Array.from(tbody.rows).filter(r => r.style.display === "none");

            const collator = new Intl.Collator(undefined, { numeric: true, sensitivity: "base" });

            rows.sort((a, b) => {
                const aText = (a.cells[colIndex]?.innerText || a.cells[colIndex]?.textContent || "").trim();
                const bText = (b.cells[colIndex]?.innerText || b.cells[colIndex]?.textContent || "").trim();

                const cmp = collator.compare(aText, bText);
                return direction === "asc" ? cmp : -cmp;
            });

            // Re-append in sorted order (stable enough for our needs)
            rows.concat(hiddenRows).forEach(row => tbody.appendChild(row));

            // Optionally update icon if exists inside the sort button (bi-sort-alpha-down/up)
            this.#updateSortIcons(table, colIndex, direction);
        } catch (e) {
            console.error("sortTable error:", e);
        }
    }

    #updateSortIcons(table, colIndex, direction) {
        try {
            // Find the filter/sort row in the thead
            const thead = table.tHead;
            if (!thead) return;
            const rows = Array.from(thead.rows);
            const filterRow = rows.find(r => r.classList.contains("filter-row"));
            if (!filterRow) return;

            // Reset all icons
            filterRow.querySelectorAll("button i.bi").forEach(icon => {
                icon.classList.remove("bi-sort-alpha-up");
                icon.classList.add("bi-sort-alpha-down");
            });

            const td = filterRow.cells[colIndex];
            const btn = td ? td.querySelector("button i.bi") : null;
            if (!btn) return;
            if (direction === "asc") {
                btn.classList.remove("bi-sort-alpha-up");
                btn.classList.add("bi-sort-alpha-down");
            } else {
                btn.classList.remove("bi-sort-alpha-down");
                btn.classList.add("bi-sort-alpha-up");
            }
        } catch (_) {
            // ignore icon update failures
        }
    }
}

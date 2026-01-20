import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["filterPopover", "filterInput"];

    connect() {
        this.filters = {};
        this.sortState = { col: null, dir: "asc" };
    }

    toggleFilter(event) {
        const col = Number(event.params.column);
        const pop = this._findFilterPopover(col);
        if (!pop || Number.isNaN(col)) return;

        const willShow = pop.classList.contains("d-none");
        this._hideAllFilterPopovers();

        if (willShow) {
            pop.classList.remove("d-none");
            const input = this._findFilterInput(col);
            if (input) { input.focus(); input.select(); }
        } else {
            pop.classList.add("d-none");
            const input = this._findFilterInput(col);
            if (input) input.value = "";
            delete this.filters[col];
            this._applyFilters();
            this._updateFilterIcon(col);
        }
    }

    filter(event) {
        const input = event.target;
        const col = Number(input.dataset.tableFiltersColumn);
        const value = (input.value || "").trim().toLowerCase();

        if (value === "") delete this.filters[col];
        else this.filters[col] = value;

        this._applyFilters();
        this._updateFilterIcon(col);
    }

    sort(event) {
        const col = Number(event.params.column);

        if (this.sortState.col === col) {
            this.sortState.dir = this.sortState.dir === "asc" ? "desc" : "asc";
        } else {
            this.sortState.col = col;
            this.sortState.dir = "asc";
        }

        const tbody = this.element.querySelector("tbody");
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll("tr"));
        const dirFactor = this.sortState.dir === "asc" ? 1 : -1;

        rows.sort((a, b) => {
            const aText = (a.children[col]?.textContent || "").trim();
            const bText = (b.children[col]?.textContent || "").trim();
            return aText.localeCompare(bText, undefined, { numeric: true, sensitivity: "base" }) * dirFactor;
        });

        rows.forEach((r) => tbody.appendChild(r));
        this._updateSortIcons();
    }

    closeFilter(event) {
        const input = event.target;
        const col = Number(input.dataset.tableFiltersColumn);

        const pop = this._findFilterPopover(col);
        if (pop) pop.classList.add("d-none");
    }

    _applyFilters() {
        const tbody = this.element.querySelector("tbody");
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll("tr"));
        rows.forEach((row) => {
            let visible = true;
            for (const [colStr, needle] of Object.entries(this.filters)) {
                const col = Number(colStr);
                const haystack = (row.children[col]?.textContent || "").toLowerCase();
                if (!haystack.includes(needle)) { visible = false; break; }
            }
            row.classList.toggle("d-none", !visible);
        });
    }

    _hideAllFilterPopovers() {
        this.filterPopoverTargets.forEach((p) => p.classList.add("d-none"));
    }

    _findFilterPopover(col) {
        return this.filterPopoverTargets.find((p) => Number(p.dataset.tableFiltersColumn) === col);
    }

    _findFilterInput(col) {
        return this.filterInputTargets.find((i) => Number(i.dataset.tableFiltersColumn) === col);
    }

    _filterButton(col) {
        return this.element.querySelector(
            `[data-table-filters-role="filter"][data-table-filters-column-param="${col}"]`
        );
    }

    _sortButton(col) {
        return this.element.querySelector(
            `[data-table-filters-role="sort"][data-table-filters-column-param="${col}"]`
        );
    }

    _updateFilterIcon(col) {
        const btn = this._filterButton(col);
        if (!btn) return;

        const icon = btn.querySelector("i");
        const active = !!this.filters[col];

        icon.classList.toggle("bi-funnel", !active);
        icon.classList.toggle("bi-funnel-fill", active);
        btn.classList.toggle("text-primary", active);
    }

    _updateSortIcons() {
        // reset all
        this.element
            .querySelectorAll(`[data-table-filters-role="sort"]`)
            .forEach((btn) => {
                const icon = btn.querySelector("i");
                btn.classList.remove("text-primary");
                icon.classList.remove("bi-sort-alpha-up", "bi-sort-alpha-down");
                icon.classList.add("bi-sort-alpha-down");
            });

        if (this.sortState.col === null) return;

        const btn = this._sortButton(this.sortState.col);
        if (!btn) return;

        const icon = btn.querySelector("i");
        btn.classList.add("text-primary");

        icon.classList.toggle("bi-sort-alpha-down", this.sortState.dir === "asc");
        icon.classList.toggle("bi-sort-alpha-up", this.sortState.dir === "desc");
    }
}

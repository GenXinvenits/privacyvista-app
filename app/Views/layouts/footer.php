</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="/app/public/assets/js/app.js"></script>

<style>
/* Global CRUD action buttons: compact, borderless, icon-only */
.table .pv-action-icon,
.table a.btn[title*="View"],
.table a.btn[title*="Edit"],
.table a.btn[title*="Delete"],
.table button.btn[title*="View"],
.table button.btn[title*="Edit"],
.table button.btn[title*="Delete"] {
    display:inline-flex !important;
    align-items:center;
    justify-content:center;
    width:34px;
    height:34px;
    padding:0 !important;
    margin-right:3px;
    border:0 !important;
    border-color:transparent !important;
    background:transparent !important;
    box-shadow:none !important;
    border-radius:9px;
}

.table .pv-action-icon:hover,
.table .pv-action-icon:focus-visible,
.table a.btn[title*="View"]:hover,
.table a.btn[title*="View"]:focus-visible,
.table a.btn[title*="Edit"]:hover,
.table a.btn[title*="Edit"]:focus-visible,
.table a.btn[title*="Delete"]:hover,
.table a.btn[title*="Delete"]:focus-visible,
.table button.btn[title*="View"]:hover,
.table button.btn[title*="View"]:focus-visible,
.table button.btn[title*="Edit"]:hover,
.table button.btn[title*="Edit"]:focus-visible,
.table button.btn[title*="Delete"]:hover,
.table button.btn[title*="Delete"]:focus-visible {
    border:0 !important;
    border-color:transparent !important;
    box-shadow:none !important;
    background:rgba(127,127,127,.10) !important;
}

.table .pv-action-icon svg,
.table a.btn[title*="View"] svg,
.table a.btn[title*="Edit"] svg,
.table a.btn[title*="Delete"] svg,
.table button.btn[title*="View"] svg,
.table button.btn[title*="Edit"] svg,
.table button.btn[title*="Delete"] svg {
    width:17px;
    height:17px;
}

/* Convert any remaining text-only CRUD buttons into the same icon treatment. */
.table a.btn, .table button.btn { vertical-align:middle; }
</style>

<script>
(function () {
    const icons = {
        view: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>',
        edit: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>',
        delete: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v5"/><path d="M14 11v5"/></svg>'
    };

    function normalizeCrudActions(root) {
        root.querySelectorAll('.table a.btn, .table button.btn').forEach(function (el) {
            if (el.classList.contains('pv-action-icon') && el.querySelector('svg')) return;

            const text = (el.textContent || '').trim().toLowerCase();
            const title = (el.getAttribute('title') || '').toLowerCase();
            const aria = (el.getAttribute('aria-label') || '').toLowerCase();
            const value = text + ' ' + title + ' ' + aria;
            let type = null;

            if (/^view(?:\s|$)/.test(text) || value.includes('view ' ) || value.includes('view\u00a0')) type = 'view';
            else if (/^edit(?:\s|$)/.test(text) || value.includes('edit ')) type = 'edit';
            else if (/^delete(?:\s|$)/.test(text) || value.includes('delete ')) type = 'delete';

            if (!type) return;

            el.classList.add('pv-action-icon');
            el.setAttribute('title', type.charAt(0).toUpperCase() + type.slice(1));
            el.setAttribute('aria-label', type.charAt(0).toUpperCase() + type.slice(1));
            el.innerHTML = icons[type];
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { normalizeCrudActions(document); });
    } else {
        normalizeCrudActions(document);
    }
})();
</script>

</body>
</html>
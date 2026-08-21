/* PrivacyVista — Font Awesome icon standardization */
(() => {
    'use strict';

    const rules = [
        [/dashboard|home/i, 'fa-gauge-high'],
        [/form/i, 'fa-file-lines'],
        [/client|organisation|organization/i, 'fa-building'],
        [/user|people|member/i, 'fa-users'],
        [/department/i, 'fa-building-user'],
        [/activit|processing/i, 'fa-list-check'],
        [/assessment/i, 'fa-clipboard-check'],
        [/finding|warning|risk/i, 'fa-triangle-exclamation'],
        [/task|remediation|todo/i, 'fa-list-check'],
        [/report|analytics/i, 'fa-chart-column'],
        [/setting|preference/i, 'fa-gear'],
        [/profile|account/i, 'fa-user'],
        [/logout|sign out/i, 'fa-right-from-bracket'],
        [/login|sign in/i, 'fa-right-to-bracket'],
        [/search/i, 'fa-magnifying-glass'],
        [/add|create|new/i, 'fa-plus'],
        [/edit|modify/i, 'fa-pen-to-square'],
        [/delete|remove/i, 'fa-trash'],
        [/save|submit/i, 'fa-floppy-disk'],
        [/download|export/i, 'fa-download'],
        [/upload|import/i, 'fa-upload'],
        [/calendar|date/i, 'fa-calendar-days'],
        [/mail|email/i, 'fa-envelope'],
        [/password|security/i, 'fa-lock'],
        [/more/i, 'fa-ellipsis'],
        [/close|cancel/i, 'fa-xmark'],
        [/back|previous/i, 'fa-arrow-left'],
        [/next|forward/i, 'fa-arrow-right'],
        [/check|complete|success/i, 'fa-check'],
        [/info|information/i, 'fa-circle-info'],
        [/help/i, 'fa-circle-question'],
        [/filter/i, 'fa-filter'],
        [/sort/i, 'fa-arrow-down-wide-short'],
        [/view|details/i, 'fa-eye']
    ];

    const iconFor = text => {
        for (const [pattern, icon] of rules) if (pattern.test(text)) return icon;
        return 'fa-circle';
    };

    const getContext = svg => {
        const labelled = svg.getAttribute('aria-label') || svg.getAttribute('data-icon') || svg.getAttribute('title') || '';
        if (labelled) return labelled;
        const owner = svg.closest('a,button,[role="button"],.card,.stat-card,.action-card');
        return owner ? (owner.getAttribute('aria-label') || owner.textContent || '') : '';
    };

    const replaceIcons = root => {
        root.querySelectorAll('svg:not([data-fa-preserve="true"])').forEach(svg => {
            if (svg.closest('.navbar-brand-logo, .sidebar-brand')) return;
            const context = getContext(svg);
            const icon = document.createElement('i');
            icon.className = `fa-solid ${iconFor(context)}`;
            icon.setAttribute('aria-hidden', 'true');
            icon.dataset.faReplacement = 'true';
            svg.replaceWith(icon);
        });
    };

    const init = () => {
        replaceIcons(document);
        const observer = new MutationObserver(records => {
            records.forEach(record => record.addedNodes.forEach(node => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.matches?.('svg')) replaceIcons(node.parentElement || document);
                    else replaceIcons(node);
                }
            }));
        });
        observer.observe(document.body, { childList: true, subtree: true });
    };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init, { once: true });
    else init();
})();

/* PrivacyVista — lightweight Liquid Glass cursor interaction */

(() => {
    'use strict';

    if (!window.matchMedia?.('(prefers-reduced-motion: reduce)').matches) {
        const selector = '.card, .login-card';
        let active = null;
        let raf = 0;
        let clientX = 0;
        let clientY = 0;

        const render = () => {
            raf = 0;
            if (!active) return;
            const rect = active.getBoundingClientRect();
            if (!rect.width || !rect.height) return;
            const x = Math.max(0, Math.min(rect.width, clientX - rect.left));
            const y = Math.max(0, Math.min(rect.height, clientY - rect.top));
            const edge = Math.min(x, y, rect.width - x, rect.height - y);
            const range = Math.max(48, Math.min(rect.width, rect.height) * .20);
            const strength = Math.max(0, Math.min(1, 1 - edge / range));
            active.style.setProperty('--glass-shine-x', `${(x / rect.width) * 100}%`);
            active.style.setProperty('--glass-shine-y', `${(y / rect.height) * 100}%`);
            active.style.setProperty('--glass-shine-opacity', (strength * .72).toFixed(3));
        };
        const schedule = () => { if (!raf) raf = requestAnimationFrame(render); };
        document.addEventListener('pointerover', event => {
            const element = event.target.closest?.(selector);
            if (!element) return;
            active = element; clientX = event.clientX; clientY = event.clientY; schedule();
        }, { passive: true });
        document.addEventListener('pointermove', event => {
            if (!active) return;
            if (!event.target.closest?.(selector)) {
                active.style.setProperty('--glass-shine-opacity', '0'); active = null; return;
            }
            clientX = event.clientX; clientY = event.clientY; schedule();
        }, { passive: true });
        document.addEventListener('pointerout', event => {
            if (!active) return;
            const next = event.relatedTarget;
            if (next && next.closest?.(selector) === active) return;
            active.style.setProperty('--glass-shine-opacity', '0'); active = null;
        }, { passive: true });
    }

    const initMoreNavigation = () => {
        const sidebar = document.querySelector('.sidebar');
        const nav = sidebar?.querySelector('nav');
        const desktopTrigger = sidebar?.querySelector('.more-desktop-trigger');
        if (!sidebar || !nav) return;

        const primary = new Set(['Dashboard', 'Forms', 'Reports', 'Settings']);
        const links = Array.from(nav.querySelectorAll('.nav-link[data-mobile-label]'));
        const secondary = links.filter(link => !primary.has(link.dataset.mobileLabel));

        let menu = sidebar.querySelector('.mobile-more-menu');
        if (!menu) {
            menu = document.createElement('div');
            menu.id = 'privacyvista-more-menu';
            menu.className = 'mobile-more-menu';
            menu.setAttribute('role', 'dialog');
            menu.setAttribute('aria-label', 'More navigation');
            menu.setAttribute('aria-hidden', 'true');
            menu.innerHTML = '<div class="mobile-more-header"><span>More</span><button type="button" class="mobile-more-close" aria-label="Close More menu">&times;</button></div><div class="mobile-more-grid"></div>';
            const grid = menu.querySelector('.mobile-more-grid');
            secondary.forEach(link => {
                const item = link.cloneNode(true);
                item.classList.remove('active');
                item.removeAttribute('data-mobile-label');
                grid.appendChild(item);
                link.classList.add('mobile-secondary-link');
            });
            sidebar.appendChild(menu);
        }

        let mobileTrigger = nav.querySelector('.mobile-more-trigger');
        if (!mobileTrigger && secondary.length) {
            mobileTrigger = document.createElement('button');
            mobileTrigger.type = 'button';
            mobileTrigger.className = 'nav-link mobile-more-trigger';
            mobileTrigger.setAttribute('aria-label', 'More navigation');
            mobileTrigger.setAttribute('aria-expanded', 'false');
            mobileTrigger.innerHTML = '<svg class="mobile-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="5" cy="12" r="1.2" fill="currentColor"/><circle cx="12" cy="12" r="1.2" fill="currentColor"/><circle cx="19" cy="12" r="1.2" fill="currentColor"/></svg><span>More</span>';
            nav.appendChild(mobileTrigger);
        }

        const setOpen = open => {
            menu.classList.toggle('is-open', open);
            menu.setAttribute('aria-hidden', open ? 'false' : 'true');
            desktopTrigger?.setAttribute('aria-expanded', open ? 'true' : 'false');
            mobileTrigger?.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        const toggle = event => {
            event.preventDefault();
            event.stopPropagation();
            setOpen(!menu.classList.contains('is-open'));
        };

        desktopTrigger?.addEventListener('click', toggle);
        mobileTrigger?.addEventListener('click', toggle);
        menu.querySelector('.mobile-more-close')?.addEventListener('click', () => setOpen(false));
        document.addEventListener('click', event => {
            if (menu.classList.contains('is-open') && !menu.contains(event.target) && event.target !== desktopTrigger && event.target !== mobileTrigger) setOpen(false);
        });
        document.addEventListener('keydown', event => { if (event.key === 'Escape') setOpen(false); });
        window.addEventListener('resize', () => { if (window.innerWidth > 767) setOpen(false); }, { passive: true });
    };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMoreNavigation, { once: true });
    else initMoreNavigation();
})();

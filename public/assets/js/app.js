/* PrivacyVista — Liquid Glass interaction + mobile navigation */

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
            active = element;
            clientX = event.clientX;
            clientY = event.clientY;
            schedule();
        }, { passive: true });

        document.addEventListener('pointermove', event => {
            if (!active) return;
            if (!event.target.closest?.(selector)) {
                active.style.setProperty('--glass-shine-opacity', '0');
                active = null;
                return;
            }
            clientX = event.clientX;
            clientY = event.clientY;
            schedule();
        }, { passive: true });

        document.addEventListener('pointerout', event => {
            if (!active) return;
            const next = event.relatedTarget;
            if (next && next.closest?.(selector) === active) return;
            active.style.setProperty('--glass-shine-opacity', '0');
            active = null;
        }, { passive: true });
    }

    const initMobileNavigation = () => {
        const sidebar = document.querySelector('.sidebar');
        const nav = sidebar?.querySelector('nav');
        if (!sidebar || !nav) return;

        const primary = ['Dashboard', 'Forms', 'Clients', 'Users'];
        const links = Array.from(nav.querySelectorAll('.nav-link[data-mobile-label]'));
        const primaryLinks = primary.map(label => links.find(link => link.dataset.mobileLabel === label)).filter(Boolean);
        const extraLinks = links.filter(link => !primary.includes(link.dataset.mobileLabel));

        let moreTrigger = nav.querySelector('.mobile-more-trigger');
        if (!moreTrigger) {
            moreTrigger = document.createElement('button');
            moreTrigger.type = 'button';
            moreTrigger.className = 'nav-link mobile-more-trigger';
            moreTrigger.setAttribute('aria-label', 'More navigation');
            moreTrigger.setAttribute('aria-expanded', 'false');
            moreTrigger.innerHTML = '<i class="fa-solid fa-ellipsis-vertical" aria-hidden="true"></i><span>More</span>';
            nav.appendChild(moreTrigger);
        }

        let popup = sidebar.querySelector('.mobile-more-menu');
        if (!popup) {
            popup = document.createElement('div');
            popup.className = 'mobile-more-menu';
            popup.setAttribute('aria-hidden', 'true');
            popup.innerHTML = '<div class="mobile-more-header"><strong>More</strong><button type="button" class="mobile-more-close" aria-label="Close More"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button></div><div class="mobile-more-grid"></div>';
            sidebar.appendChild(popup);
        }

        const grid = popup.querySelector('.mobile-more-grid');
        if (grid && !grid.children.length) {
            extraLinks.forEach(link => {
                const item = link.cloneNode(true);
                item.classList.remove('active');
                grid.appendChild(item);
            });
        }

        const setOpen = open => {
            popup.classList.toggle('is-open', open);
            popup.setAttribute('aria-hidden', open ? 'false' : 'true');
            moreTrigger.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        moreTrigger.addEventListener('click', event => {
            event.preventDefault();
            event.stopPropagation();
            setOpen(!popup.classList.contains('is-open'));
        });

        popup.querySelector('.mobile-more-close')?.addEventListener('click', () => setOpen(false));
        document.addEventListener('click', event => {
            if (popup.classList.contains('is-open') && !popup.contains(event.target) && event.target !== moreTrigger) setOpen(false);
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') setOpen(false);
        });

        const sync = () => {
            const mobile = window.innerWidth <= 767.98;
            primaryLinks.forEach((link, index) => {
                link.style.order = String(index + 1);
                link.style.display = mobile ? 'flex' : '';
            });
            extraLinks.forEach(link => { link.style.display = mobile ? 'none' : ''; });
            moreTrigger.style.display = mobile ? 'flex' : 'none';
            if (!mobile) setOpen(false);
        };

        window.addEventListener('resize', sync, { passive: true });
        sync();
    };

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMobileNavigation, { once: true });
    else initMobileNavigation();
})();

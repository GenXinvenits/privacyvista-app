/* PrivacyVista — lightweight Liquid Glass cursor interaction */

(() => {
    'use strict';

    if (window.matchMedia?.('(prefers-reduced-motion: reduce)').matches) return;

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

    const schedule = () => {
        if (!raf) raf = requestAnimationFrame(render);
    };

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
})();

/* =========================================================
   PrivacyVista — Liquid Glass cursor interaction
   ========================================================= */

(function () {
    'use strict';

    const selectors = '.card, .login-card';
    const reduceMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)');

    if (reduceMotion?.matches) return;

    let raf = 0;
    let active = null;
    let pointerX = 0;
    let pointerY = 0;

    function update() {
        raf = 0;

        if (!active) return;

        const rect = active.getBoundingClientRect();
        const x = Math.max(0, Math.min(rect.width, pointerX - rect.left));
        const y = Math.max(0, Math.min(rect.height, pointerY - rect.top));

        const px = (x / rect.width) * 100;
        const py = (y / rect.height) * 100;

        // Shine becomes stronger near an edge and softer toward the centre.
        const edgeDistance = Math.min(x, y, rect.width - x, rect.height - y);
        const edgeRange = Math.max(55, Math.min(rect.width, rect.height) * 0.20);
        const strength = Math.max(0, Math.min(1, 1 - edgeDistance / edgeRange));

        active.style.setProperty('--glass-shine-x', px.toFixed(2) + '%');
        active.style.setProperty('--glass-shine-y', py.toFixed(2) + '%');
        active.style.setProperty('--glass-shine-opacity', (strength * 0.72).toFixed(3));
    }

    function schedule() {
        if (!raf) raf = requestAnimationFrame(update);
    }

    function enter(event) {
        active = event.currentTarget;
        pointerX = event.clientX;
        pointerY = event.clientY;
        active.style.setProperty('--glass-shine-opacity', '0');
        schedule();
    }

    function move(event) {
        if (active !== event.currentTarget) return;
        pointerX = event.clientX;
        pointerY = event.clientY;
        schedule();
    }

    function leave(event) {
        if (active !== event.currentTarget) return;
        const element = active;
        active = null;
        element.style.setProperty('--glass-shine-opacity', '0');
    }

    function bind() {
        document.querySelectorAll(selectors).forEach((element) => {
            if (element.dataset.liquidGlassCursor === '1') return;
            element.dataset.liquidGlassCursor = '1';
            element.addEventListener('pointerenter', enter, { passive: true });
            element.addEventListener('pointermove', move, { passive: true });
            element.addEventListener('pointerleave', leave, { passive: true });
        });
    }

    bind();

    // Covers cards injected dynamically by Bootstrap/modal/navigation code.
    const observer = new MutationObserver(bind);
    observer.observe(document.body, { childList: true, subtree: true });
})();

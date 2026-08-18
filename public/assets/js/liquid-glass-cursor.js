/* PrivacyVista — cursor-reactive Liquid Glass */
(function () {
    'use strict';

    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const root = document.documentElement;
    let raf = 0;
    let x = window.innerWidth * 0.5;
    let y = window.innerHeight * 0.35;
    let tx = x;
    let ty = y;

    function render() {
        x += (tx - x) * 0.085;
        y += (ty - y) * 0.085;

        const px = Math.round((x / Math.max(window.innerWidth, 1)) * 1000) / 10;
        const py = Math.round((y / Math.max(window.innerHeight, 1)) * 1000) / 10;

        root.style.setProperty('--glass-cursor-x', px + '%');
        root.style.setProperty('--glass-cursor-y', py + '%');

        raf = requestAnimationFrame(render);
    }

    window.addEventListener('pointermove', function (event) {
        tx = event.clientX;
        ty = event.clientY;
    }, { passive: true });

    window.addEventListener('pointerleave', function () {
        tx = window.innerWidth * 0.5;
        ty = window.innerHeight * 0.35;
    }, { passive: true });

    window.addEventListener('resize', function () {
        tx = Math.min(tx, window.innerWidth);
        ty = Math.min(ty, window.innerHeight);
    }, { passive: true });

    render();
})();

/* PrivacyVista — Apple-inspired cursor-reactive Liquid Glass */
(function () {
    'use strict';

    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const selectors = [
        '.navbar',
        '.sidebar',
        '.card',
        '.dropdown-menu',
        '.user-dropdown',
        '.modal-content',
        '.list-group-item'
    ];

    let pointerX = window.innerWidth * 0.5;
    let pointerY = window.innerHeight * 0.35;
    let targetX = pointerX;
    let targetY = pointerY;
    let elements = [];

    function collect() {
        elements = [];
        document.querySelectorAll(selectors.join(',')).forEach(function (element) {
            if (!elements.includes(element)) elements.push(element);
        });
    }

    function updateElementLight(element) {
        const rect = element.getBoundingClientRect();
        if (!rect.width || !rect.height) return;

        // Pointer position relative to THIS glass surface, not the viewport.
        const localX = ((pointerX - rect.left) / rect.width) * 100;
        const localY = ((pointerY - rect.top) / rect.height) * 100;

        element.style.setProperty('--glass-local-x', localX.toFixed(2) + '%');
        element.style.setProperty('--glass-local-y', localY.toFixed(2) + '%');

        // Distance from the pointer to the glass surface controls intensity.
        const dx = pointerX < rect.left ? rect.left - pointerX : pointerX > rect.right ? pointerX - rect.right : 0;
        const dy = pointerY < rect.top ? rect.top - pointerY : pointerY > rect.bottom ? pointerY - rect.bottom : 0;
        const distance = Math.sqrt(dx * dx + dy * dy);
        const influence = Math.max(0, 1 - distance / 420);

        element.style.setProperty('--glass-pointer-strength', influence.toFixed(3));
    }

    function render() {
        pointerX += (targetX - pointerX) * 0.11;
        pointerY += (targetY - pointerY) * 0.11;

        elements.forEach(updateElementLight);
        requestAnimationFrame(render);
    }

    window.addEventListener('pointermove', function (event) {
        targetX = event.clientX;
        targetY = event.clientY;
    }, { passive: true });

    window.addEventListener('pointerleave', function () {
        targetX = window.innerWidth * 0.5;
        targetY = window.innerHeight * 0.35;
    }, { passive: true });

    window.addEventListener('resize', collect, { passive: true });

    // Content can be replaced dynamically by the application.
    new MutationObserver(collect).observe(document.body, {
        childList: true,
        subtree: true
    });

    collect();
    render();
})();

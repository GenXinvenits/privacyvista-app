(function () {
    'use strict';

    var STORAGE_KEY = 'privacyvista-theme';
    var root = document.documentElement;

    function getPreference() {
        try {
            return localStorage.getItem(STORAGE_KEY) || 'system';
        } catch (e) {
            return 'system';
        }
    }

    function getBrowserTheme() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches
            ? 'light'
            : 'dark';
    }

    function applyTheme(preference) {
        preference = preference === 'light' || preference === 'dark' ? preference : 'system';

        var resolved = preference === 'system' ? getBrowserTheme() : preference;

        root.setAttribute('data-theme', resolved);
        root.setAttribute('data-theme-preference', preference);

        document.documentElement.style.colorScheme = resolved;

        document.querySelectorAll('[data-theme-choice]').forEach(function (button) {
            var active = button.getAttribute('data-theme-choice') === preference;
            button.classList.toggle('is-active', active);
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    }

    function saveTheme(preference) {
        try {
            localStorage.setItem(STORAGE_KEY, preference);
        } catch (e) {
            // Continue with the current page theme when storage is unavailable.
        }
        applyTheme(preference);
    }

    // Apply before normal page rendering to reduce theme flashing.
    applyTheme(getPreference());

    document.addEventListener('DOMContentLoaded', function () {
        applyTheme(getPreference());

        document.querySelectorAll('[data-theme-choice]').forEach(function (button) {
            button.addEventListener('click', function () {
                saveTheme(button.getAttribute('data-theme-choice'));
            });
        });

        if (window.matchMedia) {
            var media = window.matchMedia('(prefers-color-scheme: light)');
            var handleBrowserThemeChange = function () {
                if (getPreference() === 'system') {
                    applyTheme('system');
                }
            };

            if (media.addEventListener) {
                media.addEventListener('change', handleBrowserThemeChange);
            } else if (media.addListener) {
                media.addListener(handleBrowserThemeChange);
            }
        }
    });
})();

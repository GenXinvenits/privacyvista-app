(function () {
    'use strict';

    var STORAGE_KEY = 'privacyvista-theme';
    var root = document.documentElement;
    var mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: light)') : null;
    var themeButtons = null;
    var pendingFrame = 0;

    function getPreference() {
        try {
            return localStorage.getItem(STORAGE_KEY) || 'system';
        } catch (e) {
            return 'system';
        }
    }

    function getBrowserTheme() {
        return mediaQuery && mediaQuery.matches ? 'light' : 'dark';
    }

    function normalizePreference(preference) {
        return preference === 'light' || preference === 'dark' ? preference : 'system';
    }

    function updateThemeButtons(preference) {
        if (!themeButtons) {
            themeButtons = document.querySelectorAll('[data-theme-choice]');
        }

        themeButtons.forEach(function (button) {
            var active = button.getAttribute('data-theme-choice') === preference;
            button.classList.toggle('is-active', active);
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    }

    function applyTheme(preference) {
        preference = normalizePreference(preference);
        var resolved = preference === 'system' ? getBrowserTheme() : preference;

        /*
         * Only change the attributes that actually need changing. This avoids
         * unnecessary style recalculation when the user clicks the already
         * active option.
         */
        if (root.getAttribute('data-theme') !== resolved) {
            root.setAttribute('data-theme', resolved);
            root.style.colorScheme = resolved;
        }

        if (root.getAttribute('data-theme-preference') !== preference) {
            root.setAttribute('data-theme-preference', preference);
        }

        updateThemeButtons(preference);
    }

    function applyThemeSmooth(preference) {
        preference = normalizePreference(preference);

        if (pendingFrame) {
            cancelAnimationFrame(pendingFrame);
        }

        /* Commit the theme change once per frame instead of forcing multiple
           synchronous style recalculations during rapid clicks. */
        pendingFrame = requestAnimationFrame(function () {
            pendingFrame = 0;
            applyTheme(preference);
        });
    }

    function saveTheme(preference) {
        preference = normalizePreference(preference);

        try {
            if (localStorage.getItem(STORAGE_KEY) !== preference) {
                localStorage.setItem(STORAGE_KEY, preference);
            }
        } catch (e) {
            // Continue even when localStorage is unavailable.
        }

        applyThemeSmooth(preference);
    }

    /* Apply immediately before rendering to prevent theme flash. */
    applyTheme(getPreference());

    document.addEventListener('DOMContentLoaded', function () {
        themeButtons = document.querySelectorAll('[data-theme-choice]');
        applyTheme(getPreference());

        themeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                saveTheme(button.getAttribute('data-theme-choice'));
            });
        });

        if (mediaQuery) {
            var handleBrowserThemeChange = function () {
                if (getPreference() === 'system') {
                    applyThemeSmooth('system');
                }
            };

            if (mediaQuery.addEventListener) {
                mediaQuery.addEventListener('change', handleBrowserThemeChange);
            } else if (mediaQuery.addListener) {
                mediaQuery.addListener(handleBrowserThemeChange);
            }
        }
    });
})();
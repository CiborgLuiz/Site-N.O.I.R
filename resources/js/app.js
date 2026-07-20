// Main entry point - NOIR Site
// Loads core modules conditionally based on page/context

import './bootstrap';

// Global NOIR namespace
window.NOIR = {
    version: '2.0.0',
    modules: {},
    config: {
        reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        isMobile: window.matchMedia('(max-width: 768px)').matches,
        isTouch: window.matchMedia('(hover: none) and (pointer: coarse)').matches,
    },
    utils: {
        // Debounce utility
        debounce(fn, wait) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, args), wait);
            };
        },
        // Throttle utility
        throttle(fn, limit) {
            let inThrottle;
            return (...args) => {
                if (!inThrottle) {
                    fn.apply(this, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },
        // Check if element is in viewport
        inViewport(el, threshold = 0.1) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * (1 - threshold) &&
                rect.bottom >= (window.innerHeight || document.documentElement.clientHeight) * threshold
            );
        },
    },
};

// Initialize core modules on DOM ready
document.addEventListener('DOMContentLoaded', async () => {
    // Update config on resize
    window.addEventListener('resize', NOIR.utils.debounce(() => {
        NOIR.config.isMobile = window.matchMedia('(max-width: 768px)').matches;
        NOIR.config.isTouch = window.matchMedia('(hover: none) and (pointer: coarse)').matches;
    }, 150));

    // Load site.js (global interactions, easter eggs, reveal animations)
    await import('./site.js').then(mod => {
        NOIR.modules.site = mod.default || mod;
    });

    // Load background (lazy-loaded via noir-bg-lazy.js in layout)
    // noir-bg-lazy.js handles IntersectionObserver and dynamic import of noir-background.js

    // Page-specific modules (loaded based on body class or data attribute)
    const pageType = document.body.dataset.page || document.body.className.match(/page-(\w+)/)?.[1];

    switch (pageType) {
        case 'sistema':
            if (NOIR.config.isMobile) {
                await import('./system-app.js').then(mod => {
                    NOIR.modules.systemApp = mod.default || mod;
                }).catch(() => {});
            } else {
                await import('./system-desktop.js').then(mod => {
                    NOIR.modules.systemDesktop = mod.default || mod;
                }).catch(() => {});
            }
            break;
        case 'terminal':
            await import('./terminal-engine.js').then(mod => {
                NOIR.modules.terminal = mod.default || mod;
            }).catch(() => {});
            break;
        case 'entidades':
            await import('./entities-engine.js').then(mod => {
                NOIR.modules.entities = mod.default || mod;
            }).catch(() => {});
            break;
        case 'protocolos':
            await import('./protocols-engine.js').then(mod => {
                NOIR.modules.protocols = mod.default || mod;
            }).catch(() => {});
            break;
        case 'arquivos':
            await import('./files-engine.js').then(mod => {
                NOIR.modules.files = mod.default || mod;
            }).catch(() => {});
            break;
    }

    // Scroll narrative (desktop only, enhanced experience)
    if (!NOIR.config.reducedMotion && !NOIR.config.isMobile) {
        await import('./scroll-narrative.js').then(mod => {
            NOIR.modules.scrollNarrative = mod.default || mod;
        }).catch(() => {});
    }

    // Page transitions (View Transitions API progressive enhancement)
    if (document.startViewTransition) {
        await import('./page-transitions.js').then(mod => {
            NOIR.modules.pageTransitions = mod.default || mod;
        }).catch(() => {});
    }

    // Dispatch ready event for modules that need to wait
    document.dispatchEvent(new CustomEvent('noir:app-ready', { detail: { NOIR } }));
});

// Export for module consumers
export default NOIR;
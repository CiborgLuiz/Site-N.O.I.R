// Lazy-load Three.js background when hero becomes visible
// ponytail: single observer, one dynamic import, done

const hero = document.querySelector('.hero');

function loadBackground() {
    window.__NOIR_BG_LAZY__ = true;
    import('./noir-background.js').then(({ NoirBackground }) => {
        const instance = new NoirBackground();
        window.NOIR_BG = {
            setQuality: (q) => instance.setQuality(q),
            pause: () => instance.pause(),
            resume: () => instance.resume(),
        };
    });
}

if (!hero || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    // No hero or reduced motion → skip Three.js entirely
    const canvas = document.getElementById('noir-bg');
    if (canvas) {
        canvas.style.background = '#0a0a0a';
    }
} else if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting) {
                observer.disconnect();
                loadBackground();
            }
        },
        { threshold: 0.01 }
    );
    observer.observe(hero);
    // Fallback: load after 4s even if hero not scrolled into view
    setTimeout(() => {
        observer.disconnect();
        if (!window.NOIR_BG) loadBackground();
    }, 4000);
} else {
    loadBackground();
}

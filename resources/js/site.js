const revealSelectors = [
    '.navbar',
    '.hero',
    '.hero h1',
    '.hero-subtitle',
    '.divider',
    '.hero-description',
    '.section',
    '.section .status-box',
    '.section .card',
    '.archive-card',
    '.footer',
    '.system-wrapper',
    '.monitor-frame',
];

const interactiveSelectors = [
    '.card',
    '.status-box',
    '.archive-card',
    '.monitor-frame',
    '.btn',
    '.btn2',
    '.desktop-icon',
];

const loaderMessages = [
    'Sincronizando protocolos sigilosos',
    'Calibrando observadores de campo',
    'Varredura dimensional em andamento',
    'Liberando camada de acesso',
];

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.addEventListener('DOMContentLoaded', () => {
    setupLoader();
    setupRevealTargets();
    setupInteractivePanels();
    setupHeroDrift();
});

function setupLoader() {
    const body = document.body;
    const loader = document.querySelector('[data-noir-loader]');

    if (!loader) {
        body.classList.remove('noir-loading');
        body.classList.add('noir-loaded');
        document.dispatchEvent(new CustomEvent('noir:loader-complete'));
        return;
    }

    const pageLabel = loader.querySelector('[data-loader-page]');
    const status = loader.querySelector('[data-loader-status]');
    const progressBar = loader.querySelector('[data-loader-bar]');
    const percent = loader.querySelector('[data-loader-percent]');
    const cleanTitle = document.title.replace(/^N\.O\.I\.R\s*[—-]\s*/i, '').trim() || 'Acesso';

    let progress = 0;
    let targetProgress = 16;
    let completed = document.readyState === 'complete';
    let activeMessage = 0;
    let finished = false;

    const startTime = performance.now();
    const minimumDuration = prefersReducedMotion ? 500 : 1600;
    const fallbackTimeout = prefersReducedMotion ? 1400 : 4200;

    if (pageLabel) {
        pageLabel.textContent = cleanTitle;
    }

    if (status) {
        status.textContent = loaderMessages[activeMessage];
    }

    const messageTimer = window.setInterval(() => {
        if (completed) {
            return;
        }

        activeMessage = (activeMessage + 1) % loaderMessages.length;
        targetProgress = Math.min(targetProgress + 12, 88);

        if (status) {
            status.textContent = loaderMessages[activeMessage];
        }
    }, prefersReducedMotion ? 260 : 520);

    const markLoaded = () => {
        completed = true;
        targetProgress = 100;

        if (status) {
            status.textContent = 'Interface autorizada';
        }
    };

    window.addEventListener('load', markLoaded, { once: true });
    window.setTimeout(markLoaded, fallbackTimeout);

    const animate = () => {
        if (finished) {
            return;
        }

        const maxStep = completed ? 0.22 : 0.075;
        progress += (targetProgress - progress) * maxStep;

        if (progressBar) {
            progressBar.style.setProperty('--loader-progress', `${progress.toFixed(2)}%`);
        }

        if (percent) {
            percent.textContent = `${Math.round(progress)}%`;
        }

        const elapsed = performance.now() - startTime;

        if (completed && elapsed >= minimumDuration && progress >= 99.4) {
            finishLoader();
            return;
        }

        requestAnimationFrame(animate);
    };

    const finishLoader = () => {
        if (finished) {
            return;
        }

        finished = true;
        window.clearInterval(messageTimer);

        if (progressBar) {
            progressBar.style.setProperty('--loader-progress', '100%');
        }

        if (percent) {
            percent.textContent = '100%';
        }

        loader.classList.add('is-complete');
        body.classList.remove('noir-loading');
        body.classList.add('noir-loaded');
        document.dispatchEvent(new CustomEvent('noir:loader-complete'));

        window.setTimeout(() => {
            loader.remove();
        }, prefersReducedMotion ? 40 : 850);
    };

    requestAnimationFrame(animate);
}

function setupRevealTargets() {
    const uniqueTargets = new Set();

    revealSelectors.forEach((selector) => {
        document.querySelectorAll(selector).forEach((element) => uniqueTargets.add(element));
    });

    let delayIndex = 0;

    uniqueTargets.forEach((element) => {
        element.classList.add('noir-reveal');
        element.style.setProperty('--noir-delay', `${Math.min(delayIndex * 70, 560)}ms`);
        delayIndex += 1;
    });

    const immediateTargets = Array.from(uniqueTargets).filter((element) =>
        element.matches('.navbar, .hero, .hero h1, .hero-subtitle, .divider, .hero-description, .system-wrapper, .monitor-frame')
    );

    const deferredTargets = Array.from(uniqueTargets).filter((element) => !immediateTargets.includes(element));

    const showImmediate = () => {
        immediateTargets.forEach((element) => {
            element.classList.add('is-visible');
        });
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, {
            threshold: 0.16,
            rootMargin: '0px 0px -10%',
        });

        deferredTargets.forEach((element) => observer.observe(element));
    } else {
        deferredTargets.forEach((element) => element.classList.add('is-visible'));
    }

    if (document.body.classList.contains('noir-loaded')) {
        showImmediate();
        return;
    }

    document.addEventListener('noir:loader-complete', showImmediate, { once: true });
}

function setupInteractivePanels() {
    interactiveSelectors.forEach((selector) => {
        document.querySelectorAll(selector).forEach((element) => {
            if (element.classList.contains('noir-interactive')) {
                return;
            }

            element.classList.add('noir-interactive');

            element.addEventListener('pointermove', (event) => {
                const rect = element.getBoundingClientRect();
                const x = ((event.clientX - rect.left) / rect.width) * 100;
                const y = ((event.clientY - rect.top) / rect.height) * 100;

                element.style.setProperty('--pointer-x', `${x}%`);
                element.style.setProperty('--pointer-y', `${y}%`);
            });

            element.addEventListener('pointerleave', () => {
                element.style.removeProperty('--pointer-x');
                element.style.removeProperty('--pointer-y');
            });
        });
    });
}

function setupHeroDrift() {
    if (prefersReducedMotion) {
        return;
    }

    const hero = document.querySelector('.hero');

    if (!hero) {
        return;
    }

    hero.addEventListener('pointermove', (event) => {
        const rect = hero.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) - 0.5;
        const y = ((event.clientY - rect.top) / rect.height) - 0.5;

        hero.style.setProperty('--hero-shift-x', `${x * 22}px`);
        hero.style.setProperty('--hero-shift-y', `${y * 18}px`);
    });

    hero.addEventListener('pointerleave', () => {
        hero.style.setProperty('--hero-shift-x', '0px');
        hero.style.setProperty('--hero-shift-y', '0px');
    });
}

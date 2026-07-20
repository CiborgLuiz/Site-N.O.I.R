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
    setupScrollProgress();
    setupGyroscopeParallax();
    setupEasterEggs();
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

function setupScrollProgress() {
    if (prefersReducedMotion) return;

    const bar = document.createElement('div');
    bar.className = 'noir-scroll-progress';
    bar.setAttribute('aria-hidden', 'true');
    document.body.prepend(bar);

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            bar.style.setProperty('--scroll-pct', pct + '%');
            ticking = false;
        });
    }, { passive: true });
}

function setupGyroscopeParallax() {
    if (prefersReducedMotion) return;
    if (!window.DeviceOrientationEvent) return;

    // ponytail: only on touch devices with gyroscope
    const mq = window.matchMedia('(hover: none) and (pointer: coarse)');
    if (!mq.matches) return;

    const cards = document.querySelectorAll('.card, .status-box');
    if (!cards.length) return;

    let gamma = 0, beta = 0;
    let rafId = null;

    function onOrientation(e) {
        gamma = (e.gamma || 0) / 45; // -1 to 1
        beta = ((e.beta || 0) - 45) / 45; // center around 45°
        beta = Math.max(-1, Math.min(1, beta));
    }

    function applyTilt() {
        const rx = beta * -6;
        const ry = gamma * 6;
        cards.forEach((card) => {
            card.style.transform = `perspective(600px) rotateX(${rx}deg) rotateY(${ry}deg)`;
        });
        rafId = requestAnimationFrame(applyTilt);
    }

    // Request permission on iOS 13+
    if (typeof DeviceOrientationEvent.requestPermission === 'function') {
        document.addEventListener('click', () => {
            DeviceOrientationEvent.requestPermission().then((state) => {
                if (state === 'granted') {
                    window.addEventListener('deviceorientation', onOrientation);
                    rafId = requestAnimationFrame(applyTilt);
                }
            }).catch(() => {});
        }, { once: true });
    } else {
        window.addEventListener('deviceorientation', onOrientation);
        rafId = requestAnimationFrame(applyTilt);
    }

    // Reset on leave viewport
    document.addEventListener('visibilitychange', () => {
        if (document.hidden && rafId) {
            cancelAnimationFrame(rafId);
            rafId = null;
        } else if (!document.hidden && !rafId) {
            rafId = requestAnimationFrame(applyTilt);
        }
    });
}

function setupEasterEggs() {
    const keyHistory = [];
    const maxHistory = 16;
    const konamiSequences = [
        ['arrowup', 'arrowup', 'arrowdown', 'arrowdown', 'arrowleft', 'arrowright', 'arrowleft', 'arrowright', 'b', 'a'],
        ['w', 'w', 's', 's', 'arrowleft', 'arrowright', 'arrowleft', 'arrowright', 'b', 'a'],
        ['w', 'w', 's', 's', 'a', 'd', 'a', 'd', 'b', 'a'],
    ];

    document.addEventListener('keydown', (event) => {
        if (isTypingField(event.target)) {
            return;
        }

        const key = event.key.toLowerCase();

        keyHistory.push(key);

        if (keyHistory.length > maxHistory) {
            keyHistory.shift();
        }

        if (konamiSequences.some((sequence) => endsWithSequence(keyHistory, sequence))) {
            toggleRetroMode();
            keyHistory.length = 0;
            return;
        }

        if (endsWithSequence(keyHistory, ['n', 'o', 'i', 'r'])) {
            pulseBodyClass('noir-whisper-mode', 4200);
            showEasterSignal('OBSERVADOR DETECTADO', 'noir-easter-whisper', 3000);
            keyHistory.length = 0;
            return;
        }

        if (endsWithSequence(keyHistory, ['s', 'e', 't', 'i', 's'])) {
            pulseBodyClass('noir-setis-breach', 5200);
            showEasterSignal('NIVEL SETIS EM RUPTURA', 'noir-easter-setis', 3600);
            keyHistory.length = 0;
        }
    });

    document.querySelectorAll('.logo').forEach((logo) => {
        let logoClicks = 0;
        let lastLogoClick = 0;

        logo.addEventListener('click', () => {
            const now = performance.now();

            if (now - lastLogoClick > 1400) {
                logoClicks = 0;
            }

            lastLogoClick = now;
            logoClicks += 1;

            if (logoClicks >= 20) {
                logoClicks = 0;
                pulseBodyClass('noir-logo-breach', 5200);
                showEasterSignal('A LOGO RESPONDEU', 'noir-easter-logo', 3300);
            }
        });
    });
}

function isTypingField(target) {
    if (!(target instanceof HTMLElement)) {
        return false;
    }

    return target.matches('input, textarea, select, [contenteditable="true"]');
}

function endsWithSequence(history, sequence) {
    if (history.length < sequence.length) {
        return false;
    }

    return sequence.every((key, index) => history[history.length - sequence.length + index] === key);
}

function toggleRetroMode() {
    document.body.classList.toggle('noir-retro-mode');
    showEasterSignal(
        document.body.classList.contains('noir-retro-mode') ? 'SINAL ANALOGICO ATIVADO' : 'SINAL RESTAURADO',
        'noir-easter-retro',
        2600
    );
}

function pulseBodyClass(className, duration) {
    document.body.classList.add(className);

    window.setTimeout(() => {
        document.body.classList.remove(className);
    }, duration);
}

function showEasterSignal(message, className, duration) {
    const existing = document.querySelector('[data-noir-easter]');

    if (existing) {
        existing.remove();
    }

    const signal = document.createElement('div');
    signal.className = `noir-easter-signal ${className}`;
    signal.dataset.noirEaster = 'true';
    signal.textContent = message;

    document.body.appendChild(signal);

    window.setTimeout(() => {
        signal.classList.add('is-leaving');
    }, Math.max(0, duration - 600));

    window.setTimeout(() => {
        signal.remove();
    }, duration);
}

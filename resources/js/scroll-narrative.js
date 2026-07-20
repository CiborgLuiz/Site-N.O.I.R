// Scroll-driven narrative animations (Desktop only)
// Uses GSAP ScrollTrigger

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export class ScrollNarrative {
    constructor() {
        this.timelines = [];
        this.init();
    }

    init() {
        if (window.NOIR?.config?.reducedMotion || window.NOIR?.config?.isMobile) {
            this.revealAll();
            return;
        }

        this.setupHeroReveal();
        this.setupTimelineSection();
        this.setupPillarsSection();
        this.setupStatusSection();
        this.setupBackgroundSync();
        this.setupParallaxLayers();
        this.setupScrollProgress();
    }

    revealAll() {
        document.querySelectorAll('.noir-reveal').forEach(el => {
            el.classList.add('is-visible');
        });
    }

    setupHeroReveal() {
        const hero = document.querySelector('.hero');
        if (!hero) return;

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: hero,
                start: 'top top',
                end: 'bottom top',
                scrub: 1,
                onUpdate: (self) => {
                    document.dispatchEvent(new CustomEvent('noir:hero-progress', {
                        detail: { progress: self.progress }
                    }));
                }
            }
        });

        tl.fromTo('.hero h1',
            { opacity: 0, y: 60, scale: 1.1 },
            { opacity: 1, y: 0, scale: 1, ease: 'none' }, 0
        );

        tl.fromTo('.hero-subtitle',
            { opacity: 0, y: 40 },
            { opacity: 1, y: 0, ease: 'none' }, 0.1
        );

        tl.fromTo('.hero-description',
            { opacity: 0, y: 30 },
            { opacity: 1, y: 0, ease: 'none' }, 0.2
        );

        tl.fromTo('.scroll-indicator',
            { opacity: 0, y: 20 },
            { opacity: 1, y: 0, ease: 'none' }, 0.4
        );

        this.timelines.push(tl);
    }

    setupTimelineSection() {
        const section = document.querySelector('.section-timeline, .origem-section');
        if (!section) return;

        const items = section.querySelectorAll('.timeline-item, .archive-card');
        if (!items.length) return;

        items.forEach((item, i) => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: item,
                    start: 'top 80%',
                    end: 'top 40%',
                    scrub: 0.8,
                    toggleClass: { targets: item, className: 'is-revealed' }
                }
            });

            tl.fromTo(item,
                { opacity: 0, y: 50, scale: 0.95 },
                { opacity: 1, y: 0, scale: 1, ease: 'none' }
            );

            this.timelines.push(tl);
        });
    }

    setupPillarsSection() {
        const section = document.querySelector('.section-pillars, .pillars-grid');
        if (!section) return;

        const cards = section.querySelectorAll('.pillar-card');
        if (!cards.length) return;

        cards.forEach((card, i) => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: card,
                    start: 'top 85%',
                    end: 'top 50%',
                    scrub: 0.6,
                    toggleClass: { targets: card, className: 'is-revealed' }
                }
            });

            tl.fromTo(card,
                { opacity: 0, y: 40, rotationY: -15 },
                { opacity: 1, y: 0, rotationY: 0, ease: 'power2.out' }
            );

            // 3D tilt on hover (desktop only)
            if (!window.NOIR?.config?.isMobile) {
                card.addEventListener('mousemove', (e) => this.handleTilt(card, e));
                card.addEventListener('mouseleave', () => this.resetTilt(card));
            }

            this.timelines.push(tl);
        });
    }

    handleTilt(card, e) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const rotateX = ((y - centerY) / centerY) * 8;
        const rotateY = ((x - centerX) / centerX) * 8;

        card.style.transform = `perspective(1000px) rotateX(${-rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
    }

    resetTilt(card) {
        card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
    }

    setupStatusSection() {
        const section = document.querySelector('.section-status, .status-grid');
        if (!section) return;

        const counters = section.querySelectorAll('[data-count]');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.count);
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: counter,
                    start: 'top 80%',
                    onEnter: () => this.animateCounter(counter, target)
                }
            });
            this.timelines.push(tl);
        });

        // Reality integrity bar
        const integrityBar = section.querySelector('.integrity-bar');
        if (integrityBar) {
            gsap.to(integrityBar, {
                width: '73%',
                duration: 2,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: integrityBar,
                    start: 'top 80%'
                }
            });
        }
    }

    animateCounter(element, target) {
        if (element.dataset.animated) return;
        element.dataset.animated = 'true';

        gsap.to({ value: 0 }, {
            value: target,
            duration: 2,
            ease: 'power2.out',
            onUpdate: function() {
                element.textContent = Math.round(this.targets()[0].value).toLocaleString('pt-BR');
            }
        });
    }

    setupBackgroundSync() {
        // Sync background 3D with scroll sections
        const sections = [
            { selector: '.section-timeline, .origem-section', theme: 'origin' },
            { selector: '.section-pillars, .pillars-grid', theme: 'pillars' },
            { selector: '.section-status, .status-grid', theme: 'status' },
            { selector: '.section-protocols', theme: 'protocols' },
            { selector: '.section-entities', theme: 'entities' },
            { selector: '.section-setis', theme: 'breach' }
        ];

        sections.forEach(({ selector, theme }) => {
            const section = document.querySelector(selector);
            if (!section) return;

            ScrollTrigger.create({
                trigger: section,
                start: 'top center',
                end: 'bottom center',
                onEnter: () => this.setBackgroundTheme(theme),
                onEnterBack: () => this.setBackgroundTheme(theme),
                onLeave: () => this.setBackgroundTheme('default'),
                onLeaveBack: () => this.setBackgroundTheme('default')
            });
        });
    }

    setBackgroundTheme(theme) {
        document.dispatchEvent(new CustomEvent('noir:bg-theme', {
            detail: { theme }
        }));
    }

    setupParallaxLayers() {
        const layers = document.querySelectorAll('[data-parallax]');
        layers.forEach(layer => {
            const speed = parseFloat(layer.dataset.parallax) || 0.3;
            gsap.to(layer, {
                yPercent: -50 * speed,
                ease: 'none',
                scrollTrigger: {
                    trigger: layer,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });
    }

    setupScrollProgress() {
        const progressBar = document.querySelector('.noir-scroll-progress');
        if (!progressBar) return;

        ScrollTrigger.create({
            start: 0,
            end: 'max',
            onUpdate: (self) => {
                progressBar.style.transform = `scaleX(${self.progress})`;
            }
        });
    }

    destroy() {
        this.timelines.forEach(tl => tl.kill());
        this.timelines = [];
        ScrollTrigger.getAll().forEach(st => st.kill());
    }
}

// Auto-init if not in module context
if (typeof window !== 'undefined' && !window.NOIR?.modules?.scrollNarrative) {
    document.addEventListener('DOMContentLoaded', () => {
        if (!window.NOIR?.config?.reducedMotion && !window.NOIR?.config?.isMobile) {
            window.NOIR = window.NOIR || {};
            window.NOIR.scrollNarrative = new ScrollNarrative();
        }
    });
}
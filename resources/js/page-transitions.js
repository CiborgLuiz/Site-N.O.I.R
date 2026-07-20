// Page Transitions - View Transitions API + Loading Bar

export class PageTransitions {
    constructor() {
        this.isTransitioning = false;
        this.init();
    }

    init() {
        if (!document.startViewTransition) {
            this.setupFallback();
            return;
        }

        this.setupViewTransitions();
        this.setupLoadingBar();
    }

    setupViewTransitions() {
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href^="/"], a[href^="./"], a[href^="../"]');
            if (!link) return;
            if (link.target === '_blank') return;
            if (link.hasAttribute('download')) return;
            if (link.href.includes('#')) return;

            const url = link.href;
            if (new URL(url).origin !== window.location.origin) return;

            e.preventDefault();
            this.navigate(url);
        });

        window.addEventListener('popstate', () => {
            this.navigate(window.location.href, false);
        });
    }

    async navigate(url, pushState = true) {
        if (this.isTransitioning) return;
        this.isTransitioning = true;

        const loader = this.showLoader(url);

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) throw new Error('Network error');

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newContent = doc.querySelector('[data-page-content]');
            const oldContent = document.querySelector('[data-page-content]');

            if (!newContent || !oldContent) {
                window.location.href = url;
                return;
            }

            if (pushState) {
                history.pushState(null, '', url);
            }

            document.title = doc.title;

            await this.transition(oldContent, newContent, loader);

            this.updateScripts(doc);
            this.updateStyles(doc);
            this.scrollToTop();
            this.dispatchPageLoad(url);

        } catch (err) {
            console.error('Transition failed:', err);
            window.location.href = url;
        } finally {
            this.hideLoader(loader);
            this.isTransitioning = false;
        }
    }

    async transition(oldContent, newContent, loader) {
        const transition = document.startViewTransition(async () => {
            oldContent.innerHTML = newContent.innerHTML;
            oldContent.dataset.page = newContent.dataset.page;
        });

        try {
            await transition.finished;
        } catch (e) {
            // Transition cancelled or failed
        }

        // Re-initialize page-specific JS
        this.reinitializePage(oldContent.dataset.page);
    }

    showLoader(url) {
        const pageName = new URL(url).pathname.split('/').pop() || 'home';
        const loader = document.createElement('div');
        loader.dataset.noirLoader = '';
        loader.innerHTML = `
            <div data-loader-page>CARREGANDO ${pageName.toUpperCase()}</div>
            <div data-loader-bar></div>
            <div data-loader-percent>0%</div>
            <div data-loader-status>Estabelecendo conexão segura...</div>
        `;
        document.body.appendChild(loader);

        // Animate progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            loader.querySelector('[data-loader-bar]').style.setProperty('--loader-progress', `${progress}%`);
            loader.querySelector('[data-loader-percent]').textContent = `${Math.round(progress)}%`;
        }, 100);

        loader.interval = interval;
        requestAnimationFrame(() => loader.classList.add('is-complete'));

        return loader;
    }

    hideLoader(loader) {
        clearInterval(loader.interval);
        loader.querySelector('[data-loader-bar]').style.setProperty('--loader-progress', '100%');
        loader.querySelector('[data-loader-percent]').textContent = '100%';
        loader.querySelector('[data-loader-status]').textContent = 'Acesso concedido. Bem-vindo, Operador.';

        setTimeout(() => {
            loader.classList.remove('is-complete');
            setTimeout(() => loader.remove(), 800);
        }, 400);
    }

    updateScripts(doc) {
        // Remove old page-specific scripts
        document.querySelectorAll('script[data-page-script]').forEach(s => s.remove());

        // Add new page-specific scripts
        doc.querySelectorAll('script[data-page-script]').forEach(script => {
            const newScript = document.createElement('script');
            if (script.src) {
                newScript.src = script.src;
            } else {
                newScript.textContent = script.textContent;
            }
            newScript.dataset.pageScript = script.dataset.pageScript;
            document.body.appendChild(newScript);
        });
    }

    updateStyles(doc) {
        // Update page-specific styles if needed
        doc.querySelectorAll('link[data-page-style]').forEach(link => {
            if (!document.querySelector(`link[href="${link.href}"]`)) {
                document.head.appendChild(link.cloneNode());
            }
        });
    }

    scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'instant' });
    }

    dispatchPageLoad(url) {
        document.dispatchEvent(new CustomEvent('noir:page-load', {
            detail: { url }
        }));
    }

    reinitializePage(page) {
        // Re-init engines based on page
        switch (page) {
            case 'protocolos':
                if (window.protocolsEngine) window.protocolsEngine.destroy();
                import('./protocols-engine.js').then(m => {
                    window.protocolsEngine = new m.ProtocolsEngine();
                });
                break;
            case 'entidades':
                if (window.entitiesEngine) window.entitiesEngine.destroy();
                import('./entities-engine.js').then(m => {
                    window.entitiesEngine = new m.EntitiesEngine();
                });
                break;
            case 'sistema':
                if (window.systemDesktop) window.systemDesktop.destroy();
                import('./system-desktop.js').then(m => {
                    window.systemDesktop = new m.SystemDesktop();
                });
                break;
            case 'terminal':
                if (window.terminalEngine) window.terminalEngine.destroy();
                import('./terminal-engine.js').then(m => {
                    window.terminalEngine = new m.TerminalEngine();
                });
                break;
        }
    }

    setupFallback() {
        // No View Transitions API - normal navigation
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && !link.target && !link.hasAttribute('download')) {
                // Let browser handle normally
            }
        });
    }
}

// Auto-init
if (typeof window !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        window.pageTransitions = new PageTransitions();
    });
}
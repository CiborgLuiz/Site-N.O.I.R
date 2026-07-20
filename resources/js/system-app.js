// System App Mode - Mobile version of sistema.blade.php

export class SystemApp {
    constructor() {
        this.apps = [
            { id: 'protocols', name: 'Protocolos', icon: '📋', desc: 'Arquivo de protocolos de contenção', route: '/protocolos' },
            { id: 'entities', name: 'Entidades', icon: '🧬', desc: 'Banco de dados de anomalias', route: '/entidades' },
            { id: 'files', name: 'Arquivos', icon: '📁', desc: 'Explorador de arquivos classificados', route: '/arquivos' },
            { id: 'terminal', name: 'Terminal', icon: '💻', desc: 'Shell N.O.I.R com acesso direto', route: '/terminal' },
            { id: 'organization', name: 'Organização', icon: '🏛️', desc: 'Estrutura operacional N.O.I.R', route: '/organizacao' },
            { id: 'classified', name: 'Classificados', icon: '🔒', desc: 'Acesso restrito - Clearance 5+', route: '/classificados', locked: true }
        ];
        this.init();
    }

    init() {
        this.setupDOM();
        this.bindEvents();
        this.checkClearance();
    }

    setupDOM() {
        const container = document.getElementById('system-app');
        if (!container) return;

        container.innerHTML = `
            <header class="system-app-header" style="
                position: sticky; top: 0; z-index: 100;
                background: rgba(10,10,10,0.95); backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(209,171,121,0.2);
                padding: 16px 20px;
            ">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="app-logo" style="
                            width: 40px; height: 40px; border-radius: 10px;
                            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-bright));
                            display: flex; align-items: center; justify-content: center;
                            font-size: 1.2rem; color: #0a0a0a; font-weight: bold;
                        ">N</div>
                        <div>
                            <h1 style="font-family: 'JetBrains Mono', monospace; font-size: 1.1rem; color: var(--accent-gold); margin: 0;">N.O.I.R OS</h1>
                            <p style="font-size: 0.7rem; color: var(--text-muted); margin: 0;">Sistema de Arquivo Móvel</p>
                        </div>
                    </div>
                    <div class="app-status" style="
                        font-family: 'JetBrains Mono', monospace; font-size: 0.65rem;
                        color: var(--accent-gold); opacity: 0.7;
                    ">ONLINE</div>
                </div>
            </header>

            <main class="system-app-main" style="padding: 20px;">
                <section class="app-section" style="margin-bottom: 32px;">
                    <h2 style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">APLICAÇÕES DISPONÍVEIS</h2>
                    <div class="apps-grid" id="apps-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;"></div>
                </section>

                <section class="app-section">
                    <h2 style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">WIDGET TESSERACT</h2>
                    <div class="tesseract-widget-container" style="
                        aspect-ratio: 16/9; border-radius: 12px; overflow: hidden;
                        background: #070a14; border: 1px solid rgba(209,171,121,0.2);
                        position: relative;
                    ">
                        <canvas id="tesseract-widget" class="system-wallpaper-canvas" style="width:100%;height:100%;display:block;"></canvas>
                        <div class="tesseract-quality-badge" id="tesseract-badge">SISTEMA</div>
                    </div>
                    <div class="widget-controls" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px;">
                        <button class="widget-btn" data-theme="system" style="background:rgba(0,212,255,0.2);border-color:#00d4ff;">SISTEMA</button>
                        <button class="widget-btn" data-theme="noir" style="background:rgba(209,171,121,0.2);border-color:var(--accent-gold);">NOIR</button>
                        <button class="widget-btn" data-theme="breach" style="background:rgba(231,76,60,0.2);border-color:#e74c3c;">BREACH</button>
                        <button class="widget-btn" data-theme="retro" style="background:rgba(0,255,65,0.2);border-color:#00ff41;">RETRO</button>
                    </div>
                    <div style="margin-top: 12px;">
                        <label style="display:flex;align-items:center;gap:12px;font-family:'JetBrains Mono',monospace;font-size:0.75rem;color:var(--text-muted);">
                            Intensidade:
                            <input type="range" id="widget-intensity" min="0" max="1" step="0.1" value="0.5" style="flex:1;accent-color:var(--accent-gold);">
                            <span id="intensity-value">50%</span>
                        </label>
                    </div>
                </section>

                <section class="app-section" style="margin-top: 32px; padding-top: 24px; border-top: 1px solid rgba(209,171,121,0.1);">
                    <h2 style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px;">STATUS DO SISTEMA</h2>
                    <div class="system-status-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <div class="status-item" style="background:rgba(0,0,0,0.4);border:1px solid rgba(209,171,121,0.2);border-radius:8px;padding:16px;">
                            <div style="font-family:'JetBrains Mono',monospace;font-size:0.65rem;color:var(--text-muted);margin-bottom:4px;">CLEARANCE ATUAL</div>
                            <div id="current-clearance" style="font-size:1.5rem;font-weight:bold;color:var(--accent-gold);">NÍVEL 1</div>
                        </div>
                        <div class="status-item" style="background:rgba(0,0,0,0.4);border:1px solid rgba(209,171,121,0.2);border-radius:8px;padding:16px;">
                            <div style="font-family:'JetBrains Mono',monospace;font-size:0.65rem;color:var(--text-muted);margin-bottom:4px;">INTEGRIDADE REALIDADE</div>
                            <div id="reality-integrity" style="font-size:1.5rem;font-weight:bold;color:#f39c12;">INSTÁVEL</div>
                        </div>
                        <div class="status-item" style="background:rgba(0,0,0,0.4);border:1px solid rgba(209,171,121,0.2);border-radius:8px;padding:16px;">
                            <div style="font-family:'JetBrains Mono',monospace;font-size:0.65rem;color:var(--text-muted);margin-bottom:4px;">ENTIDADES ATIVAS</div>
                            <div id="active-entities" style="font-size:1.5rem;font-weight:bold;color:var(--accent-gold);">47</div>
                        </div>
                        <div class="status-item" style="background:rgba(0,0,0,0.4);border:1px solid rgba(209,171,121,0.2);border-radius:8px;padding:16px;">
                            <div style="font-family:'JetBrains Mono',monospace;font-size:0.65rem;color:var(--text-muted);margin-bottom:4px;">PROTOCOLOS ATIVOS</div>
                            <div id="active-protocols" style="font-size:1.5rem;font-weight:bold;color:#2ecc71;">12</div>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="system-app-footer" style="
                position: sticky; bottom: 0; z-index: 100;
                background: rgba(10,10,10,0.95); backdrop-filter: blur(10px);
                border-top: 1px solid rgba(209,171,121,0.2);
                padding: 12px 20px;
                display: flex; justify-content: space-around;
            ">
                <button class="nav-btn active" data-nav="apps" style="background:none;border:none;color:var(--accent-gold);font-family:'JetBrains Mono',monospace;font-size:0.7rem;display:flex;flex-direction:column;align-items:center;gap:4px;padding:8px;cursor:pointer;">
                    <span>📱</span> Apps
                </button>
                <button class="nav-btn" data-nav="widget" style="background:none;border:none;color:var(--text-muted);font-family:'JetBrains Mono',monospace;font-size:0.7rem;display:flex;flex-direction:column;align-items:center;gap:4px;padding:8px;cursor:pointer;">
                    <span>🔮</span> Widget
                </button>
                <button class="nav-btn" data-nav="status" style="background:none;border:none;color:var(--text-muted);font-family:'JetBrains Mono',monospace;font-size:0.7rem;display:flex;flex-direction:column;align-items:center;gap:4px;padding:8px;cursor:pointer;">
                    <span>📊</span> Status
                </button>
            </footer>
        `;

        this.appsGrid = container.querySelector('#apps-grid');
        this.badge = container.querySelector('#tesseract-badge');
        this.intensityInput = container.querySelector('#widget-intensity');
        this.intensityValue = container.querySelector('#intensity-value');
        this.widgetCanvas = container.querySelector('#tesseract-widget');
        
        this.renderApps();
        this.initTesseractWidget();
    }

    renderApps() {
        if (!this.appsGrid) return;
        
        this.appsGrid.innerHTML = this.apps.map(app => `
            <button class="app-card ${app.locked ? 'locked' : ''}" data-route="${app.route}" style="
                background: rgba(0,0,0,0.4);
                border: 1px solid ${app.locked ? 'rgba(231,76,60,0.3)' : 'rgba(209,171,121,0.2)'};
                border-radius: 12px;
                padding: 20px 16px;
                cursor: ${app.locked ? 'not-allowed' : 'pointer'};
                display: flex; flex-direction: column; align-items: center; text-align: center;
                gap: 12px; transition: all 0.2s ease;
                opacity: ${app.locked ? '0.5' : '1'};
            " ${app.locked ? 'disabled' : ''}>
                <span style="font-size: 2.5rem;">${app.icon}</span>
                <div>
                    <h3 style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: var(--text-primary); margin: 0 0 4px;">${app.name}</h3>
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin: 0;">${app.desc}</p>
                    ${app.locked ? '<span style="font-size: 0.6rem; color: #e74c3c; font-family: monospace;">🔒 CLEARANCE 5</span>' : ''}
                </div>
            </button>
        `).join('');

        this.appsGrid.querySelectorAll('.app-card:not(.locked)').forEach(card => {
            card.addEventListener('click', () => {
                window.location.href = card.dataset.route;
            });
        });
    }

    initTesseractWidget() {
        if (!this.widgetCanvas || typeof TesseractWidget === 'undefined') {
            // Fallback: static gradient
            const ctx = this.widgetCanvas?.getContext('2d');
            if (ctx) {
                this.drawStaticWidget(ctx);
            }
            return;
        }

        this.widget = new TesseractWidget(this.widgetCanvas, { 
            theme: 'system',
            intensity: 0.5
        });

        // Theme buttons
        document.querySelectorAll('.widget-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.widget-btn').forEach(b => {
                    b.style.opacity = '0.6';
                    b.style.transform = 'scale(1)';
                });
                btn.style.opacity = '1';
                btn.style.transform = 'scale(1.05)';
                this.widget.setTheme(btn.dataset.theme);
                this.badge.textContent = btn.dataset.theme.toUpperCase();
            });
        });

        // Intensity
        this.intensityInput?.addEventListener('input', (e) => {
            const val = parseFloat(e.target.value);
            this.intensityValue.textContent = Math.round(val * 100) + '%';
            this.widget.setIntensity(val);
        });
    }

    drawStaticWidget(ctx) {
        const w = this.widgetCanvas.width = this.widgetCanvas.offsetWidth * 2;
        const h = this.widgetCanvas.height = this.widgetCanvas.offsetHeight * 2;
        ctx.scale(2, 2);
        
        // Background
        const grad = ctx.createRadialGradient(w/4, h/4, 0, w/4, h/4, w/2);
        grad.addColorStop(0, 'rgba(209,171,121,0.15)');
        grad.addColorStop(1, '#070a14');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, w/2, h/2);
        
        // Tesseract wireframe (simplified 2D projection)
        ctx.strokeStyle = 'rgba(209,171,121,0.4)';
        ctx.lineWidth = 1;
        const cx = w/4, cy = h/4, size = Math.min(w, h) * 0.25;
        
        // Outer cube
        ctx.beginPath();
        ctx.rect(cx - size, cy - size, size * 2, size * 2);
        ctx.stroke();
        
        // Inner cube
        ctx.beginPath();
        ctx.rect(cx - size*0.5, cy - size*0.5, size, size);
        ctx.stroke();
        
        // Connecting lines
        ctx.beginPath();
        [-size, size].forEach(dx => {
            [-size, size].forEach(dy => {
                ctx.moveTo(cx + dx, cy + dy);
                ctx.lineTo(cx + dx*0.5, cy + dy*0.5);
            });
        });
        ctx.stroke();
    }

    bindEvents() {
        // Nav buttons
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.nav-btn').forEach(b => {
                    b.classList.remove('active');
                    b.style.color = 'var(--text-muted)';
                });
                btn.classList.add('active');
                btn.style.color = 'var(--accent-gold)';
                
                // Scroll to section
                const sections = ['apps', 'widget', 'status'];
                const idx = sections.indexOf(btn.dataset.nav);
                const section = document.querySelectorAll('.app-section')[idx];
                section?.scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Check clearance periodically
        setInterval(() => this.checkClearance(), 30000
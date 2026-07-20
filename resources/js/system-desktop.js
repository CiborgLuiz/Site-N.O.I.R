// System Desktop Engine - Full desktop simulation for sistema.blade.php
// Desktop only (>= 768px). Mobile uses system-app.js

import { TerminalEngine } from './terminal-engine.js';
import { TesseractWidget } from './tesseract-widget.js';

export class SystemDesktop {
    constructor() {
        this.windows = new Map();
        this.windowIdCounter = 0;
        this.zIndexBase = 1000;
        this.activeWindow = null;
        this.tesseractWidget = null;
        this.terminalEngine = null;
        this.init();
    }

    init() {
        if (window.innerWidth < 768) {
            console.log('[SystemDesktop] Mobile detected, skipping desktop init');
            return;
        }

        this.setupDOM();
        this.initTesseractWidget();
        this.initTerminal();
        this.initStartMenu();
        this.initTaskbar();
        this.initContextMenu();
        this.bindGlobalEvents();
        this.initDesktopIcons();
    }

    setupDOM() {
        this.desktop = document.querySelector('.system-desktop');
        this.iconsContainer = document.querySelector('.desktop-icons');
        this.taskbar = document.querySelector('.system-taskbar');
        this.startBtn = document.querySelector('.start-btn');
        this.startMenu = document.querySelector('.start-menu');
        this.taskbarApps = document.querySelector('.taskbar-apps');
        this.clock = document.querySelector('.taskbar-clock');
        this.wallpaperCanvas = document.getElementById('tesseract-widget');

        this.updateClock();
        setInterval(() => this.updateClock(), 1000);
    }

    initTesseractWidget() {
        if (this.wallpaperCanvas && typeof TesseractWidget !== 'undefined') {
            this.tesseractWidget = new TesseractWidget('#tesseract-widget', {
                theme: 'system',
                intensity: 0.7
            });
        }
    }

    initTerminal() {
        const terminalContainer = document.querySelector('.terminal-container');
        if (terminalContainer && typeof TerminalEngine !== 'undefined') {
            this.terminalEngine = new TerminalEngine(terminalContainer);
            window.terminalEngine = this.terminalEngine;
        }
    }

    initDesktopIcons() {
        const icons = [
            { id: 'terminal', label: 'Terminal', icon: '>_', app: 'terminal' },
            { id: 'archivos', label: 'Arquivos', icon: '📁', app: 'archivos' },
            { id: 'protocolos', label: 'Protocolos', icon: '📋', app: 'protocolos' },
            { id: 'entidades', label: 'Entidades', icon: '👁', app: 'entidades' },
            { id: 'classificados', label: 'Classificados', icon: '🔒', app: 'classificados', locked: true }
        ];

        this.iconsContainer.innerHTML = icons.map(ic => `
            <div class="desktop-icon ${ic.locked ? 'locked' : ''}" data-app="${ic.app}" style="
                display: flex; flex-direction: column; align-items: center; gap: 6px;
                width: 100px; padding: 12px 8px; cursor: ${ic.locked ? 'not-allowed' : 'pointer'};
                opacity: ${ic.locked ? 0.5 : 1}; border-radius: 8px; transition: background 0.2s;
                user-select: none;
            " onmouseover="if(!this.classList.contains('locked'))this.style.background='rgba(209,171,121,0.1)'" onmouseout="if(!this.classList.contains('locked'))this.style.background='transparent'">
                <div style="
                    width: 56px; height: 56px; border-radius: 12px;
                    background: ${ic.locked ? 'rgba(231,76,60,0.1)' : 'rgba(209,171,121,0.1)'};
                    border: 1px solid ${ic.locked ? 'rgba(231,76,60,0.3)' : 'rgba(209,171,121,0.3)'};
                    display: flex; align-items: center; justify-content: center;
                    font-size: 1.5rem; color: ${ic.locked ? '#e74c3c' : 'var(--accent-gold)'};
                ">${ic.icon}</div>
                <span style="font-size: 0.7rem; text-align: center; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%;">${ic.label}</span>
            </div>
        `).join('');

        this.iconsContainer.querySelectorAll('.desktop-icon:not(.locked)').forEach(icon => {
            icon.addEventListener('dblclick', () => this.openApp(icon.dataset.app));
            icon.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectIcon(icon);
            });
            icon.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                this.showContextMenu(e.clientX, e.clientY, 'icon', icon.dataset.app);
            });
        });
    }

    selectIcon(icon) {
        this.iconsContainer.querySelectorAll('.desktop-icon').forEach(ic => {
            ic.style.background = 'transparent';
        });
        icon.style.background = 'rgba(209,171,121,0.15)';
        icon.style.borderRadius = '8px';
    }

    initStartMenu() {
        const apps = [
            { id: 'terminal', label: 'Terminal N.O.I.R', desc: 'Shell de acesso ao arquivo', icon: '>_' },
            { id: 'archivos', label: 'Gerenciador de Arquivos', desc: 'Navegue pelo arquivo classificado', icon: '📁' },
            { id: 'protocolos', label: 'Arquivo de Protocolos', desc: 'Protocolos de contenção por classe', icon: '📋' },
            { id: 'entidades', label: 'Banco de Entidades', desc: 'Dossiês de anomalias conhecidas', icon: '👁' },
            { id: 'classificados', label: 'Arquivos SETIS', desc: 'REQUER CLEARANCE 5', icon: '🔒', locked: true }
        ];

        this.startMenu.innerHTML = `
            <div class="start-menu-header" style="
                padding: 8px 12px; border-bottom: 1px solid rgba(209,171,121,0.2);
                margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;
            ">
                <span style="font-weight: bold; color: var(--accent-gold);">N.O.I.R OS</span>
                <span style="font-size: 0.65rem; color: var(--text-muted);">v1.0.0</span>
            </div>
            <div class="start-menu-apps" style="max-height: 300px; overflow-y: auto;">
                ${apps.map(app => `
                    <button class="start-menu-app ${app.locked ? 'locked' : ''}" data-app="${app.id}" style="
                        width: 100%; display: flex; align-items: center; gap: 12px;
                        padding: 10px 12px; background: transparent; border: none;
                        border-radius: 6px; color: ${app.locked ? '#e74c3c' : 'var(--text-primary)'};
                        text-align: left; cursor: ${app.locked ? 'not-allowed' : 'pointer'};
                        opacity: ${app.locked ? 0.6 : 1}; transition: background 0.15s;
                    " onmouseover="if(!this.classList.contains('locked'))this.style.background='rgba(209,171,121,0.1)'" onmouseout="if(!this.classList.contains('locked'))this.style.background='transparent'">
                        <span style="font-size: 1.25rem;">${app.icon}</span>
                        <div style="flex: 1;">
                            <div style="font-weight: 500; font-size: 0.8rem;">${app.label}</div>
                            <div style="font-size: 0.65rem; color: var(--text-muted);">${app.desc}</div>
                        </div>
                    </button>
                `).join('')}
            </div>
            <div class="start-menu-footer" style="
                margin-top: 12px; padding-top: 12px;
                border-top: 1px solid rgba(209,171,121,0.2);
                display: flex; gap: 8px;
            ">
                <button class="start-menu-btn" data-action="wallpaper" style="
                    flex: 1; padding: 8px; background: rgba(209,171,121,0.1);
                    border: 1px solid rgba(209,171,121,0.2); border-radius: 6px;
                    color: var(--accent-gold); font-size: 0.7rem; cursor: pointer;
                ">Wallpaper</button>
                <button class="start-menu-btn" data-action="settings" style="
                    flex: 1; padding: 8px; background: rgba(209,171,121,0.1);
                    border: 1px solid rgba(209,171,121,0.2); border-radius: 6px;
                    color: var(--accent-gold); font-size: 0.7rem; cursor: pointer;
                ">Configurações</button>
            </div>
        `;

        this.startMenu.querySelectorAll('.start-menu-app:not(.locked)').forEach(btn => {
            btn.addEventListener('click', () => {
                this.openApp(btn.dataset.app);
                this.closeStartMenu();
            });
        });

        this.startMenu.querySelector('[data-action="wallpaper"]').addEventListener('click', () => {
            this.openWallpaperSettings();
            this.closeStartMenu();
        });
    }

    toggleStartMenu() {
        const isHidden = this.startMenu.classList.contains('hidden');
        if (isHidden) {
            this.startMenu.classList.remove('hidden');
            requestAnimationFrame(() => {
                this.startMenu.style.opacity = '1';
                this.startMenu.style.visibility = 'visible';
                this.startMenu.style.transform = 'translateY(0)';
            });
        } else {
            this.closeStartMenu();
        }
    }

    closeStartMenu() {
        if (!this.startMenu.classList.contains('hidden')) {
            this.startMenu.style.opacity = '0';
            this.startMenu.style.visibility = 'hidden';
            this.startMenu.style.transform = 'translateY(10px)';
            setTimeout(() => this.startMenu.classList.add('hidden'), 200);
        }
    }

    initTaskbar() {
        this.startBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleStartMenu();
        });
    }

    initContextMenu() {
        this.contextMenu = document.createElement('div');
        this.contextMenu.className = 'context-menu hidden';
        this.contextMenu.style.cssText = `
            position: fixed; z-index: 10000;
            background: rgba(10,10,10,0.98); border: 1px solid rgba(209,171,121,0.3);
            border-radius: 8px; padding: 8px 0; min-width: 180px;
            opacity: 0; visibility: hidden; transform: scale(0.95);
            transition: all 0.15s ease; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem;
        `;
        document.body.appendChild(this.contextMenu);

        document.addEventListener('click', () => this.hideContextMenu());
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.hideContextMenu();
        });
    }

    showContextMenu(x, y, type, appId = null) {
        let items = [];

        if (type === 'desktop') {
            items = [
                { label: 'Novo Terminal', action: () => this.openApp('terminal') },
                { label: 'Configurações de Wallpaper', action: () => this.openWallpaperSettings() },
                { type: 'separator' },
                { label: 'Atualizar Desktop', action: () => this.refreshDesktop() }
            ];
        } else if (type === 'icon' && appId) {
            items = [
                { label: 'Abrir', action: () => this.openApp(appId) },
                { label: 'Fixar na Taskbar', action: () => this.pinToTaskbar(appId) }
            ];
        } else if (type === 'window') {
            const win = this.windows.get(appId);
            if (!win) return;
            items = [
                { label: win.minimized ? 'Restaurar' : 'Minimizar', action: () => this.toggleMinimize(appId) },
                { label: win.maximized ? 'Restaurar' : 'Maximizar', action: () => this.toggleMaximize(appId) },
                { type: 'separator' },
                { label: 'Fechar', action: () => this.closeWindow(appId), danger: true }
            ];
        }

        this.contextMenu.innerHTML = items.map(item => {
            if (item.type === 'separator') {
                return '<hr style="border: none; border-top: 1px solid rgba(209,171,121,0.2); margin: 6px 0;">';
            }
            return `<button class="context-menu-item ${item.danger ? 'danger' : ''}" style="
                width: 100%; padding: 8px 16px; background: transparent; border: none;
                color: ${item.danger ? '#e74c3c' : 'var(--text-primary)'}; text-align: left;
                cursor: pointer; font-family: inherit; font-size: inherit;
                transition: background 0.1s;
            " onmouseover="this.style.background='rgba(209,171,121,0.1)'" onmouseout="this.style.background='transparent'">${item.label}</button>`;
        }).join('');

        this.contextMenu.querySelectorAll('.context-menu-item').forEach((el, i) => {
            el.addEventListener('click', () => {
                items[i].action?.();
                this.hideContextMenu();
            });
        });

        const rect = this.contextMenu.getBoundingClientRect();
        const vw = window.innerWidth, vh = window.innerHeight;
        if (x + rect.width > vw) x = vw - rect.width - 10;
        if (y + rect.height > vh) y = vh - rect.height - 10;

        this.contextMenu.style.left = `${x}px`;
        this.contextMenu.style.top = `${y}px`;
        this.contextMenu.classList.remove('hidden');
        requestAnimationFrame(() => {
            this.contextMenu.style.opacity = '1';
            this.contextMenu.style.visibility = 'visible';
            this.contextMenu.style.transform = 'scale(1)';
        });
    }

    hideContextMenu() {
        if (!this.contextMenu.classList.contains('hidden')) {
            this.contextMenu.style.opacity = '0';
            this.contextMenu.style.visibility = 'hidden';
            this.contextMenu.style.transform = 'scale(0.95)';
            setTimeout(() => this.contextMenu.classList.add('hidden'), 150);
        }
    }

    bindGlobalEvents() {
        this.desktop?.addEventListener('click', () => {
            this.closeStartMenu();
            this.hideContextMenu();
            this.deselectAllIcons();
        });

        this.desktop?.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            this.showContextMenu(e.clientX, e.clientY, 'desktop');
        });

        document.addEventListener('mousedown', (e) => {
            const windowEl = e.target.closest('.system-window');
            if (windowEl) this.focusWindow(windowEl.dataset.id);
        });

        document.addEventListener('keydown', (e) => {
            if (e.metaKey || e.ctrlKey) {
                if (e.key === 't') {
                    e.preventDefault();
                    this.openApp('terminal');
                }
            }
        });
    }

    deselectAllIcons() {
        this.iconsContainer.querySelectorAll('.desktop-icon').forEach(ic => {
            ic.style.background = 'transparent';
        });
    }

    updateClock() {
        if (this.clock) {
            this.clock.textContent = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        }
    }

    // Window Management
    createWindow(appId, options = {}) {
        const id = `${appId}-${++this.windowIdCounter}`;
        const win = {
            id,
            appId,
            element: null,
            minimized: false,
            maximized: false,
            prevBounds: null,
            zIndex: this.zIndexBase++
        };
        this.windows.set(id, win);

        const templates = {
            terminal: () => this.createTerminalWindow(id, options),
            archivos: () => this.createArchivosWindow(id, options),
            protocolos: () => this.createProtocolosWindow(id, options),
            entidades: () => this.createEntidadesWindow(id, options),
            classificados: () => this.createClassificadosWindow(id, options),
            wallpaper: () => this.createWallpaperSettingsWindow(id, options)
        };
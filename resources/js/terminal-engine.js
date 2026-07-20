// Terminal CRT Engine - Realistic scanlines, phosphor, commands with lore

export class TerminalEngine {
    constructor(container = document.querySelector('.terminal-container, #terminal')) {
        this.container = container;
        this.history = [];
        this.historyIndex = -1;
        this.commands = new Map();
        this.outputBuffer = [];
        this.isProcessing = false;
        this.clearance = 1;
        this.init();
    }

    init() {
        if (!this.container) return;
        this.setupDOM();
        this.registerCommands();
        this.bindEvents();
        this.printWelcome();
    }

    setupDOM() {
        this.container.innerHTML = `
            <div class="terminal-crt">
                <div class="terminal-screen" role="log" aria-live="polite" aria-atomic="false"></div>
                <div class="terminal-input-line">
                    <span class="terminal-prompt">noir@archive:~$</span>
                    <input type="text" class="terminal-input" autocomplete="off" spellcheck="false" aria-label="Terminal input" autocapitalize="off">
                    <span class="terminal-cursor" aria-hidden="true">█</span>
                </div>
                <div class="terminal-autocomplete hidden" role="listbox" aria-label="Command suggestions"></div>
            </div>
        `;

        this.screen = this.container.querySelector('.terminal-screen');
        this.input = this.container.querySelector('.terminal-input');
        this.autocomplete = this.container.querySelector('.terminal-autocomplete');
        this.cursor = this.container.querySelector('.terminal-cursor');

        // Blink cursor
        setInterval(() => {
            if (!this.isProcessing) {
                this.cursor.style.opacity = this.cursor.style.opacity === '0' ? '1' : '0';
            }
        }, 530);
    }

    registerCommands() {
        const cmds = {
            help: {
                desc: 'Lista comandos disponíveis',
                exec: () => this.printHelp()
            },
            clear: {
                desc: 'Limpa a tela',
                exec: () => this.clear()
            },
            access: {
                desc: 'Tenta acessar nível de clearance [1-5]',
                exec: (args) => this.cmdAccess(args)
            },
            search: {
                desc: 'Busca no banco de dados [termo]',
                exec: (args) => this.cmdSearch(args)
            },
            protocol: {
                desc: 'Exibe protocolo [ID]',
                exec: (args) => this.cmdProtocol(args)
            },
            entity: {
                desc: 'Exibe entidade [código]',
                exec: (args) => this.cmdEntity(args)
            },
            clearance: {
                desc: 'Mostra clearance atual',
                exec: () => this.cmdClearance()
            },
            breach: {
                desc: 'ATIVA MODO BREACH SETIS (perigoso)',
                exec: () => this.cmdBreach()
            },
            retro: {
                desc: 'Ativa/desativa modo CRT retro',
                exec: () => this.cmdRetro()
            },
            whisper: {
                desc: 'Ativa/desativa modo whisper',
                exec: () => this.cmdWhisper()
            },
            wallpaper: {
                desc: 'Controla wallpaper tesseract [theme|intensity]',
                exec: (args) => this.cmdWallpaper(args)
            },
            whoami: {
                desc: 'Identidade do operador',
                exec: () => this.printLine('OPERADOR: CLASSIFICADO | CLEARANCE: 1 | SETOR: ARQUIVO')
            },
            date: {
                desc: 'Data/hora do sistema (Paris)',
                exec: () => this.printLine(new Date().toLocaleString('pt-BR', { timeZone: 'Europe/Paris' }))
            },
            uptime: {
                desc: 'Tempo de atividade do sistema',
                exec: () => this.printLine(`SISTEMA ONLINE: ${Math.floor(performance.now() / 1000)}s`)
            },
            echo: {
                desc: 'Repete argumentos',
                exec: (args) => this.printLine(args.join(' '))
            },
            history: {
                desc: 'Mostra histórico de comandos',
                exec: () => this.printHistory()
            }
        };

        Object.entries(cmds).forEach(([name, cmd]) => {
            this.commands.set(name, cmd);
        });
    }

    bindEvents() {
        this.input.addEventListener('keydown', (e) => this.handleKeydown(e));
        this.input.addEventListener('input', () => this.updateAutocomplete());
        this.container.addEventListener('click', () => this.input.focus());
        this.container.addEventListener('contextmenu', (e) => e.preventDefault());
    }

    handleKeydown(e) {
        switch (e.key) {
            case 'Enter':
                e.preventDefault();
                this.executeCommand(this.input.value.trim());
                this.input.value = '';
                this.hideAutocomplete();
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.navigateHistory(-1);
                break;
            case 'ArrowDown':
                e.preventDefault();
                this.navigateHistory(1);
                break;
            case 'Tab':
                e.preventDefault();
                this.completeCommand();
                break;
            case 'Escape':
                this.hideAutocomplete();
                this.input.value = '';
                break;
        }
    }

    navigateHistory(direction) {
        if (!this.history.length) return;

        this.historyIndex += direction;

        if (this.historyIndex < 0) {
            this.historyIndex = 0;
        } else if (this.historyIndex >= this.history.length) {
            this.historyIndex = this.history.length - 1;
            this.input.value = '';
            return;
        }

        this.input.value = this.history[this.historyIndex];
    }

    updateAutocomplete() {
        const value = this.input.value.toLowerCase().trim();
        if (!value) {
            this.hideAutocomplete();
            return;
        }

        const matches = Array.from(this.commands.keys())
            .filter(cmd => cmd.startsWith(value))
            .slice(0, 8);

        if (!matches.length) {
            this.hideAutocomplete();
            return;
        }

        this.autocomplete.innerHTML = matches.map(cmd =>
            `<div class="autocomplete-item" data-cmd="${cmd}" role="option">${cmd} <span class="cmd-desc">${this.escapeHtml(this.commands.get(cmd).desc)}</span></div>`
        ).join('');

        this.autocomplete.classList.remove('hidden');
    }

    hideAutocomplete() {
        this.autocomplete.classList.add('hidden');
    }

    completeCommand() {
        const item = this.autocomplete.querySelector('.autocomplete-item');
        if (item) {
            this.input.value = item.dataset.cmd + ' ';
            this.hideAutocomplete();
        }
    }

    async executeCommand(input) {
        if (!input) return;

        this.addToHistory(input);
        this.printLine(`<span class="terminal-prompt">noir@archive:~$</span> ${this.escapeHtml(input)}`);

        const [cmdName, ...args] = input.split(/\s+/);
        const command = this.commands.get(cmdName.toLowerCase());

        if (command) {
            this.isProcessing = true;
            this.cursor.style.opacity = '1';
            try {
                await command.exec(args);
            } catch (err) {
                this.printError(`ERRO: ${err.message}`);
            } finally {
                this.isProcessing = false;
            }
        } else {
            this.printError(`Comando não reconhecido: ${cmdName}. Digite 'help' para ajuda.`);
        }
    }

    addToHistory(cmd) {
        this.history.push(cmd);
        this.historyIndex = this.history.length;
    }

    printLine(html, animate = true) {
        const line = document.createElement('div');
        line.className = 'terminal-line';
        line.innerHTML = html;
        this.screen.appendChild(line);

        if (animate) {
            line.style.opacity = '0';
            line.style.transform = 'translateY(4px)';
            requestAnimationFrame(() => {
                line.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
                line.style.opacity = '1';
                line.style.transform = 'translateY(0)';
            });
        }

        this.scrollToBottom();
    }

    printError(msg) {
        this.printLine(`<span class="terminal-error">${this.escapeHtml(msg)}</span>`);
    }

    printSuccess(msg) {
        this.printLine(`<span class="terminal-success">${this.escapeHtml(msg)}</span>`);
    }

    printWarning(msg) {
        this.printLine(`<span class="terminal-warning">${this.escapeHtml(msg)}</span>`);
    }

    printInfo(msg) {
        this.printLine(`<span class="terminal-info">${this.escapeHtml(msg)}</span>`);
    }

    scrollToBottom() {
        this.screen.scrollTop = this.screen.scrollHeight;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    triggerGlitch() {
        document.body.classList.add('noir-logo-breach');
        setTimeout(() => document.body.classList.remove('noir-logo-breach'), 1000);
    }

    printWelcome() {
        const lines = [
            '<span class="terminal-header">╔══════════════════════════════════════════════════════════════╗</span>',
            '<span class="terminal-header">║                    N.O.I.R SHELL v1.0.0                       ║</span>',
            '<span class="terminal-header">║           NÚCLEO DE OPERAÇÕES E INVESTIGAÇÕES DE RUPTURAS      ║</span>',
            '<span class="terminal-header">╚══════════════════════════════════════════════════════════════╝</span>',
            '',
            '<span class="terminal-success">SISTEMA INICIALIZADO. CONEXÃO COM ARQUIVO CENTRAL ESTABELECIDA.</span>',
            '<span class="terminal-warning">AVISO: Acesso a dados classificados requer clearance apropriado.</span>',
            '',
            'Digite <span class="terminal-command">help</span> para lista de comandos.',
            ''
        ];

        lines.forEach((line, i) => {
            setTimeout(() => this.printLine(line, false), i * 50);
        });
    }

    printHelp() {
        const lines = ['<span class="terminal-header">COMANDOS DISPONÍVEIS:</span>', ''];

        const categories = {
            'SISTEMA': ['help', 'clear', 'whoami', 'date', 'uptime', 'history'],
            'ACESSO': ['access', 'clearance'],
            'BUSCA': ['search', 'protocol', 'entity'],
            'MODOS': ['breach', 'retro', 'whisper'],
            'WALLPAPER': ['wallpaper'],
            'OUTROS': ['echo']
        };

        Object.entries(categories).forEach(([cat, cmds]) => {
            lines.push(`<span class="terminal-category">${cat}:</span>`);
            cmds.forEach(cmd => {
                const c = this.commands.get(cmd);
                if (c) {
                    lines.push(`  <span class="terminal-command">${cmd}</span> - ${c.desc}`);
                }
            });
            lines.push('');
        });

        lines.push('<span class="terminal-warning">COMANDOS PERIGOSOS: breach (irreversível sem reinicialização)</span>');

        lines.forEach(line => this.printLine(line, false));
    }

    clear() {
        this.screen.innerHTML = '';
    }

    printHistory() {
        if (!this.history.length) {
            this.printLine('Histórico vazio.');
            return;
        }

        this.history.forEach((cmd, i) => {
            this.printLine(`${i + 1}  ${this.escapeHtml(cmd)}`, false);
        });
    }

    // Command implementations
    cmdAccess(args) {
        const level = parseInt(args[0]);
        if (!level || level < 1 || level > 5) {
            this.printError('Clearance deve ser entre 1 e 5.');
            return;
        }

        if (level > this.clearance) {
            this.printError(`ACESSO NEGADO. Clearance ${level} requer autorização superior.`);
            this.triggerGlitch();
            return;
        }

        this.clearance = level;
        this.printSuccess(`ACESSO AUTORIZADO: NÍVEL ${level}`);
        this.printLine(`Bem-vindo, Operador. Dados de clearance ${level} desbloqueados.`);

        if (level >= 4) {
            this.printWarning('⚠ CLEARANCE 4+: Protocolos existenciais acessíveis.');
        }
        if (level === 5) {
            this.printError('⚠⚠ CLEARANCE 5: ENTIDADES SETIS VISÍVEIS. PROCEDA COM EXTREMA CAUTELA.');
            document.body.classList.add('noir-setis-breach');
            setTimeout(() => document.body.classList.remove('noir-setis-breach'), 5000);
        }
    }

    cmdSearch(args) {
        const term = args.join(' ').toLowerCase();
        if (!term) {
            this.printError('Uso: search [termo]');
            return;
        }

        this.printInfo(`BUSCANDO: "${term}"...`);

        const results = [
            { type: 'PROTOCOLO', id: 'PROTOCOLO-042', desc: 'Contenção de Entidades Ísop' },
            { type: 'ENTIDADE', id: 'ENT-0734', desc: 'Classe Ísop - Observador Silencioso' },
            { type: 'ARQUIVO', id: 'ARQ-1992-001', desc: 'Relatório do Armageddon' },
            { type: 'PROTOCOLO', id: 'PROTOCOLO-256', desc: 'Resposta DENUS - Devastação Regional' },
            { type: 'ENTIDADE', id: 'ENT-000', desc: 'Fratura Primordial - Classe SETIS' }
        ].filter(r =>
            r.id.toLowerCase().includes(term) ||
            r.desc.toLowerCase().includes(term) ||
            r.type.toLowerCase().includes(term)
        );

        setTimeout(() => {
            if (!results.length) {
                this.printWarning('NENHUM RESULTADO ENCONTRADO.');
                return;
            }

            this.printSuccess(`${results.length} RESULTADO(S):`);
            results.forEach(r => {
                this.printLine(`  [${r.type}] <span class="terminal-command">${r.id}</span> - ${r.desc}`);
            });
        }, 800);
    }

    cmdProtocol(args) {
        const id = args[0]?.toUpperCase();
        if (!id) {
            this.printError('Uso: protocol [ID]');
            return;
        }

        this.printInfo(`ACESSANDO PROTOCOLO ${id}...`);

        setTimeout(() => {
            const isSetis = id.includes('SETIS') || id === 'PROTOCOLO-666' || id === 'PROTOCOLO-999';
            if (isSetis && this.clearance < 5) {
                this.printError('ACESSO NEGADO. CLEARANCE 5 NECESSÁRIO.');
                this.triggerGlitch();
                return;
            }

            this.printSuccess(`PROTOCOLO ${id} CARREGADO:`);
            this.printLine('┌─────────────────────────────────────────────────────────────┐');
            this.printLine(`│ CLASSIFICAÇÃO: ${id.includes('DENUS') ? 'DENUS' : id.includes('ISOP') ? 'ÍSOP' : id.includes('TOTIN') ? 'TOTIN' : 'SEGURO'.padEnd(10)} │`);
            this.printLine(`│ STATUS: ATIVO                                               │`);
            this.printLine(`│ RESPONSÁVEL: ${id.includes('SECURITY') ? 'N.O.I.R SECURITY' : id.includes('LABS') ? 'N.O.I.R LABS' : 'N.O.I.R CONTAINMENT'.padEnd(22)} │`);
            this.printLine(`│ DESCRIÇÃO: [DADOS EXPURGADOS - CLEARANCE ${isSetis ? '5' : '3'}+]                │`);
            this.printLine('└─────────────────────────────────────────────────────────────┘');
        }, 600);
    }

    cmdEntity(args) {
        const code = args[0]?.toUpperCase();
        if (!code) {
            this.printError('Uso: entity [CÓDIGO]');
            return;
        }

        this.printInfo(`ACESSANDO DOSSIÊ ${code}...`);

        setTimeout(()
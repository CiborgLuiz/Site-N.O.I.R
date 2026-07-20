// Protocols Engine for protocolos.blade.php

export class ProtocolsEngine {
    constructor() {
        this.protocols = [];
        this.filteredProtocols = [];
        this.searchTerm = '';
        this.init();
    }

    init() {
        this.loadProtocols();
        this.setupDOM();
        this.bindEvents();
        this.render();
    }

    loadProtocols() {
        this.protocols = [
            { id: 'PROTOCOLO-001', title: 'Contenção Padrão - Entidades Seguras', class: 'SEGURO', clearance: 1, status: 'ATIVO', responsible: 'N.O.I.R CONTAINMENT', desc: 'Procedimento padrão para contenção, monitoramento e estudo de entidades classificadas como SEGURO. Inclui protocolos de alimentação, observação e descarte seguro de resíduos anômalos.' },
            { id: 'PROTOCOLO-007', title: 'Neutralização Controlada', class: 'NEUTRALIZADO', clearance: 1, status: 'ARQUIVADO', responsible: 'N.O.I.R CONTAINMENT', desc: 'Protocolo histórico para neutralização de anomalias de baixo risco. Substituído por PROTOCOLO-001 em 1989. Mantido para referência histórica.' },
            { id: 'PROTOCOLO-042', title: 'Contenção de Entidades Ísop - Observadores', class: 'ÍSOP', clearance: 2, status: 'ATIVO', responsible: 'N.O.I.R CONTAINMENT', desc: 'Procedimentos especializados para entidades que reagem à observação. Requer equipes de contenção treinadas em "cegueira tática" e sistemas de monitoramento passivo não-óptico.' },
            { id: 'PROTOCOLO-055', title: 'Supressão de Informação - Nível 2', class: 'ÍSOP', clearance: 2, status: 'ATIVO', responsible: 'N.O.I.R SECURITY', desc: 'Protocolo de silenciamento de testemunhas civis e controle de mídia para incidentes Ísop. Inclui amnésicos Classe-B e fabricação de narrativas alternativas.' },
            { id: 'PROTOCOLO-108', title: 'Anomalias Contendo Anomalias (TOTIN)', class: 'TOTIN', clearance: 3, status: 'ATIVO', responsible: 'N.O.I.R LABS', desc: 'Framework para estudo e contenção de anomalias que funcionam como recipientes para outras anomalias. Requer análise recursiva de risco e isolamento dimensional em camadas.' },
            { id: 'PROTOCOLO-144', title: 'Comunicação Interdimensional', class: 'TOTIN', clearance: 3, status: 'EXPERIMENTAL', responsible: 'N.O.I.R LABS', desc: 'Tentativa de estabelecer canal de comunicação com inteligências da Dimensão 000. Alto risco de contaminação cognitiva. Apenas voluntários com clearance 3+ e implante de supressão memética.' },
            { id: 'PROTOCOLO-256', title: 'Resposta a Devastação Regional/Global (DENUS)', class: 'DENUS', clearance: 4, status: 'PRONTO', responsible: 'DIRETORIA CENTRAL', desc: 'Protocolo de emergência máxima para entidades DENUS. Autoriza evacuação em massa, cooperação militar internacional, uso de armamento não-convencional e supressão total de informação. Requer autorização de 2/3 da Diretoria.' },
            { id: 'PROTOCOLO-333', title: 'Estabilização de Fratura Dimensional', class: 'DENUS', clearance: 4, status: 'ATIVO', responsible: 'N.O.I.R LABS + CONTAINMENT', desc: 'Procedimento contínuo para manter a Fratura Primordial (ENT-000) dentro de parâmetros aceitáveis. Envolve âncoras de realidade, dampeners de Hume e sacrifício controlado de recursos dimensionais.' },
            { id: 'PROTOCOLO-666', title: '[EXPURGADO] - Contenção Entidades SETIS', class: 'SETIS', clearance: 5, status: 'CLASSIFICADO', responsible: 'DIRETORIA CENTRAL', desc: '[DADOS EXPURGADOS - ACESSO RESTRITO À DIRETORIA CENTRAL] Protocolo teórico para contenção de ameaças existenciais. Existência não confirmada oficialmente. Rumores sugerem envolvimento de tecnologia temporal e pactos com entidades não-humanas.' },
            { id: 'PROTOCOLO-999', title: 'Protocolo Omega - Fim do Mundo', class: 'SETIS', clearance: 5, status: 'TEÓRICO', responsible: 'DIRETORIA CENTRAL', desc: '[DADOS EXPURGADOS] Protocolo de último recurso caso a contenção falhe completamente. Envolve [EXPURGADO] e [EXPURGADO]. Não deve ser discutido fora da Sala de Decisão da Diretoria.' }
        ];
        this.filteredProtocols = [...this.protocols];
    }

    setupDOM() {
        const container = document.getElementById('protocols-engine');
        if (!container) return;

        container.innerHTML = `
            <div class="protocols-header" style="margin-bottom: 24px;">
                <h1 style="font-family: 'JetBrains Mono', monospace; color: var(--accent-gold); font-size: clamp(1.5rem, 5vw, 2.5rem); margin-bottom: 8px;">ARQUIVO DE PROTOCOLOS N.O.I.R</h1>
                <p style="color: var(--text-muted); max-width: 600px;">Classificação por nível de ameaça | Clearance necessário indicado | Protocolos SETIS requerem autorização Diretoria</p>
            </div>

            <div class="protocols-toolbar" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; align-items: center;">
                <div class="search-wrapper" style="flex: 1; min-width: 200px; max-width: 500px; position: relative;">
                    <input type="text" id="protocol-search" placeholder="Buscar protocolo por ID, título ou descrição..." style="
                        width: 100%; padding: 10px 16px 10px 40px;
                        background: rgba(0,0,0,0.5); border: 1px solid rgba(209,171,121,0.2);
                        border-radius: 8px; color: var(--text-primary);
                        font-family: 'JetBrains Mono', monospace; font-size: 0.85rem;
                    ">
                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);">🔍</span>
                </div>
                <div class="protocol-stats" style="display: flex; gap: 24px; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text-muted);">
                    <span>Total: <span id="protocol-total">${this.protocols.length}</span></span>
                    <span>Exibindo: <span id="protocol-showing">${this.filteredProtocols.length}</span></span>
                    <span>Ativos: <span id="protocol-active">${this.protocols.filter(p => p.status === 'ATIVO').length}</span></span>
                </div>
            </div>

            <div class="protocols-list" id="protocols-list" style="display: flex; flex-direction: column; gap: 12px;"></div>

            <!-- Modal -->
            <div class="protocol-modal hidden" id="protocol-modal" style="
                position: fixed; inset: 0; z-index: 10000;
                background: rgba(0,0,0,0.9); backdrop-filter: blur(8px);
                display: flex; align-items: center; justify-content: center;
                padding: 20px; opacity: 0; visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            ">
                <div class="modal-content" style="
                    background: rgba(10,10,10,0.98); border: 1px solid rgba(209,171,121,0.3);
                    border-radius: 12px; max-width: 800px; width: 100%; max-height: 90vh;
                    overflow-y: auto; transform: scale(0.95); transition: transform 0.3s ease;
                "></div>
            </div>
        `;

        this.list = container.querySelector('#protocols-list');
        this.modal = container.querySelector('#protocol-modal');
        this.modalContent = container.querySelector('.modal-content');
        this.showingEl = container.querySelector('#protocol-showing');
    }

    bindEvents() {
        const search = document.getElementById('protocol-search');
        search?.addEventListener('input', (e) => this.setSearch(e.target.value));

        this.modal?.addEventListener('click', (e) => {
            if (e.target === this.modal) this.closeModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal?.classList.contains('hidden')) {
                this.closeModal();
            }
        });
    }

    setSearch(term) {
        this.searchTerm = term.toLowerCase().trim();
        this.applyFilter();
    }

    applyFilter() {
        this.filteredProtocols = this.protocols.filter(p => {
            if (!this.searchTerm) return true;
            return p.id.toLowerCase().includes(this.searchTerm) ||
                p.title.toLowerCase().includes(this.searchTerm) ||
                p.desc.toLowerCase().includes(this.searchTerm) ||
                p.class.toLowerCase().includes(this.searchTerm) ||
                p.responsible.toLowerCase().includes(this.searchTerm);
        });
        this.render();
    }

    getClassColor(cls) {
        const colors = {
            'NEUTRALIZADO': '#6b7280',
            'SEGURO': '#2ecc71',
            'TOTIN': '#f39c12',
            'ÍSOP': '#9b59b6',
            'DENUS': '#e74c3c',
            'SETIS': '#c0392b'
        };
        return colors[cls] || '#6b7280';
    }

    getStatusColor(status) {
        const colors = {
            'ATIVO': '#2ecc71',
            'PRONTO': '#f39c12',
            'ARQUIVADO': '#6b7280',
            'EXPERIMENTAL': '#3498db',
            'CLASSIFICADO': '#e74c3c',
            'TEÓRICO': '#9b59b6'
        };
        return colors[status] || '#fff';
    }

    render() {
        if (!this.list) return;

        if (this.filteredProtocols.length === 0) {
            this.list.innerHTML = `
                <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">📋</div>
                    <p>Nenhum protocolo encontrado com a busca atual.</p>
                </div>
            `;
            this.showingEl.textContent = '0';
            return;
        }

        this.showingEl.textContent = this.filteredProtocols.length;

        this.list.innerHTML = this.filteredProtocols.map(p => `
            <article class="protocol-card" data-id="${p.id}" style="
                background: rgba(0,0,0,0.4);
                border: 1px solid ${this.getClassColor(p.class)}40;
                border-radius: 10px;
                overflow: hidden;
                transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
                cursor: pointer;
            " onmouseover="this.style.borderColor='${this.getClassColor(p.class)}';this.style.boxShadow='0 8px 30px rgba(0,0,0,0.3)';this.style.background='rgba(0,0,0,0.5)'" onmouseout="this.style.borderColor='${this.getClassColor(p.class)}40';this.style.boxShadow='none';this.style.background='rgba(0,0,0,0.4)'">
                <div class="protocol-header" style="
                    padding: 16px 20px;
                    background: linear-gradient(90deg, ${this.getClassColor(p.class)}20 0%, transparent 100%);
                    border-bottom: 1px solid ${this.getClassColor(p.class)}30;
                    display: flex; flex-wrap: wrap; align-items: center; gap: 12px;
                ">
                    <span class="protocol-id" style="
                        font-family: 'JetBrains Mono', monospace;
                        font-size: 0.9rem; font-weight: bold;
                        color: var(--accent-gold); letter-spacing: 0.5px;
                    ">${p.id}</span>
                    <span class="protocol-class" style="
                        font-size: 0.65rem; font-family: 'JetBrains Mono', monospace;
                        padding: 4px 12px; border-radius: 12px;
                        background: ${this.getClassColor(p.class)};
                        color: ${p.class === 'NEUTRALIZADO' || p.class === 'SEGURO' ? '#0a0a0a' : '#fff'};
                        font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;
                    ">${p.class}</span>
                    <span class="protocol-clearance" style="
                        font-size: 0.65rem; font-family: 'JetBrains Mono', monospace;
                        padding: 4px 12px; border-radius: 12px;
                        background: rgba(209,171,121,0.2);
                        color: var(--accent-gold); font-weight: bold;
                    ">CLEARANCE ${p.clearance}</span>
                    <span class="protocol-status" style="
                        font-size: 0.65rem; font-family: 'JetBrains Mono', monospace;
                        padding: 4px 12px; border-radius: 12px;
                        background: ${this.getStatusColor(p.status)}20;
                        color: ${this.getStatusColor(p.status)}; font-weight: bold;
                        margin-left: auto;
                    ">${p.status}</span>
                </div>
                <div class="protocol-body" style="padding: 16px 20px;">
                    <h3 class="protocol-title" style="
                        font-size: 1.1rem; font-weight: 600;
                        color: var(--text-primary); margin-bottom: 8px;
                    ">${p.title}</h3>
                    <div class="protocol-meta" style="
                        font-size: 0.75rem; color: var(--text-muted);
                        margin-bottom: 12px; font-family: 'JetBrains Mono', monospace;
                    ">RESPONSÁVEL: ${p.responsible}</div>
                    <p class="protocol-desc" style="
                        font-size: 0.85rem; color: var(--text-muted);
                        line-height: 1.6;
                    ">${p.desc.substring(0, 180)}${p.desc.length > 180 ? '...' : ''}</p>
                </div>
                <div class="protocol-footer" style="
                    padding: 12px 20px;
                    background: rgba(0,0,0,0.3);
                    border-top: 1px solid rgba(209,171,121,0.1);
                    display: flex; justify-content: space-between; align-items: center;
                ">
                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: var(--text-muted);">
                        ${p.class === 'SETIS' ? '⚠ ACESSO RESTRITO' : 'Clique para detalhes completos ▶'}
                    </span>
                    <span style="color: var(--accent-gold); font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;">▶</span>
                </div>
            </article>
        `).join('');

        this.list.querySelectorAll('.protocol-card').forEach(card => {
            card.addEventListener('click', () => this.openModal(card.dataset.id));
        });
    }

    openModal(id) {
        const protocol = this.protocols.find(p => p.id === id);
        if (!protocol) return;

        const isSetis = protocol.class === 'SETIS';
        const hasClearance = !isSetis || (window.terminalEngine && window.terminalEngine.clearance >= 5);

        this.modalContent.innerHTML = `
            <div class="modal-header" style="
                padding: 24px; border-bottom: 1px solid rgba(209,171,1
// Entities Engine for entidades.blade.php

export class EntitiesEngine {
    constructor() {
        this.entities = [];
        this.filteredEntities = [];
        this.activeFilter = 'all';
        this.searchTerm = '';
        this.init();
    }

    init() {
        this.loadEntities();
        this.setupDOM();
        this.bindEvents();
        this.render();
    }

    loadEntities() {
        this.entities = [
            { code: 'ENT-000', name: 'Fratura Primordial', class: 'SETIS', threat: 'EXISTENCIAL', containment: 'IMPOSSÍVEL', desc: 'A anomalia original. Uma rasgadura na realidade causada pelo experimento de 1992. Fonte de todas as outras anomalias. Não pode ser contida, apenas monitorada. Cresce 0.03% ao ano.', clearance: 5, location: 'Dimensão 000 / Overworld', status: 'ATIVA', notes: '[DADOS EXPURGADOS - APENAS DIRETORIA CENTRAL]' },
            { code: 'ENT-001', name: 'O Observador Silencioso', class: 'ÍSOP', threat: 'ALTA', containment: 'ESPECIALIZADA', desc: 'Entidade humanoide que só se move quando não observada. Velocidade impossível. Mata quebrando o pescoço ou arrancando a coluna. Requer contenção em câmara com 360° de cobertura visual ininterrupta.', clearance: 3, location: 'Sítio-07, Câmara 001', status: 'CONTIDA', notes: 'Equipe de contenção usa óculos de espelho reverso. Nenhum contato visual direto permitido.' },
            { code: 'ENT-042', name: 'A Biblioteca Infinita', class: 'TOTIN', threat: 'MÉDIA', containment: 'RECURSIVA', desc: 'Estrutura arquitetônica que contém cópias de si mesma recursivamente. Cada "livro" é uma realidade alternativa. Ler um livro transporta a consciência para aquela realidade. Perda de pessoal: 47.', clearance: 3, location: 'Sítio-19, Ala Profunda', status: 'CONTIDA', notes: 'Acesso apenas com autorização LABS + CONTAINMENT. Protocolo-144 ativo.' },
            { code: 'ENT-108', name: 'O Sussurro no Vazio', class: 'ÍSOP', threat: 'ALTA', containment: 'MEMÉTICA', desc: 'Entidade auditiva/memética. Quem ouve sua "voz" desenvolve compulsão de espalhar a mensagem. Transmissão por texto, áudio, pensamento. Contenção: isolamento acústico total + supressão memética.', clearance: 2, location: 'Sítio-03, Câmara Anecóica', status: 'CONTIDA', notes: 'Pessoal usa implantes de ruído branco. Protocolo-055 aplicado a vazamentos.' },
            { code: 'ENT-256', name: 'A Cidade que Respira', class: 'DENUS', threat: 'CATASTRÓFICA', containment: 'ZONA DE EXCLUSÃO', desc: 'Área urbana de 47km² que "respira" - expande/contrai ciclicamente. Edifícios crescem como órgãos. Habitantes fundidos à arquitetura. Qualquer entrada na zona = assimilação. Evacuação de 2.3 milhões em 1998.', clearance: 4, location: '[EXPURGADO], França', status: 'MONITORADA', notes: 'Protocolo-256 ativo. Perímetro de 100km. Satélites monitoram expansão.' },
            { code: 'ENT-333', name: 'Âncora de Realidade', class: 'DENUS', threat: 'CATASTRÓFICA', containment: 'ESTABILIZAÇÃO ATIVA', desc: 'Objeto/entidade que mantém a Fratura Primordial (ENT-000) semi-estável. Sem ela, a Fratura consumiria a realidade em 72h. Requer "alimentação" constante de energia dimensional. Custo: 3 operadores/mês (fatalidade).', clearance: 4, location: 'Sítio-00 (CLASSIFICADO)', status: 'ESTÁVEL', notes: 'Protocolo-333 em execução contínua. Falha = Cenário Omega.' },
            { code: 'ENT-404', name: 'O Arquivo que Não Existe', class: 'TOTIN', threat: 'MÉDIA', containment: 'DIGITAL', desc: 'Entrada no banco de dados que se auto-modifica. Aparece/desaparece aleatoriamente. Contém informações sobre anomalias não catalogadas. Tentativas de exclusão falham. Possível IA anômala ou vazamento dimensional.', clearance: 2, location: 'Servidor Central N.O.I.R', status: 'MONITORADA', notes: 'LABS investiga se é ferramenta ou ameaça. Não interagir diretamente.' },
            { code: 'ENT-666', name: 'O Homem de Preto', class: 'SETIS', threat: 'EXISTENCIAL', containment: 'IMPOSSÍVEL', desc: 'Figura humanoide vista em todos os incidentes maiores desde 1932. Não envelhece. Aparece/desaparece sem rastros. Possível avatar da Fratura ou entidade externa. Objetivos desconhecidos. Não hostil direto, mas eventos ao redor são catastróficos.', clearance: 5, location: 'DESCONHECIDA', status: 'NÃO CONTIDA', notes: '[DADOS EXPURGADOS] Avistamentos correlacionam com picos de atividade dimensional.' },
            { code: 'ENT-734', name: 'O Colecionador de Rostos', class: 'ÍSOP', threat: 'ALTA', containment: 'FÍSICA + MEMÉTICA', desc: 'Entidade que remove rostos de vítimas e os "usa". Vítimas permanecem vivas, sem rosto, em estado vegetativo. Entidade pode assumir aparência de qualquer rosto coletado. Contenção: câmara selada, alimentação por duto, zero contato visual.', clearance: 3, location: 'Sítio-07, Câmara 042', status: 'CONTIDA', notes: '12 rostos em posse. Protocolo de identificação facial obrigatório para pessoal.' },
            { code: 'ENT-891', name: 'A Chuva que Canta', class: 'SEGURO', threat: 'BAIXA', containment: 'PADRÃO', desc: 'Precipitação anômala que emite frequências musicais ao cair. Efeito calmante em humanos. Coletada para pesquisa de supressão memética. Não hostil. Classe SEGURO confirmada após 14 anos de estudo.', clearance: 1, location: 'Sítio-12, Laboratório de Acústica', status: 'ESTUDO', notes: 'Amostras distribuídas para pesquisa de amnésicos Classe-A aprimorados.' },
            { code: 'ENT-999', name: 'O Último Relatório', class: 'NEUTRALIZADO', threat: 'NENHUMA', containment: 'ARQUIVADO', desc: 'Documento que se reescrevia prevendo mortes de pessoal. Previu 847 mortes com 100% precisão. Neutralizado queimando o original + todas as cópias. Cinzas seladas em concreto. Nenhuma atividade desde 2003.', clearance: 1, location: 'Arquivo Morto, Sítio-01', status: 'NEUTRALIZADO', notes: 'Estudo de caso para detecção de anomalias preditivas.' }
        ];
        this.filteredEntities = [...this.entities];
    }

    setupDOM() {
        const container = document.getElementById('entities-engine');
        if (!container) return;

        container.innerHTML = `
            <div class="entities-header" style="margin-bottom: 24px;">
                <h1 style="font-family: 'JetBrains Mono', monospace; color: var(--accent-gold); font-size: clamp(1.5rem, 5vw, 2.5rem); margin-bottom: 8px;">BANCO DE DADOS DE ENTIDADES N.O.I.R</h1>
                <p style="color: var(--text-muted); max-width: 600px;">Classificação por nível de ameaça | Clearance necessário | Dossiês completos disponíveis mediante autorização</p>
            </div>

            <div class="entities-toolbar" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; align-items: center;">
                <div class="search-wrapper" style="flex: 1; min-width: 200px; max-width: 500px; position: relative;">
                    <input type="text" id="entity-search" placeholder="Buscar por código, nome, classe ou descrição..." style="
                        width: 100%; padding: 10px 16px 10px 40px;
                        background: rgba(0,0,0,0.5); border: 1px solid rgba(209,171,121,0.2);
                        border-radius: 8px; color: var(--text-primary);
                        font-family: 'JetBrains Mono', monospace; font-size: 0.85rem;
                    ">
                    <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);">🔍</span>
                </div>
                <div class="class-filters" style="display: flex; flex-wrap: wrap; gap: 8px;">
                    ${['all', 'NEUTRALIZADO', 'SEGURO', 'TOTIN', 'ÍSOP', 'DENUS', 'SETIS'].map(cls => `
                        <button class="filter-btn ${cls === 'all' ? 'active' : ''}" data-class="${cls}" style="
                            padding: 8px 16px; background: ${cls === 'all' ? 'rgba(209,171,121,0.2)' : 'rgba(0,0,0,0.4)'};
                            border: 1px solid ${this.getClassColor(cls)}40; border-radius: 20px;
                            color: ${cls === 'all' ? 'var(--accent-gold)' : this.getClassColor(cls)};
                            font-family: 'JetBrains Mono', monospace; font-size: 0.7rem;
                            font-weight: bold; text-transform: uppercase; cursor: pointer;
                            transition: all 0.2s;
                        ">${cls === 'all' ? 'TODAS' : cls}</button>
                    `).join('')}
                </div>
                <div class="entities-stats" style="display: flex; gap: 24px; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text-muted); margin-left: auto;">
                    <span>Total: <span id="entity-total">${this.entities.length}</span></span>
                    <span>Exibindo: <span id="entity-showing">${this.filteredEntities.length}</span></span>
                    <span>Contidas: <span id="entity-contained">${this.entities.filter(e => e.status === 'CONTIDA').length}</span></span>
                </div>
            </div>

            <div class="entities-grid" id="entities-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;"></div>

            <!-- Modal -->
            <div class="entity-modal hidden" id="entity-modal" style="
                position: fixed; inset: 0; z-index: 10000;
                background: rgba(0,0,0,0.95); backdrop-filter: blur(8px);
                display: flex; align-items: center; justify-content: center;
                padding: 20px; opacity: 0; visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            ">
                <div class="modal-content" style="
                    background: rgba(10,10,10,0.98); border: 1px solid rgba(209,171,121,0.3);
                    border-radius: 12px; max-width: 900px; width: 100%; max-height: 90vh;
                    overflow-y: auto; transform: scale(0.95); transition: transform 0.3s ease;
                "></div>
            </div>
        `;

        this.grid = container.querySelector('#entities-grid');
        this.modal = container.querySelector('#entity-modal');
        this.modalContent = container.querySelector('.modal-content');
        this.showingEl = container.querySelector('#entity-showing');
    }

    getClassColor(cls) {
        const colors = {
            'NEUTRALIZADO': '#6b7280',
            'SEGURO': '#2ecc71',
            'TOTIN': '#f39c12',
            'ÍSOP': '#9b59b6',
            'DENUS': '#e74c3c',
            'SETIS': '#c0392b',
            'all': '#d1ab79'
        };
        return colors[cls] || '#6b7280';
    }

    bindEvents() {
        const search = document.getElementById('entity-search');
        search?.addEventListener('input', (e) => this.setSearch(e.target.value));

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => this.setFilter(btn.dataset.class));
        });

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

    setFilter(cls) {
        this.activeFilter = cls;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.class === cls);
            if (btn.classList.contains('active')) {
                btn.style.background = 'rgba(209,171,121,0.2)';
                btn.style.color = 'var(--accent-gold)';
            } else {
                btn.style.background = 'rgba(0,0,0,0.4)';
                btn.style.color = this.getClassColor(btn.dataset.class);
            }
        });
        this.applyFilter();
    }

    applyFilter() {
        this.filteredEntities = this.entities.filter(e => {
            const matchesSearch = !this.searchTerm ||
                e.code.toLowerCase().includes(this.searchTerm) ||
                e.name.toLowerCase().includes(this.searchTerm) ||
                e.class.toLowerCase().includes(this.searchTerm) ||
                e.desc.toLowerCase().includes(this.searchTerm) ||
                e.location.toLowerCase().includes(this.searchTerm);

            const matchesFilter = this.activeFilter === 'all' || e.class === this.activeFilter;

            return matchesSearch && matchesFilter;
        });
        this.render();
    }

    hasClearance(level) {
        // In real app, check user session
        return level <= 1;
    }

    render() {
        if (!this.grid) return;

        if (this.filteredEntities.length === 0) {
            this.grid.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: var(--text-muted);">
                    <div style="font-size: 3rem; margin-bottom: 16px;">👁</div>
                    <p>Nenhuma entidade encontrada com os filtros atuais.</p>
                </div>
            `;
            this.showingEl.textContent = '0';
            return;
        }

        this.showingEl.textContent = this.filteredEntities.length;

        this.grid.innerHTML = this.filteredEntities.map(e => {
            const color = this.getClassColor(e.class);
            const isSetis = e.class === 'SETIS';
            const hasAccess = !isSetis || this.hasClearance(5);
            return `
                <article class="entity-card" data-code="${e.code}" style="
                    background: rgba(0,0,0,0.4);
                    border: 1px solid ${color}40;
                    border-radius: 12px;
                    overflow: hidden;
                    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
                    cursor: ${hasAccess ? 'pointer' : 'not-allowed'};
                    opacity: ${hasAccess ? 1 : 0.5};
                " onmouseover="if(this.style.opacity > 0.5){this.style.borderColor='${color}';this.style.boxShadow='0 8px 30px rgba(0,0,0
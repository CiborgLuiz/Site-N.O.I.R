@extends('layouts.app')

@section('page_title', 'Entidades')
@section('body_class', 'entities-page')
@section('head_css')
    @vite(['resources/css/home.css'])
@endsection

@section('content')
    <section class="hero">
        <h1>ENTIDADES</h1>
        <p class="hero-subtitle">
            CATÁLOGO • CLASSIFICAÇÃO • CONTENÇÃO
        </p>
        <div class="divider"></div>
        <p class="hero-description">
            Todo ser fora do padrão biológico conhecido é registrado.
            Toda entidade catalogada recebe uma classificação.
            Toda classificação determina seu destino.
        </p>
    </section>

    <section class="section">
        <div class="two-columns">
            <div>
                <h2>Banco de Entidades</h2>
                <p>
                    A N.O.I.R mantém um registro detalhado de todas as entidades
                    já detectadas, estudadas ou contidas. Cada entrada contém
                    dados de comportamento, nível de ameaça e protocolo de contenção.
                </p>
                <p>
                    Informações classificadas como <strong>SETIS</strong>
                    são acessíveis apenas pela Diretoria Central.
                </p>
            </div>

            <div class="status-box">
                <p><strong>BANCO DE DADOS:</strong> ATIVO</p>
                <p><strong>ENTIDADES REGISTRADAS:</strong> CLASSIFICADO</p>
                <p><strong>CLASSE MÁXIMA:</strong> SETIS</p>
                <p><strong>ACESSO:</strong> MONITORADO</p>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <h2>Classes de Entidades</h2>

        <div class="cards class-grid">
            <div class="card class-neutralizado">
                <h3>NEUTRALIZADO</h3>
                <p>Entidades completamente desativadas.</p>
                <p>Arquivadas apenas para registro histórico.</p>
                <p>Ação: Sem ação necessária. Monitore periodicamente.</p>
            </div>

            <div class="card class-seguro">
                <h3>SEGURO</h3>
                <p>Comportamento previsível.</p>
                <p>Contenção simples e estável.</p>
                <p>Ação: Manter em contenção de baixo nível.</p>
            </div>

            <div class="card class-totin">
                <h3>TOTIN</h3>
                <p>Anomalias utilizadas para conter outras anomalias.</p>
                <p>Existência restrita à Diretoria.</p>
                <p>Ação: Uso exclusivo aprovado pela Diretoria Central.</p>
            </div>

            <div class="card class-isop">
                <h3>ÍSOP</h3>
                <p>Imprevisíveis ou parcialmente compreendidos.</p>
                <p>Monitoramento constante obrigatório.</p>
                <p>Ação: Nível de vigilância elevado. Relatórios diários.</p>
            </div>

            <div class="card class-denus">
                <h3>DENUS</h3>
                <p>Dificuldade extrema de contenção.</p>
                <p>Potencial de devastação regional ou global.</p>
                <p>Ação: Contenção máxima. Equipes de campo em prontidão.</p>
            </div>

            <div class="card class-setis">
                <h3>SETIS</h3>
                <p>Impossíveis de conter.</p>
                <p>Ameaça existencial à realidade.</p>
                <p>Ação: Protocolo Existencial. Escalonamento imediato.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Registro de Entidades Recentes</h2>

        <div class="cards">
            <div class="card">
                <h3>ENTIDADE [CLASSIFICADO]</h3>
                <p>Classe: ÍSOP</p>
                <p>Status: Monitoramento ativo</p>
                <p>Última atualização: Registro criptografado</p>
            </div>

            <div class="card">
                <h3>ENTIDADE [CLASSIFICADO]</h3>
                <p>Classe: SEGURO</p>
                <p>Status: Contida</p>
                <p>Última atualização: Registro criptografado</p>
            </div>

            <div class="card">
                <h3>ENTIDADE [CLASSIFICADO]</h3>
                <p>Classe: DENUS</p>
                <p>Status: Em contenção</p>
                <p>Última atualização: Registro criptografado</p>
            </div>
        </div>
    </section>

    <section class="section section-warning">
        <h2>Aviso de Segurança</h2>
        <p>
            Dados detalhados sobre entidades são restritos.
            Informações não autorizadas podem comprometer
            operações de contenção em andamento.
        </p>
        <p>
            Caso tenha acessado sem autorização,
            seu acesso já foi registrado e será investigado.
        </p>

        <a href="sistema" class="btn">Acessar Sistema</a>
    </section>

    <div id="setis-alert">
        ⚠ NÍVEL DE RISCO MÁXIMO
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".card").forEach(card => {
                card.addEventListener("mouseenter", () => {
                    const cls = Array.from(card.classList).find(c => c.startsWith("class-"));
                    if (!cls) return;
                    const type = cls.replace("class-", "");
                    document.body.classList.add(type + "-active");
                });
                card.addEventListener("mouseleave", () => {
                    document.body.classList.remove(
                        "neutralizado-active", "seguro-active", "totin-active",
                        "isop-active", "denus-active", "setis-active"
                    );
                });
            });
        });
    </script>
@endsection

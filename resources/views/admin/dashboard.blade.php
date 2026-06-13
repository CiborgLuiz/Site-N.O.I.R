<!DOCTYPE html>
<html lang="pt-BR">
<head>
    
    <!-- Básico -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->

    <meta name="description" content="@yield('description', 'N.O.I.R é uma experiência única de Minecraft com mistérios, entidades, anomalias e eventos que desafiam a realidade.')">

    <meta name="keywords" content="Minecraft, N.O.I.R, SMP, Servidor Minecraft, Horror, Mistério, Survival, Modpack">
    <meta name="author" content="Equipe N.O.I.R">

    <!-- Open Graph (Discord, WhatsApp, Facebook, etc) -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="N.O.I.R">
    <meta property="og:title" content="@yield('og_title', 'N.O.I.R')">
    <meta property="og:description" content="@yield('og_description', 'Uma experiência de Minecraft onde a realidade nem sempre é o que parece.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/noir-preview.png') }}">

    <!-- Twitter/X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'N.O.I.R')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Uma experiência de Minecraft onde a realidade nem sempre é o que parece.')">
    <meta name="twitter:image" content="{{ asset('images/noir-preview.png') }}">

    <!-- Cor do navegador -->
    <meta name="theme-color" content="#4B0082">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonte -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilo Global dos Links -->
    <style>
        a {
            color: #9d6cff;
            text-decoration: none;
            transition: all .25s ease;
        }

        a:hover {
            color: #c3a6ff;
            text-shadow: 0 0 8px rgba(157,108,255,.6);
        }

        a:active {
            color: #ffffff;
        }

        a.special-link {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid rgba(157,108,255,.3);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            transition: all .25s ease;
        }

        a.special-link:hover {
            background: rgba(157,108,255,.15);
            border-color: #9d6cff;
            transform: translateY(-2px);
        }
    </style>
    @stack('head')
    <title>N.O.I.R - Painel Admin</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite('resources/css/admin.css')
</head>
<body
    class="admin-page noir-loading"
    data-upload-max-kb="{{ config('filesystems.upload_max_kb', 3072) }}"
    style="--noir-logo-image: url('{{ asset('images/logo.png') }}');"
>
    @include('partials.site-loader')

    <canvas id="noir-bg"></canvas>

    <header class="admin-topbar">
        <a href="{{ url('/home') }}" class="logo">N.O.I.R</a>
        <div class="admin-session">
            <span>{{ $admin->name }}</span>
            <span class="admin-role">{{ $admin->role === 'owner' ? 'DONO' : 'ADMIN' }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">Sair</button>
            </form>
        </div>
    </header>

    <main class="admin-shell">
        @php
            $activeTab = session('admin_tab', 'archives');

            if ($activeTab === 'keys' && ! $admin->isOwner()) {
                $activeTab = 'archives';
            }
        @endphp

        <section class="admin-hero">
            <div>
                <p class="admin-kicker">N.O.I.R // PAINEL INTERNO</p>
                <h1>Centro de inserção</h1>
                <p>Criação, integração e remoção de registros do sistema.</p>
            </div>
            <div class="admin-stat">
                <span data-admin-archive-count>{{ $archives->count() }}</span>
                <small>arquivos catalogados</small>
            </div>
        </section>

        <div class="admin-live-region" data-admin-live aria-live="polite">
            @if (session('status'))
                <div class="admin-flash">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-flash admin-flash-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <nav class="admin-tabs" aria-label="Seções do painel admin">
            <button type="button" data-admin-tab-target="archives">Inserir entidade</button>
            <button type="button" data-admin-tab-target="folders">Criar pasta</button>
            <button type="button" data-admin-tab-target="files">Inserir arquivo em pasta</button>

            @if ($admin->isOwner())
                <button type="button" data-admin-tab-target="keys">Gerar chave de admin</button>
            @endif
        </nav>

        <section class="admin-tab-panel" data-admin-tab-panel="archives">
            <article class="admin-panel">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Inserir arquivo de entidade</h2>
                        <p class="admin-muted">Cria um registro em `Archive` e envia a imagem para o storage de uploads.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.archives.store') }}" enctype="multipart/form-data" class="admin-form">
                    @csrf

                    <label>
                        Nome
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </label>

                    <label>
                        Identificador
                        <input type="text" name="identifier" value="{{ old('identifier') }}" required>
                    </label>

                    <label>
                        Classificação
                        <select name="classification" required>
                            @foreach ($classifications as $classification)
                                <option value="{{ $classification }}" @selected(old('classification') === $classification)>
                                    {{ strtoupper($classification) }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label>
                        Imagem da entidade
                        <input type="file" name="image" accept="image/png,image/jpeg,image/webp" data-max-upload-kb="{{ config('filesystems.upload_max_kb', 3072) }}" required>
                        <span class="admin-hint">Storage: {{ config('filesystems.uploads_disk') }} // limite {{ round(config('filesystems.upload_max_kb', 3072) / 1024, 1) }} MB</span>
                    </label>

                    <label class="admin-wide">
                        Descrição
                        <textarea name="description" rows="5" required>{{ old('description') }}</textarea>
                    </label>

                    <button type="submit" class="admin-button">Criar entidade</button>
                </form>
            </article>

            <article class="admin-panel">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Arquivos de entidade</h2>
                        <p class="admin-muted">Remover aqui também apaga a imagem enviada pelo painel.</p>
                    </div>
                </div>

                <div class="admin-folder-list" data-empty-message="Nenhuma entidade criada.">
                    @forelse ($archives as $archive)
                        <div class="admin-list-row admin-list-row-actions">
                            <div>
                                <strong>{{ $archive->identifier }} // {{ $archive->name }}</strong>
                                <span>{{ strtoupper($archive->classification) }}</span>
                                <small>{{ $archive->image_label }}</small>
                            </div>

                            <form method="POST" action="{{ route('admin.archives.destroy', $archive) }}" data-confirm-delete="Remover esta entidade e a imagem dela?" data-async-delete>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-danger-button">Remover</button>
                            </form>
                        </div>
                    @empty
                        <p class="admin-muted">Nenhuma entidade criada.</p>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="admin-tab-panel" data-admin-tab-panel="folders">
            <article class="admin-panel">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Criar pasta</h2>
                        <p class="admin-muted">Cria uma pasta em `Folder` usando o icone padrao `folder.png`.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.folders.store') }}" class="admin-form">
                    @csrf

                    <label>
                        Nome da pasta
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </label>

                    <label>
                        Icone
                        <input type="text" value="folder.png" readonly>
                    </label>

                    <button type="submit" class="admin-button">Criar pasta</button>
                </form>
            </article>

            <article class="admin-panel">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Pastas do sistema</h2>
                        <p class="admin-muted">Remover uma pasta apaga seus arquivos do banco e os uploads vinculados.</p>
                    </div>
                </div>

                <div class="admin-folder-list" data-empty-message="Nenhuma pasta criada.">
                    @forelse ($folders as $folder)
                        <div class="admin-list-row admin-list-row-actions">
                            <div>
                                <strong>{{ $folder->name }}</strong>
                                <span>{{ $folder->files->count() }} arquivos</span>
                            </div>

                            <form method="POST" action="{{ route('admin.folders.destroy', $folder) }}" data-confirm-delete="Remover esta pasta e todos os arquivos dela?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-danger-button">Remover</button>
                            </form>
                        </div>
                    @empty
                        <p class="admin-muted">Nenhuma pasta criada.</p>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="admin-tab-panel" data-admin-tab-panel="files">
            <article class="admin-panel">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Inserir arquivo em pasta</h2>
                        <p class="admin-muted">Integra TXT, PNG, MP3 ou MP4 no sistema interativo.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.files.store') }}" enctype="multipart/form-data" class="admin-form admin-file-form">
                    @csrf

                    <label>
                        Pasta
                        <select name="folder_id" required>
                            @foreach ($folders as $folder)
                                <option value="{{ $folder->id }}" @selected(old('folder_id') == $folder->id)>
                                    {{ $folder->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label>
                        Nome do arquivo
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </label>

                    <label>
                        Tipo
                        <select name="type" id="admin-file-type" required>
                            @foreach ($fileTypes as $type)
                                <option value="{{ $type }}" @selected(old('type', 'txt') === $type)>{{ strtoupper($type) }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="admin-wide admin-file-field" data-file-field="txt mp4">
                        Conteúdo
                        <textarea name="content" rows="6">{{ old('content') }}</textarea>
                        <span class="admin-hint">TXT salva texto. MP4 aceita embed do YouTube ou link normal do YouTube.</span>
                    </label>

                    <label class="admin-file-field" data-file-field="png">
                        Imagem do sistema
                        <input type="file" name="system_image" accept="image/png,image/jpeg,image/webp,image/gif" data-max-upload-kb="{{ config('filesystems.upload_max_kb', 3072) }}">
                        <span class="admin-hint">Storage: {{ config('filesystems.uploads_disk') }} // limite {{ round(config('filesystems.upload_max_kb', 3072) / 1024, 1) }} MB</span>
                    </label>

                    <label class="admin-file-field" data-file-field="mp3">
                        Audio do sistema
                        <input type="file" name="system_audio" accept="audio/mpeg,audio/wav,audio/ogg" data-max-upload-kb="{{ config('filesystems.upload_max_kb', 3072) }}">
                        <span class="admin-hint">Storage: {{ config('filesystems.uploads_disk') }} // limite {{ round(config('filesystems.upload_max_kb', 3072) / 1024, 1) }} MB</span>
                    </label>

                    <button type="submit" class="admin-button" @disabled($folders->isEmpty())>Inserir arquivo</button>
                </form>
            </article>

            <article class="admin-panel">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Arquivos nas pastas</h2>
                        <p class="admin-muted">Remover PNG ou MP3 também apaga o upload salvo pelo painel.</p>
                    </div>
                </div>

                <div class="admin-folder-list" data-empty-message="Nenhuma pasta criada.">
                    @forelse ($folders as $folder)
                        <div class="admin-folder-block" data-empty-message="Pasta sem arquivos.">
                            <strong>{{ $folder->name }}</strong>

                            @forelse ($folder->files as $file)
                                <div class="admin-list-row admin-list-row-actions admin-file-row">
                                    <div>
                                        <span>{{ strtoupper($file->type) }} // {{ $file->name }}</span>
                                        <small>{{ $file->path_label ?: \Illuminate\Support\Str::limit($file->content, 90) }}</small>
                                    </div>

                                    <form method="POST" action="{{ route('admin.files.destroy', $file) }}" data-confirm-delete="Remover este arquivo da pasta?" data-async-delete>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-danger-button">Remover</button>
                                    </form>
                                </div>
                            @empty
                                <p class="admin-muted">Pasta sem arquivos.</p>
                            @endforelse
                        </div>
                    @empty
                        <p class="admin-muted">Nenhuma pasta criada.</p>
                    @endforelse
                </div>
            </article>
        </section>

        @if ($admin->isOwner())
            <section class="admin-tab-panel" data-admin-tab-panel="keys">
                <article class="admin-panel">
                    <div class="admin-panel-heading">
                        <div>
                            <h2>Gerar chave de admin</h2>
                            <p class="admin-muted">A chave aparece uma vez, define o cargo ADMIN e expira no primeiro cadastro.</p>
                        </div>
                    </div>

                    @if (session('generated_admin_key'))
                        <div class="admin-key-output">
                            <label>
                                Chave gerada
                                <input type="text" value="{{ session('generated_admin_key') }}" readonly>
                            </label>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.keys.store') }}" class="admin-form">
                        @csrf

                        <label>
                            Cargo
                            <select name="role" required>
                                <option value="admin">ADMIN</option>
                            </select>
                        </label>

                        <button type="submit" class="admin-button">Gerar chave</button>
                    </form>
                </article>

                <article class="admin-panel">
                    <div class="admin-panel-heading">
                        <div>
                            <h2>Contas administrativas</h2>
                            <p class="admin-muted">O dono pode desabilitar outros admins sem remover o histórico.</p>
                        </div>
                    </div>

                    <div class="admin-account-list">
                        @foreach ($adminAccounts as $account)
                            <div class="admin-account-row">
                                <div>
                                    <strong>{{ $account->name }}</strong>
                                    <span>{{ $account->email }}</span>
                                </div>
                                <span class="admin-role">{{ $account->role === 'owner' ? 'DONO' : 'ADMIN' }}</span>
                                <span class="{{ $account->active ? 'admin-active' : 'admin-disabled' }}">
                                    {{ $account->active ? 'ATIVO' : 'DESABILITADO' }}
                                </span>

                                @if (! $account->isOwner() && $account->id !== $admin->id)
                                    <form method="POST" action="{{ $account->active ? route('admin.accounts.disable', $account) : route('admin.accounts.enable', $account) }}">
                                        @csrf
                                        <button type="submit">
                                            {{ $account->active ? 'Desabilitar' : 'Reativar' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="admin-panel">
                    <div class="admin-panel-heading">
                        <div>
                            <h2>Chaves recentes</h2>
                            <p class="admin-muted">As chaves usadas nao podem ser reaproveitadas.</p>
                        </div>
                    </div>

                    <div class="admin-folder-list" data-empty-message="Nenhuma chave criada.">
                        @forelse ($inviteKeys as $key)
                            <div class="admin-list-row">
                                <strong>{{ strtoupper($key->role) }}</strong>
                                <span>{{ $key->isAvailable() ? 'DISPONIVEL' : 'USADA' }}</span>
                                <span>{{ optional($key->usedBy)->email ?? 'sem uso' }}</span>
                            </div>
                        @empty
                            <p class="admin-muted">Nenhuma chave criada.</p>
                        @endforelse
                    </div>
                </article>
            </section>
        @endif
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const initialTab = @js($activeTab);
            const tabButtons = document.querySelectorAll('[data-admin-tab-target]');
            const tabPanels = document.querySelectorAll('[data-admin-tab-panel]');
            const typeSelect = document.getElementById('admin-file-type');
            const fields = document.querySelectorAll('[data-file-field]');
            const maxUploadKb = Number(document.body.dataset.uploadMaxKb || 3072);

            function activateTab(tabName, updateHash = false) {
                const hasPanel = Array.from(tabPanels).some((panel) => panel.dataset.adminTabPanel === tabName);
                const nextTab = hasPanel ? tabName : 'archives';

                tabButtons.forEach((button) => {
                    const active = button.dataset.adminTabTarget === nextTab;
                    button.classList.toggle('is-active', active);
                    button.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                tabPanels.forEach((panel) => {
                    panel.hidden = panel.dataset.adminTabPanel !== nextTab;
                });

                if (updateHash) {
                    history.replaceState(null, '', `#${nextTab}`);
                }
            }

            function syncFileFields() {
                const type = typeSelect ? typeSelect.value : 'txt';

                fields.forEach((field) => {
                    const enabled = field.dataset.fileField.split(' ').includes(type);
                    field.hidden = !enabled;

                    field.querySelectorAll('input, textarea').forEach((input) => {
                        input.disabled = !enabled;
                    });
                });
            }

            tabButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    activateTab(button.dataset.adminTabTarget, true);
                });
            });

            function showAdminNotice(message, isError = false) {
                const liveRegion = document.querySelector('[data-admin-live]');

                if (! liveRegion) {
                    return;
                }

                liveRegion.innerHTML = '';

                const notice = document.createElement('div');
                notice.className = `admin-flash${isError ? ' admin-flash-error' : ''}`;
                notice.textContent = message;
                liveRegion.appendChild(notice);
            }

            function updateEmptyState(list) {
                if (! list || ! list.dataset.emptyMessage) {
                    return;
                }

                const hasRows = list.querySelector('.admin-list-row, .admin-folder-block');

                if (! hasRows) {
                    const empty = document.createElement('p');
                    empty.className = 'admin-muted';
                    empty.textContent = list.dataset.emptyMessage;
                    list.appendChild(empty);
                }
            }

            async function submitDeleteForm(form) {
                const button = form.querySelector('button[type="submit"]');
                const row = form.closest('.admin-list-row');
                const list = row ? row.closest('.admin-folder-list') : null;
                const emptyStateContainer = row ? row.closest('.admin-folder-block') || list : null;

                if (button) {
                    button.disabled = true;
                    button.textContent = 'Removendo...';
                }

                if (row) {
                    row.classList.add('is-removing');
                }

                try {
                    const response = await fetch(form.action, {
                        method: form.method.toUpperCase(),
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (! response.ok) {
                        throw new Error('delete-failed');
                    }

                    const contentType = response.headers.get('content-type') || '';
                    const payload = contentType.includes('application/json') ? await response.json() : {};

                    if (row) {
                        row.classList.add('is-removed');
                        window.setTimeout(() => {
                            row.remove();
                            updateEmptyState(emptyStateContainer);
                        }, 180);
                    }

                    if (Number.isInteger(payload.archive_count)) {
                        document.querySelectorAll('[data-admin-archive-count]').forEach((counter) => {
                            counter.textContent = payload.archive_count;
                        });
                    }

                    showAdminNotice(payload.message || 'Registro removido.');
                } catch (error) {
                    if (row) {
                        row.classList.remove('is-removing');
                    }

                    if (button) {
                        button.disabled = false;
                        button.textContent = 'Remover';
                    }

                    showAdminNotice('Nao foi possivel remover sem recarregar. Tente novamente.', true);
                }
            }

            document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (! window.confirm(form.dataset.confirmDelete)) {
                        event.preventDefault();
                        return;
                    }

                    if (! window.fetch || ! window.FormData) {
                        return;
                    }

                    if (! form.hasAttribute('data-async-delete')) {
                        return;
                    }

                    event.preventDefault();
                    submitDeleteForm(form);
                });
            });

            document.querySelectorAll('input[type="file"][data-max-upload-kb]').forEach((input) => {
                input.addEventListener('change', () => {
                    const maxKb = Number(input.dataset.maxUploadKb || maxUploadKb);
                    const file = input.files && input.files[0];

                    if (! file || file.size <= maxKb * 1024) {
                        return;
                    }

                    input.value = '';
                    showAdminNotice(`Arquivo acima do limite de ${(maxKb / 1024).toFixed(1)} MB para deploy no Vercel.`, true);
                });
            });

            if (typeSelect) {
                typeSelect.addEventListener('change', syncFileFields);
                syncFileFields();
            }

            activateTab(window.location.hash.replace('#', '') || initialTab);
        });
    </script>

    @vite('resources/js/site.js')
    @vite('resources/js/noir-bg.js')
</body>
</html>

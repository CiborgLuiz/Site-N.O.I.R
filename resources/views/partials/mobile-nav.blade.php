@php
    $current = request()->path();
@endphp

<input type="checkbox" id="nav-toggle" class="nav-toggle" hidden>
<label for="nav-toggle" class="nav-toggle-label" aria-label="Abrir menu">
    <span></span>
    <span></span>
    <span></span>
</label>

<ul class="nav-links">
    <li><a href="{{ url('home') }}" class="{{ str_starts_with($current, 'home') ? 'nav-accent' : '' }}">Início</a></li>
    <li><a href="{{ url('organizacao') }}" class="{{ str_starts_with($current, 'organizacao') ? 'nav-accent' : '' }}">A Organização</a></li>
    <li><a href="{{ url('protocolos') }}" class="{{ str_starts_with($current, 'protocolos') ? 'nav-accent' : '' }}">Protocolos</a></li>
    <li><a href="{{ url('entidades') }}" class="{{ str_starts_with($current, 'entidades') ? 'nav-accent' : '' }}">Entidades</a></li>
    <li><a href="{{ url('arquivos') }}" class="{{ str_starts_with($current, 'arquivos') ? 'nav-accent' : '' }}">Arquivos</a></li>
    <li><a href="{{ url('sistema') }}" class="{{ str_starts_with($current, 'sistema') ? 'nav-accent' : '' }}">Acessar Sistema</a></li>
</ul>

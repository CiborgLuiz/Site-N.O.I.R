@php
    $current = request()->path();
@endphp

<header class="navbar">
    <div class="nav-container">
        <div class="logo">N.O.I.R</div>

        @include('partials.mobile-nav')
    </div>
</header>

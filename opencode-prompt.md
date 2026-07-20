# TRANSFORMAÇÃO VISUAL NOIR-SITE + WIDGET TESERACT 3D NO SISTEMA OPERACIONAL

## CONTEXTO
Stack: Laravel 11 + Vite + Tailwind v4 + Three.js + GSAP ScrollTrigger
Lore N.O.I.R.: SCP-style, documentos classificados, 4 pilares, 6 classes entidade, Dimensão 000

---

## TAREFA 1: MELHORAR `noir-bg.js` (FUNDO GERAL DO SITE) — OPCIONAL/EXPERIMENTAL

**Objetivo**: Evoluir o canvas 2D particles atual → **Three.js leve** só se ganho visual/performance justificar.

### Especificação (`resources/js/noir-bg.js` → novo `noir-background.js`)
- **Full-screen fixed canvas** (z-index: -1, behind content)
- **Opção A (Leve - Default)**: Shader único full-screen quad:
  - Scanlines + film grain + chromatic aberration sutil
  - Partículas GPU (points) com vertex shader — 2-5k pontos
  - Mouse parallax leve (0.5% viewport)
  - Scroll Y → uniform `uTime` + `uScroll` para distorção sutil
  - **Zero geometria 3D complexa** — só shader 2D + points
- **Opção B (Rica - Desktop only)**: Ativar via `data-bg-enhanced="true"` no body:
  - Tesseract wireframe rotacionando **muito lento** (background profundo)
  - Partículas 3D conectadas por linhas (buffer geometry dinâmico)
  - Só carrega se: `!prefersReducedMotion` && `screen.width > 1024` && `navigator.deviceMemory > 4`
- **Fallback gracioso**: Se WebGL2 falhar ou FPS < 30 → volta pro canvas 2D atual otimizado
- **Controle**: `window.NOIR_BG.setQuality('low'|'high'|'auto')`, `.pause()`, `.resume()`

### Mobile Specifics
- `prefers-reduced-motion` → **desliga tudo**, deixa cor sólida `#0a0a0a`
- Touch: sem parallax mouse, scroll Y ainda alimenta shader
- Battery API: se `navigator.getBattery().charging === false` && `level < 0.2` → force low quality
- Viewport units fix: `100dvh` / `100svh` para mobile browser chrome

---

## TAREFA 2: WIDGET TESERACT 3D — `noir-background-3d.js` (APENAS SISTEMA OPERACIONAL)

**Local**: `sistema.blade.php` — dentro do desktop simulado, **substituindo a imagem Windows XP**.

### Especificação (`resources/js/tesseract-widget.js`)
```javascript
// Classe TesseractWidget
// - Canvas contido no elemento .system-wallpaper (aspect-ratio: 16/9, max-h: 60vh)
// - Three.js scene isolada (não compartilha com fundo do site)
// - Geometria: Tesseract (hypercube 4D) wireframe + faces semi-transparentes
// - Rotação 4D real: dois planos ortogonais (XY + ZW) projetados em 3D
// - Shader custom (GLSL):
//   * Fresnel glow nas arestas (gold/rose NOIR)
//   * Pulse sutil (sin(time * 0.3))
//   * Grid floor reflexivo sutil
//   * Scanlines opcionais (tema 'retro')
// - Interação:
//   * Mouse move dentro do widget → parallax camera (max 5°)
//   * Click → "ping" ripple shader
//   * Hover ícones desktop → widget reage (brilho sutil)
// - API:
//   .setTheme('noir'|'system'|'breach'|'retro')
//   .setIntensity(0-1) // velocidade rotação + densidade partículas
//   .pause() / .resume() // Page Visibility API auto
//   .destroy() // cleanup Three.js
```

### Temas do Widget
| Tema | Paleta | Uso |
|------|--------|-----|
| `noir` | Gold `#d1ab79`, Rose `#8d7482`, scanlines leves | Padrão |
| `system` | Cyan `#00d4ff`, Blue `#0066ff`, grid holográfico | Desktop "OS" |
| `breach` | Red `#e74c3c`, glitch intenso, particles escaping | Terminal `breach` |
| `retro` | Phosphor `#00ff41`, scanlines pesadas, curvatura CRT | Terminal `retro` |

### Integração no `sistema.blade.php`
```blade
<div class="system-wallpaper" id="system-wallpaper">
    <canvas id="tesseract-widget"></canvas>
    <!-- Ícones desktop por cima (z-index: 10) -->
</div>
```
- Inicializar após DOM ready: `new TesseractWidget('#tesseract-widget', { theme: 'system' })`
- Terminal commands: `wallpaper theme [noir|system|breach|retro]`, `wallpaper intensity [0-1]`

### Mobile no Sistema Operacional
- Widget **não renderiza** em mobile (`< 768px`) → mostra wallpaper estático (imagem otimizada WebP)
- Ou: versão ultra-leve (só shader 2D animado, sem Three.js) via dynamic import condicional
- Desktop sim em mobile → layout stacked, não grid; ícones full-width cards

---

## TAREFA 3: MOBILE FIRST — EXPERIÊNCIA COMPLETA

### Navegação & Layout
- **Hamburger menu** animado (GSAP) → drawer lateral com blur backdrop
- **Hero mobile**: título menor, typewriter mais rápido, scroll indicator touch-friendly (swipe up hint)
- **Sections**: stack vertical, padding responsivo (`clamp(1rem, 5vw, 3rem)`)
- **Cards 4 Pilares**: carousel swipe (touch) em vez de grid 2x2 — `ScrollTrigger` horizontal ou Swiper.js leve
- **Tabelas (Protocolos/Entidades)**: horizontal scroll com sticky first column, ou card layout mobile

### Interações Touch
- Hover states → **active/touch states** (`@media (hover: none)`)
- 3D tilt cards → **gyroscope parallax** (DeviceOrientation API) se disponível, senão touch drag
- Modal/drawers: swipe down para fechar, backdrop tap para fechar
- Terminal: virtual keyboard aware (viewport resize), autocomplete touch-friendly

### Performance Mobile
- **Critical CSS inline** no `<head>` (Vite plugin)
- **Lazy-load Three.js**: `import('./noir-background.js')` só após `IntersectionObserver` hero visible
- **Code splitting**: cada page JS chunk separado (`protocolos.js`, `entidades.js`, `sistema.js`)
- **Image optimization**: WebP/AVIF via Vite imagetools, `srcset` responsivo, `loading="lazy"`
- **Service Worker**: Workbox precache critical assets, runtime cache API calls
- **Bundle target**: `< 50KB gzipped` JS crítico inicial; Three.js chunk separado `< 100KB`

### Acessibilidade Mobile
- `prefers-reduced-motion`: desliga TODAS animações (GSAP, CSS, Three.js) → estado final imediato
- `prefers-contrast: high`: força cores high-contrast, desliga glow/blur
- Focus visible: outline 3px gold em todos elementos interativos
- ARIA labels em ícones-only, live regions para contadores animados
- Touch targets mínimos 44x44px (Apple/Google guidelines)

---

## TAREFA 4: PÁGINAS INTERNAS — MOBILE ADAPTADO

### Protocolos
- Mobile: lista cards empilhados, expandível (accordion) ao invés de tabela
- Clearance level: badge colorido + ícone lock, toque → tooltip explicativo

### Entidades (nova página)
- Mobile: tabs horizontais scrolláveis (classes), grid 1 coluna cards
- Card: imagem thumb + código + classe + threat badge
- Modal: bottom sheet (slide up) em vez de center modal

### Sistema Operacional (Desktop Sim)
- **Mobile = não simula desktop**. Mostra **versão app nativa**:
  - Lista de "apps" (Protocolos, Arquivos, Terminal, Classificados) como cards
  - Tap → navega para página dedicada (já existente)
  - Widget tesseract: versão 2D shader only (performance)
  - Terminal: versão simplificada, comandos essenciais só

### Terminal
- Mobile: input fixo no bottom, output scrollável acima
- History: swipe left no input → mostra history drawer
- Autocomplete: chips acima do teclado virtual

---

## TAREFA 5: ESTRUTURA FINAL ATUALIZADA

```
noir-site/
├── resources/
│   ├── css/
│   │   ├── app.css
│   │   ├── home.css
│   │   ├── site-effects.css          # + mobile breakpoints, modes
│   │   ├── protocolos-effects.css
│   │   ├── system.css                # + mobile "app mode" styles
│   │   ├── terminal.css
│   │   ├── arquivos.css
│   │   └── background.css            # NOVO: styles fundo site + widget
│   ├── js/
│   │   ├── app.js                    # Bootstrap + conditional imports
│   │   ├── noir-background.js        # NOVO: fundo site (substitui noir-bg.js)
│   │   ├── tesseract-widget.js       # NOVO: widget 3D só sistema
│   │   ├── site.js                   # Global init, modes, easter eggs
│   │   ├── scroll-narrative.js       # ScrollTrigger timeline (desktop)
│   │   ├── page-transitions.js       # View Transitions + loading
│   │   ├── terminal-engine.js        # Terminal logic (shared)
│   │   ├── system-desktop.js         # Desktop sim (desktop only)
│   │   ├── system-app.js             # NOVO: Mobile app mode sistema
│   │   ├── entities-engine.js        # Entidades grid/modal
│   │   └── protocols-engine.js       # Protocolos list/accordion
│   └── views/
│       ├── home.blade.php
│       ├── protocolos.blade.php
│       ├── entidades.blade.php       # NOVO
│       ├── arquivos.blade.php
│       ├── sistema.blade.php         # + widget canvas + mobile detection
│       ├── terminal.blade.php
│       ├── organizacao.blade.php
│       ├── partials/
│       │   ├── site-loader.blade.php
│       │   ├── folder.blade.php
│       │   ├── entity-card.blade.php
│       │   ├── protocol-card.blade.php
│       │   ├── tesseract-widget.blade.php  # NOVO: partial widget
│       │   └── mobile-nav.blade.php        # NOVO: hamburger drawer
│       └── layouts/
│           └── app.blade.php
├── public/
│   ├── images/
│   │   ├── logo.png
│   │   ├── noir-preview.png
│   │   ├── entities/
│   │   └── wallpapers/               # NOVO: fallbacks mobile WebP
│   └── shaders/
│       ├── scanline.frag
│       ├── glitch.frag
│       ├── tesseract.vert
│       ├── tesseract.frag
│       └── background.frag           # NOVO: shader fundo site
├── docs/
│   └── tech-choices.md               # Decisões técnicas documentadas
└── vite.config.js                    # + code splitting, PWA, imagetools
```

---

## CRITÉRIOS DE ACEITAÇÃO (Definition of Done)

### Visual & UX
- [ ] Site carrega < 2s 3G (Lighthouse Performance > 85)
- [ ] Mobile: navegação fluida, zero layout shift (CLS < 0.1)
- [ ] Desktop: background 3D (se ativado) 60fps sustained
- [ ] Sistema operacional: widget tesseract 60fps desktop, fallback mobile
- [ ] Scroll narrative: suave, sem jank, progress indicator preciso
- [ ] Modes (breach/retro/whisper): ativam < 100ms, revertem limpo

### Técnico
- [ ] Zero erros console (exceto warnings conhecidos Three.js)
- [ ] `prefers-reduced-motion`: tudo para, conteúdo acessível
- [ ] `prefers-contrast: high`: legível, sem glow
- [ ] Service Worker: offline-first para assets estáticos
- [ ] Build produção: `npm run build` sem warnings, chunks otimizados
- [ ] TypeScript strict (se migrar) ou JSDoc types em todos módulos JS

### Lore & Polish
- [ ] Easter eggs funcionam (konami, logo clicks, terminal commands)
- [ ] SETIS breach mode: visual + audio sutil + background reaction
- [ ] Terminal: comandos lore-relevantes, output estilizado
- [ ] Textos: zero placeholder, tudo lore-consistente
- [ ] Imagens: otimizadas, WebP/AVIF, placeholders blur-up

---

## AGENTES SUGERIDOS PARA OPENCODE

| Agente | Responsabilidade |
|--------|------------------|
| `frontend-architect` | Arquitetura geral, code splitting, Vite config, PWA |
| `threejs-specialist` | `noir-background.js`, `tesseract-widget.js`, shaders GLSL |
| `gsap-animator` | ScrollTrigger timeline, page transitions, micro-interactions |
| `mobile-optimizer` | Touch UX, performance mobile, PWA, critical CSS |
| `accessibility-auditor` | a11y audit, reduced motion, contrast, ARIA |
| `loremaster` | Textos, lore consistency, easter eggs, terminal commands |
| `performance-engineer` | Bundle analysis, lazy loading, FPS profiling, Core Web Vitals |

---

**EXECUTE**: Comece por `noir-background.js` (fundo site) + `tesseract-widget.js` (widget sistema) + mobile breakpoints CSS. Depois scroll narrative, páginas internas, terminal, sistema desktop/app mode. Documente decisões em `docs/tech-choices.md`.

**PRIORIDADE**: Mobile first. Desktop enhancement. Lore em cada pixel.
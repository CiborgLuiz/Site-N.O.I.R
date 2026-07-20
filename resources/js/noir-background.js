import * as THREE from 'three';

const QUALITY = { LOW: 'low', HIGH: 'high', AUTO: 'auto' };
const PARTICLE_COUNT_LOW = 800;
const PARTICLE_COUNT_HIGH = 2500;

const BACKGROUND_VERT = `
varying vec2 vUv;
void main() {
    vUv = uv;
    gl_Position = vec4(position, 1.0);
}`;

const BACKGROUND_FRAG = `
precision mediump float;
uniform float uTime;
uniform float uScroll;
uniform vec2 uMouse;
uniform vec2 uResolution;

float random(vec2 st) {
    return fract(sin(dot(st.xy, vec2(12.9898, 78.233))) * 43758.5453123);
}

void main() {
    vec2 uv = gl_FragCoord.xy / uResolution;

    // Chromatic aberration
    float aberr = 0.001 + uScroll * 0.0003;
    float r = random(uv + uTime * 0.01);
    float g = random(uv + uTime * 0.01 + 0.33);
    float b = random(uv + uTime * 0.01 + 0.66);

    float dist = length(uv - 0.5);
    float vignette = smoothstep(0.8, 0.3, dist);

    // Film grain
    float grain = (random(uv * uResolution + uTime * 100.0) - 0.5) * 0.06;

    // Scanlines
    float scanline = sin(gl_FragCoord.y * 1.5 + uTime * 2.0) * 0.015;

    // Mouse influence
    float mouseDist = length(uv - uMouse);
    float mouseGlow = smoothstep(0.3, 0.0, mouseDist) * 0.04;

    // Base color
    vec3 color = vec3(0.02, 0.02, 0.03);

    // Subtle gold/rose gradients
    color += vec3(0.08, 0.065, 0.047) * smoothstep(0.8, 0.2, dist) * 0.3;
    color += vec3(0.055, 0.045, 0.05) * (1.0 - uv.y) * 0.2;

    // Apply effects
    color += grain;
    color += scanline;
    color += mouseGlow * vec3(0.82, 0.67, 0.47);
    color *= vignette;

    gl_FragColor = vec4(color, 1.0);
}`;

class NoirBackground {
    constructor() {
        this.canvas = document.getElementById('noir-bg');
        if (!this.canvas) return;

        this.quality = QUALITY.AUTO;
        this.paused = false;
        this.destroyed = false;
        this.scrollY = 0;
        this.mouse = { x: 0.5, y: 0.5 };
        this.fps = 60;
        this.frameCount = 0;
        this.lastFpsTime = performance.now();
        this.enhanced = false;

        this._setupFallback();
        this._detectCapabilities();
        this._init();
        this._initEvents();
        this._animate();
    }

    _detectCapabilities() {
        const rm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const mobile = window.innerWidth < 768;
        const wide = screen.width > 1024;
        const enhanced = document.body.dataset.bgEnhanced === 'true';

        let memory = true;
        if (navigator.deviceMemory) memory = navigator.deviceMemory > 4;

        this.canEnhanced = enhanced && !rm && !mobile && wide && memory;
        this.reducedMotion = rm;

        if (rm) {
            this.canvas.style.background = '#0a0a0a';
            this.destroyed = true;
        }
    }

    _setupFallback() {
        this.fallbackCtx = null;
        this.fallbackParticles = [];
    }

    _init() {
        if (this.destroyed) return;

        try {
            this.renderer = new THREE.WebGLRenderer({
                canvas: this.canvas,
                antialias: false,
                powerPreference: 'low-power',
            });
        } catch {
            this._fallbackToCanvas2D();
            return;
        }

        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.renderer.setSize(window.innerWidth, window.innerHeight);

        this.scene = new THREE.Scene();
        this.camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);
        this.clock = new THREE.Clock();

        this._buildBackgroundQuad();
        this._buildParticles();

        if (this.canEnhanced && this.quality !== QUALITY.LOW) {
            this._buildEnhanced();
            this.enhanced = true;
        }
    }

    _buildBackgroundQuad() {
        const geo = new THREE.PlaneGeometry(2, 2);
        this.bgUniforms = {
            uTime: { value: 0 },
            uScroll: { value: 0 },
            uMouse: { value: new THREE.Vector2(0.5, 0.5) },
            uResolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) },
        };
        const mat = new THREE.ShaderMaterial({
            vertexShader: BACKGROUND_VERT,
            fragmentShader: BACKGROUND_FRAG,
            uniforms: this.bgUniforms,
            depthTest: false,
            depthWrite: false,
        });
        this.bgQuad = new THREE.Mesh(geo, mat);
        this.scene.add(this.bgQuad);
    }

    _buildParticles() {
        const count = this.quality === QUALITY.LOW ? PARTICLE_COUNT_LOW : PARTICLE_COUNT_HIGH;
        const positions = new Float32Array(count * 3);
        const velocities = new Float32Array(count * 3);

        for (let i = 0; i < count; i++) {
            const i3 = i * 3;
            positions[i3] = (Math.random() - 0.5) * 20;
            positions[i3 + 1] = (Math.random() - 0.5) * 20;
            positions[i3 + 2] = (Math.random() - 0.5) * 10;
            velocities[i3] = (Math.random() - 0.5) * 0.002;
            velocities[i3 + 1] = (Math.random() - 0.5) * 0.002;
            velocities[i3 + 2] = (Math.random() - 0.5) * 0.001;
        }

        const geo = new THREE.BufferGeometry();
        geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));

        const mat = new THREE.PointsMaterial({
            color: 0x9ca3af,
            size: 0.04,
            transparent: true,
            opacity: 0.6,
            sizeAttenuation: true,
            blending: THREE.AdditiveBlending,
            depthWrite: false,
        });

        this.particles = new THREE.Points(geo, mat);
        this.particleVelocities = velocities;
        this.particleCount = count;
        this.scene.add(this.particles);
    }

    _buildEnhanced() {
        // Tesseract wireframe (deep background)
        const verts4D = [];
        for (let i = 0; i < 16; i++) {
            verts4D.push([
                (i & 1) ? 1 : -1,
                (i & 2) ? 1 : -1,
                (i & 4) ? 1 : -1,
                (i & 8) ? 1 : -1,
            ]);
        }
        const edges = [];
        for (let i = 0; i < 16; i++) {
            for (let j = i + 1; j < 16; j++) {
                let diff = 0;
                for (let k = 0; k < 4; k++) {
                    if (verts4D[i][k] !== verts4D[j][k]) diff++;
                }
                if (diff === 1) edges.push([i, j]);
            }
        }

        const positions = new Float32Array(edges.length * 6);
        const geo = new THREE.BufferGeometry();
        geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const mat = new THREE.LineBasicMaterial({
            color: 0xd1ab79,
            transparent: true,
            opacity: 0.08,
        });
        this.tesseractLines = new THREE.LineSegments(geo, mat);
        this.tesseractData = { verts4D, edges, projected: new Array(16) };
        this.scene.add(this.tesseractLines);

        // Connection lines between nearby particles
        const maxConnections = 200;
        const linePositions = new Float32Array(maxConnections * 6);
        const lineGeo = new THREE.BufferGeometry();
        lineGeo.setAttribute('position', new THREE.BufferAttribute(linePositions, 3));
        const lineMat = new THREE.LineBasicMaterial({
            color: 0xd1ab79,
            transparent: true,
            opacity: 0.06,
            blending: THREE.AdditiveBlending,
        });
        this.connectionLines = new THREE.LineSegments(lineGeo, lineMat);
        this.connectionLines.geometry.setDrawRange(0, 0);
        this.scene.add(this.connectionLines);
    }

    _project4Dto3D(v4d, a1, a2) {
        const c1 = Math.cos(a1), s1 = Math.sin(a1);
        let x = v4d[0] * c1 - v4d[1] * s1;
        let y = v4d[0] * s1 + v4d[1] * c1;
        const c2 = Math.cos(a2), s2 = Math.sin(a2);
        const z = v4d[2] * c2 - v4d[3] * s2;
        const w = v4d[2] * s2 + v4d[3] * c2;
        const d = 4;
        const f = 1 / (d - w);
        return [x * f, y * f, z * f];
    }

    _updateParticles(time) {
        const pos = this.particles.geometry.getAttribute('position');
        const arr = pos.array;
        const vel = this.particleVelocities;
        const bound = 10;

        for (let i = 0; i < this.particleCount; i++) {
            const i3 = i * 3;
            arr[i3] += vel[i3];
            arr[i3 + 1] += vel[i3 + 1];
            arr[i3 + 2] += vel[i3 + 2];

            if (Math.abs(arr[i3]) > bound) vel[i3] *= -1;
            if (Math.abs(arr[i3 + 1]) > bound) vel[i3 + 1] *= -1;
            if (Math.abs(arr[i3 + 2]) > bound) vel[i3 + 2] *= -1;
        }
        pos.needsUpdate = true;
    }

    _updateTesseract(time) {
        if (!this.enhanced || !this.tesseractLines) return;

        const speed = 0.08;
        const a1 = time * speed;
        const a2 = time * speed * 0.7;
        const { verts4D, edges } = this.tesseractData;
        const pos = this.tesseractLines.geometry.getAttribute('position');

        for (let i = 0; i < 16; i++) {
            this.tesseractData.projected[i] = this._project4Dto3D(verts4D[i], a1, a2);
        }

        let idx = 0;
        for (const [a, b] of edges) {
            const pa = this.tesseractData.projected[a];
            const pb = this.tesseractData.projected[b];
            pos.setXYZ(idx, pa[0] * 3, pa[1] * 3, pa[2] * 3 - 5);
            pos.setXYZ(idx + 1, pb[0] * 3, pb[1] * 3, pb[2] * 3 - 5);
            idx += 2;
        }
        pos.needsUpdate = true;

        const pulse = 0.06 + Math.sin(time * 0.3) * 0.03;
        this.tesseractLines.material.opacity = pulse;
    }

    _updateConnections() {
        if (!this.enhanced || !this.connectionLines) return;

        const pos = this.particles.geometry.getAttribute('position');
        const linePos = this.connectionLines.geometry.getAttribute('position');
        const maxDist = 2.5;
        let idx = 0;
        const maxVerts = linePos.count;

        // ponytail: O(n²) but n is small (2500) and we cap early
        for (let i = 0; i < this.particleCount && idx < maxVerts; i++) {
            for (let j = i + 1; j < this.particleCount && idx < maxVerts; j++) {
                const dx = pos.array[i * 3] - pos.array[j * 3];
                const dy = pos.array[i * 3 + 1] - pos.array[j * 3 + 1];
                const dz = pos.array[i * 3 + 2] - pos.array[j * 3 + 2];
                const d = dx * dx + dy * dy + dz * dz;
                if (d < maxDist * maxDist) {
                    linePos.setXYZ(idx, pos.array[i * 3], pos.array[i * 3 + 1], pos.array[i * 3 + 2]);
                    linePos.setXYZ(idx + 1, pos.array[j * 3], pos.array[j * 3 + 1], pos.array[j * 3 + 2]);
                    idx += 2;
                }
            }
        }
        linePos.needsUpdate = true;
        this.connectionLines.geometry.setDrawRange(0, idx);
    }

    _initEvents() {
        window.addEventListener('mousemove', (e) => {
            this.mouse.x = e.clientX / window.innerWidth;
            this.mouse.y = 1.0 - e.clientY / window.innerHeight;
        });

        window.addEventListener('scroll', () => {
            this.scrollY = window.scrollY;
        }, { passive: true });

        window.addEventListener('resize', () => {
            if (this.destroyed || !this.renderer) return;
            this.renderer.setSize(window.innerWidth, window.innerHeight);
            this.bgUniforms.uResolution.value.set(window.innerWidth, window.innerHeight);
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) this.pause();
            else this.resume();
        });

        // Battery API
        if (navigator.getBattery) {
            navigator.getBattery().then((bat) => {
                const check = () => {
                    if (!bat.charging && bat.level < 0.2) {
                        this.setQuality(QUALITY.LOW);
                    }
                };
                bat.addEventListener('levelchange', check);
                bat.addEventListener('chargingchange', check);
            });
        }
    }

    _animate() {
        if (this.destroyed) return;
        this._rafId = requestAnimationFrame(() => this._animate());
        if (this.paused) return;

        const time = this.clock.getElapsedTime();

        // FPS tracking
        this.frameCount++;
        const now = performance.now();
        if (now - this.lastFpsTime >= 1000) {
            this.fps = this.frameCount;
            this.frameCount = 0;
            this.lastFpsTime = now;
            if (this.fps < 30 && this.quality !== QUALITY.LOW) {
                this.setQuality(QUALITY.LOW);
            }
        }

        this.bgUniforms.uTime.value = time;
        this.bgUniforms.uScroll.value = this.scrollY;
        this.bgUniforms.uMouse.value.set(this.mouse.x, this.mouse.y);

        this._updateParticles(time);
        this._updateTesseract(time);
        this._updateConnections();

        this.renderer.render(this.scene, this.camera);
    }

    _fallbackToCanvas2D() {
        this.destroyed = true;
        this.canvas.style.background = '#0a0a0a';

        const ctx = this.canvas.getContext('2d');
        if (!ctx) return;

        this.fallbackCtx = ctx;
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;

        const particles = [];
        for (let i = 0; i < 80; i++) {
            particles.push({
                x: Math.random() * this.canvas.width,
                y: Math.random() * this.canvas.height,
                vx: (Math.random() - 0.5) * 0.3,
                vy: (Math.random() - 0.5) * 0.3,
            });
        }

        const draw = () => {
            if (this.destroyed) return;
            ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

            for (const p of particles) {
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0 || p.x > this.canvas.width) p.vx *= -1;
                if (p.y < 0 || p.y > this.canvas.height) p.vy *= -1;

                ctx.fillStyle = '#9ca3af';
                ctx.beginPath();
                ctx.arc(p.x, p.y, 1.5, 0, Math.PI * 2);
                ctx.fill();
            }

            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 130) {
                        ctx.strokeStyle = `rgba(156,163,175,${1 - dist / 130})`;
                        ctx.lineWidth = 0.4;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }

            requestAnimationFrame(draw);
        };

        window.addEventListener('resize', () => {
            this.canvas.width = window.innerWidth;
            this.canvas.height = window.innerHeight;
        });

        draw();
    }

    setQuality(q) {
        this.quality = q;
        // ponytail: quality changes take effect on next init, not hot-swapped
    }

    pause() {
        this.paused = true;
        if (this.clock) this.clock.stop();
    }

    resume() {
        if (this.paused) {
            this.paused = false;
            if (this.clock) this.clock.start();
        }
    }

    destroy() {
        this.destroyed = true;
        cancelAnimationFrame(this._rafId);
        if (this.renderer) {
            this.renderer.dispose();
            this.scene?.traverse((obj) => {
                if (obj.geometry) obj.geometry.dispose();
                if (obj.material) obj.material.dispose();
            });
        }
    }
}

// ponytail: auto-init when not lazy-loaded
if (typeof window !== 'undefined' && !window.__NOIR_BG_LAZY__) {
    const instance = new NoirBackground();
    window.NOIR_BG = {
        setQuality: (q) => instance.setQuality(q),
        pause: () => instance.pause(),
        resume: () => instance.resume(),
    };
}

export { NoirBackground };

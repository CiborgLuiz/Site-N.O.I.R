import * as THREE from 'three';

const THEMES = {
    noir: {
        primary: new THREE.Color(0xd1ab79),
        secondary: new THREE.Color(0x8d7482),
        grid: new THREE.Color(0xd1ab79),
        bg: new THREE.Color(0x0a0a0a),
        fresnelStrength: 1.2,
        scanlines: true,
    },
    system: {
        primary: new THREE.Color(0x00d4ff),
        secondary: new THREE.Color(0x0066ff),
        grid: new THREE.Color(0x00d4ff),
        bg: new THREE.Color(0x050a14),
        fresnelStrength: 1.0,
        scanlines: false,
    },
    breach: {
        primary: new THREE.Color(0xe74c3c),
        secondary: new THREE.Color(0xff6b6b),
        grid: new THREE.Color(0xe74c3c),
        bg: new THREE.Color(0x0a0202),
        fresnelStrength: 2.0,
        scanlines: false,
    },
    retro: {
        primary: new THREE.Color(0x00ff41),
        secondary: new THREE.Color(0x00cc33),
        grid: new THREE.Color(0x00ff41),
        bg: new THREE.Color(0x000800),
        fresnelStrength: 0.8,
        scanlines: true,
    },
};

const VERT_SHADER = `
varying vec3 vNormal;
varying vec3 vWorldPos;
varying vec2 vUv;
void main() {
    vNormal = normalize(normalMatrix * normal);
    vWorldPos = (modelMatrix * vec4(position, 1.0)).xyz;
    vUv = uv;
    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
}`;

const FRAG_SHADER = `
precision mediump float;
uniform vec3 uColor;
uniform vec3 uSecondary;
uniform float uTime;
uniform float uIntensity;
uniform float uFresnelStrength;
uniform bool uScanlines;
varying vec3 vNormal;
varying vec3 vWorldPos;
varying vec2 vUv;
void main() {
    vec3 viewDir = normalize(cameraPosition - vWorldPos);
    float fresnel = pow(1.0 - abs(dot(viewDir, vNormal)), uFresnelStrength);
    float pulse = 0.85 + 0.15 * sin(uTime * 0.3);
    vec3 color = mix(uSecondary, uColor, fresnel) * pulse * uIntensity;
    float alpha = (0.08 + fresnel * 0.55) * uIntensity;
    if (uScanlines) {
        float scan = 0.92 + 0.08 * sin(vWorldPos.y * 80.0 + uTime * 2.0);
        color *= scan;
    }
    gl_FragColor = vec4(color, alpha);
}`;

const GRID_FRAG = `
precision mediump float;
uniform vec3 uGridColor;
uniform float uTime;
uniform float uIntensity;
varying vec3 vWorldPos;
varying vec2 vUv;
void main() {
    vec2 grid = abs(fract(vWorldPos.xz * 0.5) - 0.5);
    float line = min(grid.x, grid.y);
    float mask = 1.0 - smoothstep(0.0, 0.04, line);
    float dist = length(vWorldPos.xz) / 12.0;
    float fade = 1.0 - smoothstep(0.1, 0.9, dist);
    float pulse = 0.8 + 0.2 * sin(uTime * 0.15);
    vec3 color = uGridColor * pulse * uIntensity;
    float alpha = mask * fade * 0.18 * uIntensity;
    gl_FragColor = vec4(color, alpha);
}`;

const EDGE_VERT = `
varying vec3 vWorldPos;
void main() {
    vWorldPos = (modelMatrix * vec4(position, 1.0)).xyz;
    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
}`;

const EDGE_FRAG = `
precision mediump float;
uniform vec3 uColor;
uniform float uTime;
uniform float uIntensity;
varying vec3 vWorldPos;
void main() {
    float pulse = 0.7 + 0.3 * sin(uTime * 0.3);
    float glow = uIntensity * pulse;
    gl_FragColor = vec4(uColor * glow, glow * 0.7);
}`;

class TesseractWidget {
    constructor(selector, options = {}) {
        this.canvas = document.querySelector(selector);
        if (!this.canvas) return;

        this.theme = THEMES[options.theme] || THEMES.system;
        this.themeName = options.theme || 'system';
        this.intensity = options.intensity ?? 1;
        this.paused = false;
        this.destroyed = false;
        this.mouse = { x: 0, y: 0 };

        this._detect();
        if (this.destroyed) return;

        this._init();
        this._initEvents();
        this._animate();
    }

    _detect() {
        const rm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.reducedMotion = rm;
        if (rm) {
            this.canvas.style.background = `radial-gradient(circle at center, ${this._themeGradient()}, rgba(0,0,0,0.98) 70%)`;
            this.destroyed = true;
        }
    }

    _themeGradient() {
        const c = this.theme.primary;
        const r = Math.round(c.r * 255);
        const g = Math.round(c.g * 255);
        const b = Math.round(c.b * 255);
        return `rgba(${r},${g},${b},0.08)`;
    }

    _init() {
        try {
            this.renderer = new THREE.WebGLRenderer({
                canvas: this.canvas,
                antialias: true,
                alpha: true,
                powerPreference: 'low-power',
            });
        } catch {
            this.destroyed = true;
            return;
        }

        const rect = this.canvas.parentElement.getBoundingClientRect();
        const w = rect.width || 800;
        const h = rect.height || 450;

        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.renderer.setSize(w, h);
        this.renderer.setClearColor(this.theme.bg, 1);

        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(50, w / h, 0.1, 100);
        this.camera.position.set(0, 1.5, 5.5);
        this.camera.lookAt(0, 0, 0);

        this.clock = new THREE.Clock();

        this._buildTesseract();
        this._buildGrid();
    }

    _buildTesseract() {
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

        const linePositions = new Float32Array(edges.length * 6);
        const lineGeo = new THREE.BufferGeometry();
        lineGeo.setAttribute('position', new THREE.BufferAttribute(linePositions, 3));

        this.edgeMaterial = new THREE.ShaderMaterial({
            vertexShader: EDGE_VERT,
            fragmentShader: EDGE_FRAG,
            uniforms: {
                uColor: { value: this.theme.primary },
                uTime: { value: 0 },
                uIntensity: { value: this.intensity },
            },
            transparent: true,
            blending: THREE.AdditiveBlending,
            depthWrite: false,
        });

        this.tesseractLines = new THREE.LineSegments(lineGeo, this.edgeMaterial);
        this.scene.add(this.tesseractLines);

        this.tesseractData = { verts4D, edges, projected: new Array(16) };

        // Semi-transparent faces
        const facePositions = [];
        const faceIndices = [];

        // Build hypercube faces: 8 cubic faces (each is 4 triangles = 2 tris)
        // For each pair of axes (6 choices), fix the other 2 axes at +/-1
        const axes = [[0,1],[0,2],[0,3],[1,2],[1,3],[2,3]];
        const fixedAxes = [[2,3],[1,3],[1,2],[0,3],[0,2],[0,1]];

        let vertIdx = 0;
        for (let f = 0; f < axes.length; f++) {
            const [a1, a2] = axes[f];
            const [fi1, fi2] = fixedAxes[f];

            for (const v1 of [-1, 1]) {
                for (const v2 of [-1, 1]) {
                    const faceVerts = [];
                    for (const s1 of [-1, 1]) {
                        for (const s2 of [-1, 1]) {
                            const v = [0, 0, 0, 0];
                            v[a1] = s1;
                            v[a2] = s2;
                            v[fi1] = v1;
                            v[fi2] = v2;
                            faceVerts.push(v);
                        }
                    }
                    // Two triangles per quad
                    facePositions.push(...faceVerts[0], ...faceVerts[1], ...faceVerts[2]);
                    facePositions.push(...faceVerts[1], ...faceVerts[3], ...faceVerts[2]);
                    faceIndices.push(vertIdx, vertIdx+1, vertIdx+2, vertIdx+3, vertIdx+4, vertIdx+5);
                    vertIdx += 6;
                }
            }
        }

        const faceGeo = new THREE.BufferGeometry();
        faceGeo.setAttribute('position', new THREE.BufferAttribute(new Float32Array(facePositions), 3));
        faceGeo.setIndex(faceIndices);
        faceGeo.computeVertexNormals();

        this.faceMaterial = new THREE.ShaderMaterial({
            vertexShader: VERT_SHADER,
            fragmentShader: FRAG_SHADER,
            uniforms: {
                uColor: { value: this.theme.primary },
                uSecondary: { value: this.theme.secondary },
                uTime: { value: 0 },
                uIntensity: { value: this.intensity },
                uFresnelStrength: { value: this.theme.fresnelStrength },
                uScanlines: { value: this.theme.scanlines },
            },
            transparent: true,
            side: THREE.DoubleSide,
            depthWrite: false,
            blending: THREE.AdditiveBlending,
        });

        this.tesseractFaces = new THREE.Mesh(faceGeo, this.faceMaterial);
        this.scene.add(this.tesseractFaces);
    }

    _buildGrid() {
        const geo = new THREE.PlaneGeometry(24, 24, 1, 1);
        geo.rotateX(-Math.PI / 2);

        this.gridMaterial = new THREE.ShaderMaterial({
            vertexShader: `
                varying vec3 vWorldPos;
                varying vec2 vUv;
                void main() {
                    vWorldPos = (modelMatrix * vec4(position, 1.0)).xyz;
                    vUv = uv;
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                }`,
            fragmentShader: GRID_FRAG,
            uniforms: {
                uGridColor: { value: this.theme.grid },
                uTime: { value: 0 },
                uIntensity: { value: this.intensity },
            },
            transparent: true,
            depthWrite: false,
        });

        const grid = new THREE.Mesh(geo, this.gridMaterial);
        grid.position.y = -1.8;
        this.scene.add(grid);
    }

    _project4Dto3D(v4d, a1, a2) {
        const c1 = Math.cos(a1), s1 = Math.sin(a1);
        let x = v4d[0] * c1 - v4d[1] * s1;
        let y = v4d[0] * s1 + v4d[1] * c1;
        const c2 = Math.cos(a2), s2 = Math.sin(a2);
        const z = v4d[2] * c2 - v4d[3] * s2;
        const w = v4d[2] * s2 + v4d[3] * c2;
        const d = 3.5;
        const f = 1 / (d - w);
        return [x * f, y * f, z * f];
    }

    _updateTesseract(time) {
        const speed = 0.12;
        const a1 = time * speed;
        const a2 = time * speed * 0.618;
        const { verts4D, edges } = this.tesseractData;
        const pos = this.tesseractLines.geometry.getAttribute('position');

        for (let i = 0; i < 16; i++) {
            this.tesseractData.projected[i] = this._project4Dto3D(verts4D[i], a1, a2);
        }

        let idx = 0;
        for (const [a, b] of edges) {
            const pa = this.tesseractData.projected[a];
            const pb = this.tesseractData.projected[b];
            pos.setXYZ(idx, pa[0] * 1.4, pa[1] * 1.4, pa[2] * 1.4);
            pos.setXYZ(idx + 1, pb[0] * 1.4, pb[1] * 1.4, pb[2] * 1.4);
            idx += 2;
        }
        pos.needsUpdate = true;

        // Update face vertices
        const facePos = this.tesseractFaces.geometry.getAttribute('position');
        const faArr = facePos.array;
        const verts4DAll = this.tesseractData.verts4D;

        // Rebuild face positions from projected vertices
        // Each face has 12 verts (4 face verts × 3 verts per tri pair)
        // Easier: just reproject all 16 and update face verts by index
        // ponytail: rebuild face positions each frame — only 288 floats
        const axes = [[0,1],[0,2],[0,3],[1,2],[1,3],[2,3]];
        const fixedAxes = [[2,3],[1,3],[1,2],[0,3],[0,2],[0,1]];
        let vi = 0;

        for (let f = 0; f < axes.length; f++) {
            const [a1, a2] = axes[f];
            const [fi1, fi2] = fixedAxes[f];

            for (const v1 of [-1, 1]) {
                for (const v2 of [-1, 1]) {
                    const faceVertIndices = [];
                    for (const s1 of [-1, 1]) {
                        for (const s2 of [-1, 1]) {
                            const v = [0, 0, 0, 0];
                            v[a1] = s1;
                            v[a2] = s2;
                            v[fi1] = v1;
                            v[fi2] = v2;

                            // Find which index this vertex corresponds to
                            let idx = 0;
                            for (let k = 0; k < 16; k++) {
                                if (verts4DAll[k][0] === v[0] && verts4DAll[k][1] === v[1] &&
                                    verts4DAll[k][2] === v[2] && verts4DAll[k][3] === v[3]) {
                                    idx = k; break;
                                }
                            }
                            faceVertIndices.push(idx);
                        }
                    }

                    const projected = faceVertIndices.map(i => this.tesseractData.projected[i]);
                    // tri 1: [0],[1],[2]  tri 2: [1],[3],[2]
                    const triIndices = [0, 1, 2, 1, 3, 2];
                    for (const ti of triIndices) {
                        const p = projected[ti];
                        faArr[vi++] = p[0] * 1.4;
                        faArr[vi++] = p[1] * 1.4;
                        faArr[vi++] = p[2] * 1.4;
                    }
                }
            }
        }
        facePos.needsUpdate = true;
        this.tesseractFaces.geometry.computeVertexNormals();
    }

    _initEvents() {
        const parent = this.canvas.parentElement;
        parent.addEventListener('mousemove', (e) => {
            const rect = parent.getBoundingClientRect();
            this.mouse.x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
            this.mouse.y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
        });

        parent.addEventListener('click', () => {
            this._pingTime = this.clock.getElapsedTime();
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) this.pause();
            else this.resume();
        });

        this._resizeObserver = new ResizeObserver(() => {
            if (this.destroyed || !this.renderer) return;
            const rect = this.canvas.parentElement.getBoundingClientRect();
            const w = rect.width || 800;
            const h = rect.height || 450;
            this.renderer.setSize(w, h);
            this.camera.aspect = w / h;
            this.camera.updateProjectionMatrix();
        });
        this._resizeObserver.observe(this.canvas.parentElement);
    }

    _animate() {
        if (this.destroyed) return;
        this._rafId = requestAnimationFrame(() => this._animate());
        if (this.paused) return;

        const time = this.clock.getElapsedTime();

        // Smooth camera parallax (max ~5 degrees)
        const targetX = this.mouse.x * 0.5;
        const targetZ = 5.5 + this.mouse.y * -0.3;
        this.camera.position.x += (targetX - this.camera.position.x) * 0.04;
        this.camera.position.z += (targetZ - this.camera.position.z) * 0.04;
        this.camera.lookAt(0, 0, 0);

        // Ping ripple
        let pingEffect = 0;
        if (this._pingTime !== undefined) {
            const elapsed = time - this._pingTime;
            if (elapsed < 1.5) {
                pingEffect = Math.sin(elapsed * 8) * Math.exp(-elapsed * 3) * 0.4;
            }
        }

        this.edgeMaterial.uniforms.uTime.value = time;
        this.edgeMaterial.uniforms.uIntensity.value = this.intensity + pingEffect;

        this.faceMaterial.uniforms.uTime.value = time;
        this.faceMaterial.uniforms.uIntensity.value = this.intensity + pingEffect;

        this.gridMaterial.uniforms.uTime.value = time;
        this.gridMaterial.uniforms.uIntensity.value = this.intensity;

        this._updateTesseract(time);
        this.renderer.render(this.scene, this.camera);
    }

    setTheme(name) {
        if (!THEMES[name] || name === this.themeName) return;
        this.themeName = name;
        this.theme = THEMES[name];

        this.edgeMaterial.uniforms.uColor.value.copy(this.theme.primary);
        this.faceMaterial.uniforms.uColor.value.copy(this.theme.primary);
        this.faceMaterial.uniforms.uSecondary.value.copy(this.theme.secondary);
        this.faceMaterial.uniforms.uFresnelStrength.value = this.theme.fresnelStrength;
        this.faceMaterial.uniforms.uScanlines.value = this.theme.scanlines;
        this.gridMaterial.uniforms.uGridColor.value.copy(this.theme.grid);
        this.renderer.setClearColor(this.theme.bg, 1);
    }

    setIntensity(v) {
        this.intensity = Math.max(0, Math.min(1, v));
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
        this._resizeObserver?.disconnect();
        if (this.renderer) {
            this.renderer.dispose();
            this.scene?.traverse((obj) => {
                if (obj.geometry) obj.geometry.dispose();
                if (obj.material) obj.material.dispose();
            });
        }
    }
}

// Auto-init
if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && window.innerWidth >= 768) {
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('tesseract-widget');
        if (el) {
            window.TESSERACT_WIDGET = new TesseractWidget('#tesseract-widget', { theme: 'system' });
        }
    });
} else {
    // Mobile/reduced motion: static gradient
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('tesseract-widget');
        if (el) {
            el.style.background = 'radial-gradient(circle at center, rgba(0,212,255,0.08), rgba(0,0,0,0.98) 70%)';
        }
    });
}

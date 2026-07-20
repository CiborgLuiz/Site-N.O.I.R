import{C as n,W as R,S as L,P as j,a as B,B as A,b as W,c as P,A as G,L as N,D as V,M as k,d as U}from"./three-C_TqQV4f.js";const T={noir:{primary:new n(13740921),secondary:new n(9270402),grid:new n(13740921),bg:new n(657930),fresnelStrength:1.2,scanlines:!0},system:{primary:new n(54527),secondary:new n(26367),grid:new n(54527),bg:new n(330260),fresnelStrength:1,scanlines:!1},breach:{primary:new n(15158332),secondary:new n(16739179),grid:new n(15158332),bg:new n(655874),fresnelStrength:2,scanlines:!1},retro:{primary:new n(65345),secondary:new n(52275),grid:new n(65345),bg:new n(2048),fresnelStrength:.8,scanlines:!0}},O=`
varying vec3 vNormal;
varying vec3 vWorldPos;
varying vec2 vUv;
void main() {
    vNormal = normalize(normalMatrix * normal);
    vWorldPos = (modelMatrix * vec4(position, 1.0)).xyz;
    vUv = uv;
    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
}`,X=`
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
}`,$=`
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
}`,H=`
varying vec3 vWorldPos;
void main() {
    vWorldPos = (modelMatrix * vec4(position, 1.0)).xyz;
    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
}`,Y=`
precision mediump float;
uniform vec3 uColor;
uniform float uTime;
uniform float uIntensity;
varying vec3 vWorldPos;
void main() {
    float pulse = 0.7 + 0.3 * sin(uTime * 0.3);
    float glow = uIntensity * pulse;
    gl_FragColor = vec4(uColor * glow, glow * 0.7);
}`;class Z{constructor(e,t={}){this.canvas=document.querySelector(e),this.canvas&&(this.theme=T[t.theme]||T.system,this.themeName=t.theme||"system",this.intensity=t.intensity??1,this.paused=!1,this.destroyed=!1,this.mouse={x:0,y:0},this._detect(),!this.destroyed&&(this._init(),this._initEvents(),this._animate()))}_detect(){const e=window.matchMedia("(prefers-reduced-motion: reduce)").matches;this.reducedMotion=e,e&&(this.canvas.style.background=`radial-gradient(circle at center, ${this._themeGradient()}, rgba(0,0,0,0.98) 70%)`,this.destroyed=!0)}_themeGradient(){const e=this.theme.primary,t=Math.round(e.r*255),i=Math.round(e.g*255),r=Math.round(e.b*255);return`rgba(${t},${i},${r},0.08)`}_init(){try{this.renderer=new R({canvas:this.canvas,antialias:!0,alpha:!0,powerPreference:"low-power"})}catch{this.destroyed=!0;return}const e=this.canvas.parentElement.getBoundingClientRect(),t=e.width||800,i=e.height||450;this.renderer.setPixelRatio(Math.min(window.devicePixelRatio,2)),this.renderer.setSize(t,i),this.renderer.setClearColor(this.theme.bg,1),this.scene=new L,this.camera=new j(50,t/i,.1,100),this.camera.position.set(0,1.5,5.5),this.camera.lookAt(0,0,0),this.clock=new B,this._buildTesseract(),this._buildGrid()}_buildTesseract(){const e=[];for(let s=0;s<16;s++)e.push([s&1?1:-1,s&2?1:-1,s&4?1:-1,s&8?1:-1]);const t=[];for(let s=0;s<16;s++)for(let d=s+1;d<16;d++){let u=0;for(let m=0;m<4;m++)e[s][m]!==e[d][m]&&u++;u===1&&t.push([s,d])}const i=new Float32Array(t.length*6),r=new A;r.setAttribute("position",new W(i,3)),this.edgeMaterial=new P({vertexShader:H,fragmentShader:Y,uniforms:{uColor:{value:this.theme.primary},uTime:{value:0},uIntensity:{value:this.intensity}},transparent:!0,blending:G,depthWrite:!1}),this.tesseractLines=new N(r,this.edgeMaterial),this.scene.add(this.tesseractLines),this.tesseractData={verts4D:e,edges:t,projected:new Array(16)};const l=[],x=[],f=[[0,1],[0,2],[0,3],[1,2],[1,3],[2,3]],v=[[2,3],[1,3],[1,2],[0,3],[0,2],[0,1]];let a=0;for(let s=0;s<f.length;s++){const[d,u]=f[s],[m,o]=v[s];for(const M of[-1,1])for(const g of[-1,1]){const c=[];for(const C of[-1,1])for(const E of[-1,1]){const y=[0,0,0,0];y[d]=C,y[u]=E,y[m]=M,y[o]=g,c.push(y)}l.push(...c[0],...c[1],...c[2]),l.push(...c[1],...c[3],...c[2]),x.push(a,a+1,a+2,a+3,a+4,a+5),a+=6}}const h=new A;h.setAttribute("position",new W(new Float32Array(l),3)),h.setIndex(x),h.computeVertexNormals(),this.faceMaterial=new P({vertexShader:O,fragmentShader:X,uniforms:{uColor:{value:this.theme.primary},uSecondary:{value:this.theme.secondary},uTime:{value:0},uIntensity:{value:this.intensity},uFresnelStrength:{value:this.theme.fresnelStrength},uScanlines:{value:this.theme.scanlines}},transparent:!0,side:V,depthWrite:!1,blending:G}),this.tesseractFaces=new k(h,this.faceMaterial),this.scene.add(this.tesseractFaces)}_buildGrid(){const e=new U(24,24,1,1);e.rotateX(-Math.PI/2),this.gridMaterial=new P({vertexShader:`
                varying vec3 vWorldPos;
                varying vec2 vUv;
                void main() {
                    vWorldPos = (modelMatrix * vec4(position, 1.0)).xyz;
                    vUv = uv;
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                }`,fragmentShader:$,uniforms:{uGridColor:{value:this.theme.grid},uTime:{value:0},uIntensity:{value:this.intensity}},transparent:!0,depthWrite:!1});const t=new k(e,this.gridMaterial);t.position.y=-1.8,this.scene.add(t)}_project4Dto3D(e,t,i){const r=Math.cos(t),l=Math.sin(t);let x=e[0]*r-e[1]*l,f=e[0]*l+e[1]*r;const v=Math.cos(i),a=Math.sin(i),h=e[2]*v-e[3]*a,u=1/(3.5-(e[2]*a+e[3]*v));return[x*u,f*u,h*u]}_updateTesseract(e){const i=e*.12,r=e*.12*.618,{verts4D:l,edges:x}=this.tesseractData,f=this.tesseractLines.geometry.getAttribute("position");for(let o=0;o<16;o++)this.tesseractData.projected[o]=this._project4Dto3D(l[o],i,r);let v=0;for(const[o,M]of x){const g=this.tesseractData.projected[o],c=this.tesseractData.projected[M];f.setXYZ(v,g[0]*1.4,g[1]*1.4,g[2]*1.4),f.setXYZ(v+1,c[0]*1.4,c[1]*1.4,c[2]*1.4),v+=2}f.needsUpdate=!0;const a=this.tesseractFaces.geometry.getAttribute("position"),h=a.array,s=this.tesseractData.verts4D,d=[[0,1],[0,2],[0,3],[1,2],[1,3],[2,3]],u=[[2,3],[1,3],[1,2],[0,3],[0,2],[0,1]];let m=0;for(let o=0;o<d.length;o++){const[M,g]=d[o],[c,C]=u[o];for(const E of[-1,1])for(const y of[-1,1]){const D=[];for(const b of[-1,1])for(const _ of[-1,1]){const p=[0,0,0,0];p[M]=b,p[g]=_,p[c]=E,p[C]=y;let I=0;for(let w=0;w<16;w++)if(s[w][0]===p[0]&&s[w][1]===p[1]&&s[w][2]===p[2]&&s[w][3]===p[3]){I=w;break}D.push(I)}const F=D.map(b=>this.tesseractData.projected[b]),z=[0,1,2,1,3,2];for(const b of z){const _=F[b];h[m++]=_[0]*1.4,h[m++]=_[1]*1.4,h[m++]=_[2]*1.4}}}a.needsUpdate=!0,this.tesseractFaces.geometry.computeVertexNormals()}_initEvents(){const e=this.canvas.parentElement;e.addEventListener("mousemove",t=>{const i=e.getBoundingClientRect();this.mouse.x=((t.clientX-i.left)/i.width-.5)*2,this.mouse.y=((t.clientY-i.top)/i.height-.5)*2}),e.addEventListener("click",()=>{this._pingTime=this.clock.getElapsedTime()}),document.addEventListener("visibilitychange",()=>{document.hidden?this.pause():this.resume()}),this._resizeObserver=new ResizeObserver(()=>{if(this.destroyed||!this.renderer)return;const t=this.canvas.parentElement.getBoundingClientRect(),i=t.width||800,r=t.height||450;this.renderer.setSize(i,r),this.camera.aspect=i/r,this.camera.updateProjectionMatrix()}),this._resizeObserver.observe(this.canvas.parentElement)}_animate(){if(this.destroyed||(this._rafId=requestAnimationFrame(()=>this._animate()),this.paused))return;const e=this.clock.getElapsedTime(),t=this.mouse.x*.5,i=5.5+this.mouse.y*-.3;this.camera.position.x+=(t-this.camera.position.x)*.04,this.camera.position.z+=(i-this.camera.position.z)*.04,this.camera.lookAt(0,0,0);let r=0;if(this._pingTime!==void 0){const l=e-this._pingTime;l<1.5&&(r=Math.sin(l*8)*Math.exp(-l*3)*.4)}this.edgeMaterial.uniforms.uTime.value=e,this.edgeMaterial.uniforms.uIntensity.value=this.intensity+r,this.faceMaterial.uniforms.uTime.value=e,this.faceMaterial.uniforms.uIntensity.value=this.intensity+r,this.gridMaterial.uniforms.uTime.value=e,this.gridMaterial.uniforms.uIntensity.value=this.intensity,this._updateTesseract(e),this.renderer.render(this.scene,this.camera)}setTheme(e){!T[e]||e===this.themeName||(this.themeName=e,this.theme=T[e],this.edgeMaterial.uniforms.uColor.value.copy(this.theme.primary),this.faceMaterial.uniforms.uColor.value.copy(this.theme.primary),this.faceMaterial.uniforms.uSecondary.value.copy(this.theme.secondary),this.faceMaterial.uniforms.uFresnelStrength.value=this.theme.fresnelStrength,this.faceMaterial.uniforms.uScanlines.value=this.theme.scanlines,this.gridMaterial.uniforms.uGridColor.value.copy(this.theme.grid),this.renderer.setClearColor(this.theme.bg,1))}setIntensity(e){this.intensity=Math.max(0,Math.min(1,e))}pause(){this.paused=!0,this.clock&&this.clock.stop()}resume(){this.paused&&(this.paused=!1,this.clock&&this.clock.start())}destroy(){this.destroyed=!0,cancelAnimationFrame(this._rafId),this._resizeObserver?.disconnect(),this.renderer&&(this.renderer.dispose(),this.scene?.traverse(e=>{e.geometry&&e.geometry.dispose(),e.material&&e.material.dispose()}))}}!window.matchMedia("(prefers-reduced-motion: reduce)").matches&&window.innerWidth>=768?document.addEventListener("DOMContentLoaded",()=>{document.getElementById("tesseract-widget")&&(window.TESSERACT_WIDGET=new Z("#tesseract-widget",{theme:"system"}))}):document.addEventListener("DOMContentLoaded",()=>{const S=document.getElementById("tesseract-widget");S&&(S.style.background="radial-gradient(circle at center, rgba(0,212,255,0.08), rgba(0,0,0,0.98) 70%)")});

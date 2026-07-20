import{W as _,S as x,O as M,a as L,d as C,V as g,c as A,M as T,B as p,b as w,e as k,A as y,f as D,g as v,L as b}from"./three-C_TqQV4f.js";const f={LOW:"low",AUTO:"auto"},W=800,E=2500,P=`
varying vec2 vUv;
void main() {
    vUv = uv;
    gl_Position = vec4(position, 1.0);
}`,R=`
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
}`;class U{constructor(){this.canvas=document.getElementById("noir-bg"),this.canvas&&(this.quality=f.AUTO,this.paused=!1,this.destroyed=!1,this.scrollY=0,this.mouse={x:.5,y:.5},this.fps=60,this.frameCount=0,this.lastFpsTime=performance.now(),this.enhanced=!1,this._setupFallback(),this._detectCapabilities(),this._init(),this._initEvents(),this._animate())}_detectCapabilities(){const t=window.matchMedia("(prefers-reduced-motion: reduce)").matches,i=window.innerWidth<768,n=screen.width>1024,e=document.body.dataset.bgEnhanced==="true";let o=!0;navigator.deviceMemory&&(o=navigator.deviceMemory>4),this.canEnhanced=e&&!t&&!i&&n&&o,this.reducedMotion=t,t&&(this.canvas.style.background="#0a0a0a",this.destroyed=!0)}_setupFallback(){this.fallbackCtx=null,this.fallbackParticles=[]}_init(){if(!this.destroyed){try{this.renderer=new _({canvas:this.canvas,antialias:!1,powerPreference:"low-power"})}catch{this._fallbackToCanvas2D();return}this.renderer.setPixelRatio(Math.min(window.devicePixelRatio,2)),this.renderer.setSize(window.innerWidth,window.innerHeight),this.scene=new x,this.camera=new M(-1,1,1,-1,0,1),this.clock=new L,this._buildBackgroundQuad(),this._buildParticles(),this.canEnhanced&&this.quality!==f.LOW&&(this._buildEnhanced(),this.enhanced=!0)}}_buildBackgroundQuad(){const t=new C(2,2);this.bgUniforms={uTime:{value:0},uScroll:{value:0},uMouse:{value:new g(.5,.5)},uResolution:{value:new g(window.innerWidth,window.innerHeight)}};const i=new A({vertexShader:P,fragmentShader:R,uniforms:this.bgUniforms,depthTest:!1,depthWrite:!1});this.bgQuad=new T(t,i),this.scene.add(this.bgQuad)}_buildParticles(){const t=this.quality===f.LOW?W:E,i=new Float32Array(t*3),n=new Float32Array(t*3);for(let a=0;a<t;a++){const s=a*3;i[s]=(Math.random()-.5)*20,i[s+1]=(Math.random()-.5)*20,i[s+2]=(Math.random()-.5)*10,n[s]=(Math.random()-.5)*.002,n[s+1]=(Math.random()-.5)*.002,n[s+2]=(Math.random()-.5)*.001}const e=new p;e.setAttribute("position",new w(i,3));const o=new k({color:10265519,size:.04,transparent:!0,opacity:.6,sizeAttenuation:!0,blending:y,depthWrite:!1});this.particles=new D(e,o),this.particleVelocities=n,this.particleCount=t,this.scene.add(this.particles)}_buildEnhanced(){const t=[];for(let r=0;r<16;r++)t.push([r&1?1:-1,r&2?1:-1,r&4?1:-1,r&8?1:-1]);const i=[];for(let r=0;r<16;r++)for(let l=r+1;l<16;l++){let u=0;for(let h=0;h<4;h++)t[r][h]!==t[l][h]&&u++;u===1&&i.push([r,l])}const n=new Float32Array(i.length*6),e=new p;e.setAttribute("position",new w(n,3));const o=new v({color:13740921,transparent:!0,opacity:.08});this.tesseractLines=new b(e,o),this.tesseractData={verts4D:t,edges:i,projected:new Array(16)},this.scene.add(this.tesseractLines);const a=200,s=new Float32Array(a*6),c=new p;c.setAttribute("position",new w(s,3));const d=new v({color:13740921,transparent:!0,opacity:.06,blending:y});this.connectionLines=new b(c,d),this.connectionLines.geometry.setDrawRange(0,0),this.scene.add(this.connectionLines)}_project4Dto3D(t,i,n){const e=Math.cos(i),o=Math.sin(i);let a=t[0]*e-t[1]*o,s=t[0]*o+t[1]*e;const c=Math.cos(n),d=Math.sin(n),r=t[2]*c-t[3]*d,h=1/(4-(t[2]*d+t[3]*c));return[a*h,s*h,r*h]}_updateParticles(t){const i=this.particles.geometry.getAttribute("position"),n=i.array,e=this.particleVelocities,o=10;for(let a=0;a<this.particleCount;a++){const s=a*3;n[s]+=e[s],n[s+1]+=e[s+1],n[s+2]+=e[s+2],Math.abs(n[s])>o&&(e[s]*=-1),Math.abs(n[s+1])>o&&(e[s+1]*=-1),Math.abs(n[s+2])>o&&(e[s+2]*=-1)}i.needsUpdate=!0}_updateTesseract(t){if(!this.enhanced||!this.tesseractLines)return;const i=.08,n=t*i,e=t*i*.7,{verts4D:o,edges:a}=this.tesseractData,s=this.tesseractLines.geometry.getAttribute("position");for(let r=0;r<16;r++)this.tesseractData.projected[r]=this._project4Dto3D(o[r],n,e);let c=0;for(const[r,l]of a){const u=this.tesseractData.projected[r],h=this.tesseractData.projected[l];s.setXYZ(c,u[0]*3,u[1]*3,u[2]*3-5),s.setXYZ(c+1,h[0]*3,h[1]*3,h[2]*3-5),c+=2}s.needsUpdate=!0;const d=.06+Math.sin(t*.3)*.03;this.tesseractLines.material.opacity=d}_updateConnections(){if(!this.enhanced||!this.connectionLines)return;const t=this.particles.geometry.getAttribute("position"),i=this.connectionLines.geometry.getAttribute("position"),n=2.5;let e=0;const o=i.count;for(let a=0;a<this.particleCount&&e<o;a++)for(let s=a+1;s<this.particleCount&&e<o;s++){const c=t.array[a*3]-t.array[s*3],d=t.array[a*3+1]-t.array[s*3+1],r=t.array[a*3+2]-t.array[s*3+2];c*c+d*d+r*r<n*n&&(i.setXYZ(e,t.array[a*3],t.array[a*3+1],t.array[a*3+2]),i.setXYZ(e+1,t.array[s*3],t.array[s*3+1],t.array[s*3+2]),e+=2)}i.needsUpdate=!0,this.connectionLines.geometry.setDrawRange(0,e)}_initEvents(){window.addEventListener("mousemove",t=>{this.mouse.x=t.clientX/window.innerWidth,this.mouse.y=1-t.clientY/window.innerHeight}),window.addEventListener("scroll",()=>{this.scrollY=window.scrollY},{passive:!0}),window.addEventListener("resize",()=>{this.destroyed||!this.renderer||(this.renderer.setSize(window.innerWidth,window.innerHeight),this.bgUniforms.uResolution.value.set(window.innerWidth,window.innerHeight))}),document.addEventListener("visibilitychange",()=>{document.hidden?this.pause():this.resume()}),navigator.getBattery&&navigator.getBattery().then(t=>{const i=()=>{!t.charging&&t.level<.2&&this.setQuality(f.LOW)};t.addEventListener("levelchange",i),t.addEventListener("chargingchange",i)})}_animate(){if(this.destroyed||(this._rafId=requestAnimationFrame(()=>this._animate()),this.paused))return;const t=this.clock.getElapsedTime();this.frameCount++;const i=performance.now();i-this.lastFpsTime>=1e3&&(this.fps=this.frameCount,this.frameCount=0,this.lastFpsTime=i,this.fps<30&&this.quality!==f.LOW&&this.setQuality(f.LOW)),this.bgUniforms.uTime.value=t,this.bgUniforms.uScroll.value=this.scrollY,this.bgUniforms.uMouse.value.set(this.mouse.x,this.mouse.y),this._updateParticles(t),this._updateTesseract(t),this._updateConnections(),this.renderer.render(this.scene,this.camera)}_fallbackToCanvas2D(){this.destroyed=!0,this.canvas.style.background="#0a0a0a";const t=this.canvas.getContext("2d");if(!t)return;this.fallbackCtx=t,this.canvas.width=window.innerWidth,this.canvas.height=window.innerHeight;const i=[];for(let e=0;e<80;e++)i.push({x:Math.random()*this.canvas.width,y:Math.random()*this.canvas.height,vx:(Math.random()-.5)*.3,vy:(Math.random()-.5)*.3});const n=()=>{if(!this.destroyed){t.clearRect(0,0,this.canvas.width,this.canvas.height);for(const e of i)e.x+=e.vx,e.y+=e.vy,(e.x<0||e.x>this.canvas.width)&&(e.vx*=-1),(e.y<0||e.y>this.canvas.height)&&(e.vy*=-1),t.fillStyle="#9ca3af",t.beginPath(),t.arc(e.x,e.y,1.5,0,Math.PI*2),t.fill();for(let e=0;e<i.length;e++)for(let o=e+1;o<i.length;o++){const a=i[e].x-i[o].x,s=i[e].y-i[o].y,c=Math.sqrt(a*a+s*s);c<130&&(t.strokeStyle=`rgba(156,163,175,${1-c/130})`,t.lineWidth=.4,t.beginPath(),t.moveTo(i[e].x,i[e].y),t.lineTo(i[o].x,i[o].y),t.stroke())}requestAnimationFrame(n)}};window.addEventListener("resize",()=>{this.canvas.width=window.innerWidth,this.canvas.height=window.innerHeight}),n()}setQuality(t){this.quality=t}pause(){this.paused=!0,this.clock&&this.clock.stop()}resume(){this.paused&&(this.paused=!1,this.clock&&this.clock.start())}destroy(){this.destroyed=!0,cancelAnimationFrame(this._rafId),this.renderer&&(this.renderer.dispose(),this.scene?.traverse(t=>{t.geometry&&t.geometry.dispose(),t.material&&t.material.dispose()}))}}if(typeof window<"u"&&!window.__NOIR_BG_LAZY__){const m=new U;window.NOIR_BG={setQuality:t=>m.setQuality(t),pause:()=>m.pause(),resume:()=>m.resume()}}export{U as NoirBackground};

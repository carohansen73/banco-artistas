document.addEventListener("DOMContentLoaded",()=>{console.log("prueba de GitHub Actions");const m=document.getElementById("filter-nombre"),a=document.getElementById("filter-disciplina"),s=document.getElementById("filter-genero"),B=document.getElementById("btn-limpiar"),v=document.getElementById("contador-resultados"),f=document.querySelectorAll(".tag-disc"),w=document.getElementById("btn-ver-mas"),u=document.getElementById("ver-mas-wrap"),C=document.getElementById("ver-mas-restantes"),M=9;let y;function c(){clearTimeout(y),y=setTimeout(()=>{const t=new URLSearchParams({busqueda:m.value,disciplina:a.value,genero:s.value});fetch(`/buscador-de-artistas?${t}`).then(e=>e.json()).then(e=>{l=e,i=0;const n=document.getElementById("container-artists");if(n.innerHTML="",e.length===0){n.innerHTML=`
                            <div class="col-12 text-center text-muted py-5">
                                <p>No se encontraron artistas con esos filtros.</p>
                            </div>`,u.style.setProperty("display","none","important"),p();return}h(),p()}).catch(console.error)},300)}function h(){const t=l.slice(i,i+M);t.forEach(e=>A(e)),i+=t.length,E()}function E(){const t=l.length-i;t>0?(u.style.setProperty("display","block","important"),C.textContent=`(${t} más)`):u.style.setProperty("display","none","important")}function p(){if(!v)return;const t=l.length;v.innerHTML=t===0?'<span class="contador-texto">Sin resultados</span>':`<span class="contador-numero">${t}</span>
               <span class="contador-texto">
                    artista${t!==1?"s":""}
                </span>`}function o(t){return String(t??"").replace(/[&<>"']/g,e=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"})[e])}function A(t){const e=document.getElementById("container-artists"),n=o(t.nombre_artistico),d=o(t.localidad),r=o(t.disciplina),L=encodeURIComponent(t.slug),T=encodeURI(t.img_perfil||""),I=(t.generos||[]).map(P=>`<span class="artista-badge genero">${o(P)}</span>`).join(""),H=(t.disciplina||"").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g,"").replace(/\s+/g,"-"),x=t.img_perfil&&!t.img_perfil.includes("default")?`<img src="${T}" alt="${n}" loading="lazy">`:`<div class="artista-avatar-default">${o(t.nombre_artistico.charAt(0).toUpperCase())}</div>`,g=document.createElement("div");g.className="col-lg-4 col-md-6 col-sm-12",g.innerHTML=`
            <div class="artista-card" onclick="window.location='/artistas/${L}'">
                <div class="artista-card-img">
                    ${x}
                    <div class="artista-card-overlay">
                        <a href="/artistas/${L}" class="btn btn-red btn-sm rounded-pill">Ver perfil</a>
                    </div>
                </div>
                <div class="artista-card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="artista-card-nombre">${n}</h4>
                        ${t.disciplina?`<span class="artista-card-disciplina disc-${H}">${r}</span>`:""}
                    </div>
                    <div class="artista-card-meta">
                        ${t.localidad?`<span class="card-localidad"><i class="fas fa-map-marker-alt me-1"></i> ${d}</span>`:""}
                    </div>
                    ${I?`<div class="artista-card-generos">${I}</div>`:""}
                </div>
            </div>`,e.appendChild(g)}function b(t){a.value=t,f.forEach(e=>{e.classList.toggle("active",e.dataset.id===t)}),$(t),c()}async function $(t){if(s.innerHTML='<option value="">Todos los géneros</option>',!!t)try{(await(await fetch(`/api/generos/${t}`)).json()).forEach(d=>{const r=document.createElement("option");r.value=d.id,r.textContent=d.nombre,s.appendChild(r)})}catch(e){console.error("Error al cargar géneros:",e)}}m.addEventListener("input",c),s.addEventListener("change",c),a.addEventListener("change",()=>{b(a.value)}),f.forEach(t=>{t.addEventListener("click",()=>b(t.dataset.id))}),B.addEventListener("click",()=>{m.value="",a.value="",s.value="",$(""),c()}),w.addEventListener("click",h);let l=window.artistasIniciales||[],i=document.querySelectorAll("#container-artists > div").length;E(),p()});

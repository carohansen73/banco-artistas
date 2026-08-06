document.addEventListener("DOMContentLoaded",()=>{const d=document.getElementById("filter-nombre"),n=document.getElementById("filter-disciplina"),s=document.getElementById("filter-genero"),$=document.getElementById("btn-limpiar"),g=document.getElementById("contador-resultados"),v=document.querySelectorAll(".tag-disc"),L=document.getElementById("btn-ver-mas"),m=document.getElementById("ver-mas-wrap"),B=document.getElementById("ver-mas-restantes"),I=9;let f;function o(){clearTimeout(f),f=setTimeout(()=>{const t=new URLSearchParams({busqueda:d.value,disciplina:n.value,genero:s.value});fetch(`/buscador-de-artistas?${t}`).then(e=>e.json()).then(e=>{c=e,i=0;const a=document.getElementById("container-artists");if(a.innerHTML="",e.length===0){a.innerHTML=`
                            <div class="col-12 text-center text-muted py-5">
                                <p>No se encontraron artistas con esos filtros.</p>
                            </div>`,m.style.setProperty("display","none","important"),u();return}y(),u()}).catch(console.error)},300)}function y(){const t=c.slice(i,i+I);t.forEach(e=>w(e)),i+=t.length,h()}function h(){const t=c.length-i;t>0?(m.style.setProperty("display","block","important"),B.textContent=`(${t} más)`):m.style.setProperty("display","none","important")}function u(){if(!g)return;const t=c.length;g.innerHTML=t===0?'<span class="contador-texto">Sin resultados</span>':`<span class="contador-numero">${t}</span>
               <span class="contador-texto">
                    artista${t!==1?"s":""}
                </span>`}function w(t){const e=document.getElementById("container-artists"),a=(t.generos||[]).map(M=>`<span class="artista-badge genero">${M}</span>`).join(""),l=(t.disciplina||"").toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g,"").replace(/\s+/g,"-"),r=t.img_perfil&&!t.img_perfil.includes("default")?`<img src="${t.img_perfil}" alt="${t.nombre_artistico}" loading="lazy">`:`<div class="artista-avatar-default">${t.nombre_artistico.charAt(0).toUpperCase()}</div>`,p=document.createElement("div");p.className="col-lg-4 col-md-6 col-sm-12",p.innerHTML=`
            <div class="artista-card" onclick="window.location='/artistas/${t.slug}'">
                <div class="artista-card-img">
                    ${r}
                    <div class="artista-card-overlay">
                        <a href="/artistas/${t.slug}" class="btn btn-red btn-sm rounded-pill">Ver perfil</a>
                    </div>
                </div>
                <div class="artista-card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="artista-card-nombre">${t.nombre_artistico}</h4>
                        ${t.disciplina?`<span class="artista-card-disciplina disc-${l}">${t.disciplina}</span>`:""}
                    </div>
                    <div class="artista-card-meta">
                        ${t.localidad?`<span class="card-localidad"><i class="fas fa-map-marker-alt me-1"></i> ${t.localidad}</span>`:""}
                    </div>
                    ${a?`<div class="artista-card-generos">${a}</div>`:""}
                </div>
            </div>`,e.appendChild(p)}function E(t){n.value=t,v.forEach(e=>{e.classList.toggle("active",e.dataset.id===t)}),b(t),o()}async function b(t){if(s.innerHTML='<option value="">Todos los géneros</option>',!!t)try{(await(await fetch(`/api/generos/${t}`)).json()).forEach(l=>{const r=document.createElement("option");r.value=l.id,r.textContent=l.nombre,s.appendChild(r)})}catch(e){console.error("Error al cargar géneros:",e)}}d.addEventListener("input",o),s.addEventListener("change",o),n.addEventListener("change",()=>{E(n.value)}),v.forEach(t=>{t.addEventListener("click",()=>E(t.dataset.id))}),$.addEventListener("click",()=>{d.value="",n.value="",s.value="",b(""),o()}),L.addEventListener("click",y);let c=window.artistasIniciales||[],i=document.querySelectorAll("#container-artists > div").length;h(),u()});

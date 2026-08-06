document.addEventListener("DOMContentLoaded",function(){const f=document.querySelectorAll(".tab-btn"),w=document.querySelectorAll(".tab-content"),L=sessionStorage.getItem("artista_edit_tab")||"info";p(L),f.forEach(t=>{t.addEventListener("click",function(){const e=this.dataset.tab;p(e),sessionStorage.setItem("artista_edit_tab",e)})});function p(t){f.forEach(e=>{e.classList.toggle("active",e.dataset.tab===t)}),w.forEach(e=>{e.style.display=e.id==="tab-"+t?"":"none"})}const b=document.getElementById("disciplina_id");b&&b.addEventListener("change",function(){I(this.value,[])});function I(t,e){const o=document.getElementById("generos-container"),n=document.getElementById("generos-lista");if(!t){o.style.display="none",n.innerHTML="";return}fetch(`/api/generos/${t}`).then(i=>i.json()).then(i=>{if(n.innerHTML="",i.length===0){o.style.display="none";return}i.forEach(a=>{const x=e.includes(a.id)?"checked":"";n.innerHTML+=`
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox"
                                name="generos[]" value="${a.id}"
                                id="genero_${a.id}" ${x}>
                            <label class="form-check-label" for="genero_${a.id}">
                                ${a.nombre}
                            </label>
                        </div>`}),o.style.display="block"})}const v=document.getElementById("tiene_formacion");v&&v.addEventListener("change",function(){const t=document.getElementById("detalle-formacion-container");t.style.display=this.value==="1"?"":"none"});const y=document.getElementById("img_perfil");y&&y.addEventListener("change",function(){const t=this.files[0];if(t){const e=new FileReader;e.onload=o=>{document.getElementById("img-preview").src=o.target.result,document.getElementById("preview-container").style.display=""},e.readAsDataURL(t)}});const d=document.getElementById("fotos"),r=document.getElementById("fotos-preview");let s=new DataTransfer;d&&d.addEventListener("change",function(){Array.from(this.files).forEach(t=>{s.items.add(t)}),d.files=s.files,g()}),r&&r.addEventListener("click",function(t){if(!t.target.classList.contains("remove-foto"))return;const e=Number(t.target.dataset.index),o=new DataTransfer;Array.from(s.files).forEach((n,i)=>{i!==e&&o.items.add(n)}),s=o,d.files=s.files,g()});function g(){r&&(r.innerHTML="",Array.from(s.files).forEach((t,e)=>{const o=new FileReader;o.onload=n=>{const i=document.createElement("div");i.className="galeria-store-item position-relative",i.innerHTML=`
                    <img src="${n.target.result}"
                        class="galeria-store-img">
                    <button type="button" class="btn btn-sm btn-danger remove-foto"
                        data-index="${e}"
                        style="position:absolute; top:4px; right:4px; padding:0 6px; line-height:1.6;">
                        ×
                    </button>`,r.appendChild(i)},o.readAsDataURL(t)}))}const h=document.getElementById("add-track");h&&h.addEventListener("click",function(){document.getElementById("tracks-container").insertAdjacentHTML("beforeend",`
                <div class="row track-row mb-3">
                    <div class="col-sm-6 p-2">
                        <input type="url" name="tracks[]" class="form-control"
                            placeholder="https://open.spotify.com/track/...">
                    </div>
                    <div class="col-sm-5 p-2">
                        <input type="text" name="tracks_titulo[]" class="form-control"
                            placeholder="Título de la canción (opcional)">
                    </div>
                    <div class="col-sm-1 p-2 d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">✕</button>
                    </div>
                </div>`)});const E=document.getElementById("add-video");E&&E.addEventListener("click",function(){document.getElementById("videos-container").insertAdjacentHTML("beforeend",`
                <div class="row video-row mb-3">
                    <div class="col-sm-6 p-2">
                        <input type="url" name="videos[]" class="form-control"
                            placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="col-sm-5 p-2">
                        <input type="text" name="videos_titulo[]" class="form-control"
                            placeholder="Título del video (opcional)">
                    </div>
                    <div class="col-sm-1 p-2 d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">✕</button>
                    </div>
                </div>`)}),document.addEventListener("click",function(t){t.target.classList.contains("remove-row")&&t.target.closest(".track-row, .video-row").remove()});let l=null,c=null;const T=new bootstrap.Modal(document.getElementById("modalEliminar"));document.addEventListener("click",function(t){const e=t.target.closest(".btn-delete-media");e&&(l=e.dataset.url,c=e.closest(".media-item"),T.show())}),document.getElementById("btn-confirmar-eliminar").addEventListener("click",function(){if(!l)return;const t=document.querySelector('meta[name="csrf-token"]')?.content;fetch(l,{method:"DELETE",headers:{"X-CSRF-TOKEN":t,Accept:"application/json"}}).then(e=>e.json()).then(e=>{e.success?(T.hide(),c&&(c.style.transition="opacity 0.3s",c.style.opacity="0",setTimeout(()=>{c.remove(),k()},300)),u("Elemento eliminado correctamente.")):u("Hubo un error al eliminar. Intentá nuevamente.","danger")}).catch(()=>{u("Error de conexión. Intentá nuevamente.","danger")}).finally(()=>{l=null,c=null})});function k(){Object.entries({fotos:{container:"galeria-fotos",btnTab:"fotos"},videos:{container:"lista-videos",btnTab:"videos"},audios:{container:"lista-audios",btnTab:"audios"}}).forEach(([,e])=>{const o=document.getElementById(e.container);if(!o)return;const n=o.querySelectorAll(".media-item").length,i=document.querySelector(`.tab-btn[data-tab="${e.btnTab}"]`);if(!i)return;const a=i.querySelector(".badge");n>0?a?a.textContent=n:i.insertAdjacentHTML("beforeend",`<span class="badge bg-secondary ms-1">${n}</span>`):a?.remove()})}function u(t,e="success"){const o=document.getElementById("edit-toast");o&&o.remove();const n=document.createElement("div");n.id="edit-toast",n.style.cssText=`
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 260px;
            text-align: center;
        `,n.innerHTML=`
            <div class="alert alert-${e} shadow mb-0 py-2 px-4 rounded-pill">
                ${t}
            </div>`,document.body.appendChild(n),setTimeout(()=>{n.style.transition="opacity 0.4s",n.style.opacity="0",setTimeout(()=>n.remove(),400)},2800)}const m=document.getElementById("alert-success");m&&setTimeout(()=>{m.style.transition="opacity 0.5s",m.style.opacity="0",setTimeout(()=>m.remove(),500)},3e3)});

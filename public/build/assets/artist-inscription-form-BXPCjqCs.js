document.addEventListener("DOMContentLoaded",function(){const c=document.getElementById("disciplina_id");c&&c.addEventListener("change",function(){const e=this.value,n=document.getElementById("generos-container"),t=document.getElementById("generos-lista");if(!e){n.style.display="none",t.innerHTML="";return}fetch(`/api/generos/${e}`).then(i=>i.json()).then(i=>{if(t.innerHTML="",i.length===0){n.style.display="none";return}i.forEach(o=>{t.innerHTML+=`
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox"
                                name="generos[]" value="${o.id}" id="genero_${o.id}">
                            <label class="form-check-label" for="genero_${o.id}">
                                ${o.nombre}
                            </label>
                        </div>`}),n.style.display="block"})}),document.getElementById("tiene_formacion").addEventListener("change",function(){const e=document.getElementById("detalle-formacion-container");e.style.display=this.value==="1"?"block":"none"});const d=document.getElementById("img_perfil");d&&d.addEventListener("change",function(){const e=this.files[0];if(e){const n=new FileReader;n.onload=t=>{document.getElementById("img-preview").src=t.target.result,document.getElementById("preview-container").style.display="block"},n.readAsDataURL(e)}})});

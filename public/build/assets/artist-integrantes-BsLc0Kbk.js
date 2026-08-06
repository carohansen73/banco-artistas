document.addEventListener("DOMContentLoaded",function(){const e=document.getElementById("integrantes-lista"),a=document.getElementById("btn-add-integrante");if(!e||!a)return;function i(t=""){const n=document.createElement("div");return n.className="d-flex align-items-center gap-2 mb-2 integrante-row",n.innerHTML=`
            <input type="text" name="integrantes[]"
                class="form-control"
                placeholder="Nombre del integrante"
                value="${t}">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-integrante">&times;</button>
        `,n}a.addEventListener("click",function(){e.appendChild(i())}),e.addEventListener("click",function(t){t.target.classList.contains("btn-remove-integrante")&&t.target.closest(".integrante-row").remove()})});

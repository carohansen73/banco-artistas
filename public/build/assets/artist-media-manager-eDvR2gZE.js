const i=document.getElementById("fotos"),r=document.getElementById("fotos-preview");let t=new DataTransfer;function c(){r.innerHTML="",Array.from(t.files).forEach((e,s)=>{const o=new FileReader;o.onload=a=>{const n=document.createElement("div");n.className="galeria-store-item position-relative",n.innerHTML=`
                <img src="${a.target.result}"
                    class="galeria-store-img">
                <button type="button" class="btn btn-sm btn-danger remove-foto"
                    data-index="${s}"
                    style="position:absolute; top:4px; right:4px; padding:0 6px; line-height:1;">
                    ×
                </button>`,r.appendChild(n)},o.readAsDataURL(e)})}i.addEventListener("change",function(){Array.from(this.files).forEach(e=>{t.items.add(e)}),i.files=t.files,c()});r.addEventListener("click",function(e){if(!e.target.classList.contains("remove-foto"))return;const s=Number(e.target.dataset.index),o=new DataTransfer;Array.from(t.files).forEach((a,n)=>{n!==s&&o.items.add(a)}),t=o,i.files=t.files,c()});document.getElementById("add-track").addEventListener("click",function(){document.getElementById("tracks-container").insertAdjacentHTML("beforeend",`
        <div class="row track-row mb-3">
            <div class="col-12  col-sm-6 p-2">
                <input type="url" name="tracks[]" class="form-control"
                    placeholder="https://open.spotify.com/track/...">
            </div>
            <div class="col-10 col-sm-5 p-2">
                <input type="text" name="tracks_titulo[]" class="form-control"
                    placeholder="Título de la canción (opcional)">
            </div>
            <div class="col-1 p-2 ps-1 ps-sm-2 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger remove-row" style="margin-top:3px;">✕</button>
            </div>
        </div>`)});document.getElementById("add-video").addEventListener("click",function(){document.getElementById("videos-container").insertAdjacentHTML("beforeend",`
        <div class="row video-row mb-3">
            <div class="col-12 col-sm-6 p-2">
                <input type="url" name="videos[]" class="form-control"
                    placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <div class="col-10 col-sm-5 p-2">
                <input type="text" name="videos_titulo[]" class="form-control"
                    placeholder="Título del video (opcional)">
            </div>
            <div class="col-1 p-2 ps-1 ps-sm-2 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger remove-row" style="margin-top:3px;">✕</button>
            </div>
        </div>`)});document.addEventListener("click",function(e){e.target.classList.contains("remove-row")&&e.target.closest(".track-row, .video-row").remove()});

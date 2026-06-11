<!-- ================= MODAL SETUJUI ================= -->
<div id="modalSetujui" class="modal-hapus">
    <div class="modal-box">
        <h3>Konfirmasi Persetujuan</h3>
        <p>Apakah Anda yakin ingin menyetujui data ini?</p>

        <div class="modal-actions">
            <button type="button" class="btn-batal" onclick="closeModalSetujui()">Batal</button>
            <a id="btnYaSetujui" class="btn-hapus-yes">Ya, Setujui</a>
        </div>
    </div>
</div>

<!-- ================= MODAL TOLAK ================= -->
<div id="modalTolak" class="modal-hapus">
    <div class="modal-box">
        <h3>Konfirmasi Penolakan</h3>
        <p>Apakah Anda yakin ingin menolak data ini?</p>

        <div class="modal-actions">
            <button type="button" class="btn-batal" onclick="closeModalTolak()">Batal</button>
            <a id="btnYaTolak" class="btn-hapus-yes">Ya, Tolak</a>
        </div>
    </div>
</div>

<style>
/* Tombol Ya, Setujui */
#btnYaSetujui {
    display: inline-block;
    background-color: #28a745;
    color: #fff;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

#btnYaSetujui:hover {
    background-color: #218838;
}

/* Tombol Ya, Tolak */
#btnYaTolak {
    display: inline-block;
    background-color: #dc3545;
    color: #fff;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

#btnYaTolak:hover {
    background-color: #c82333;
}

.modal-hapus {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
</style>

<script>
function confirmSetujui(url) {
    document.getElementById("modalSetujui").style.display = "block";
    document.getElementById("btnYaSetujui").href = url;
}

function closeModalSetujui() {
    document.getElementById("modalSetujui").style.display = "none";
}

function confirmTolak(url) {
    document.getElementById("modalTolak").style.display = "block";
    document.getElementById("btnYaTolak").href = url;
}

function closeModalTolak() {
    document.getElementById("modalTolak").style.display = "none";
}
</script>
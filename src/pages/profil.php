<?php if(isset($_SESSION['flash_message'])): ?>
<div id="flash-message" class="alert-success">
    <?= $_SESSION['flash_message']; ?>
</div>
<?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>


<?php if(isset($_SESSION['flash_message'])): ?>
<div id="flash-message" class="alert <?= $_SESSION['flash_type'] ?? 'success'; ?>">
    <?= $_SESSION['flash_message']; ?>
</div>
<?php 
unset($_SESSION['flash_message']); 
unset($_SESSION['flash_type']);
?>
<?php endif; ?>

<style>
.alert-success {
    position: fixed;
    top: 80px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #4CAF50;
    color: white;
    padding: 15px 30px;
    border-radius: 10px;
    font-size: 16px;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.alert {
    position: fixed;
    top: 80px;
    left: 50%;
    transform: translateX(-50%);
    padding: 15px 30px;
    border-radius: 10px;
    color: white;
    z-index: 9999;
}

/* sukses */
.alert.success {
    background-color: #4CAF50;
}

/* hapus */
.alert.danger {
    background-color: #e74c3c;
}

.modal-hapus {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.modal-box {
    background: white;
    padding: 25px;
    border-radius: 12px;
    width: 350px;
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-box h3 {
    margin-bottom: 10px;
    color: #333;
}

.modal-box p {
    color: #666;
    margin-bottom: 20px;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn-batal {
    background: #ccc;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-batal:hover {
    background: #999;
}

.btn-hapus-yes {
    background: #e74c3c;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
}

.btn-hapus-yes:hover {
    background: #c0392b;
}
</style>

<div class="profil-container">
    <div class="profil-box">
        <form method="POST" action="../src/controllers/PenggunaController.php?action=update"
            enctype="multipart/form-data" class="profil-card">

            <!-- FOTO PROFIL -->
            <div class="profil-foto">
                <img id="previewFoto" src="../uploads/<?= $user['foto']; ?>?<?= time(); ?>" alt="Foto Profil">
                <input type="file" name="foto_profil" accept="image/*" id="inputFoto" style="display:none;"
                    onchange="previewImage(event)">
            </div>

            <!-- DATA PROFIL -->
            <div class="profil-data">
                <input type="hidden" name="id_pengguna" value="<?= $_SESSION['user']['id_pengguna'] ?>">

                <!-- agar diubah dari profil -->
                <input type="hidden" name="from" value="profil">

                <div class="data-item">
                    <span>Nama Lengkap</span>
                    <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>" readonly>
                </div>
                <div class="data-item">
                    <span>Email</span>
                    <input type="email" name="email" value="<?= $user['email']; ?>" readonly>
                </div>
                <div class="data-item">
                    <span>No HP</span>
                    <input type="text" name="no_hp" value="<?= $user['no_hp']; ?>" readonly>
                </div>
                <div class="data-item">
                    <span>Alamat</span>
                    <input type="text" name="alamat" value="<?= $user['alamat']; ?>" readonly>
                </div>
                <div class="data-item">
                    <span>Nama Pengguna</span>
                    <input type="text" name="nama_pengguna" value="<?= $user['nama_pengguna']; ?>" readonly>
                </div>
                <div class="data-item">
                    <span>Kata Sandi Baru</span>
                    <input type="password" name="kata_sandi" placeholder="Kosongkan jika tidak diubah" readonly>
                </div>

                <div class="profil-action">
                    <button type="button" class="profil-btn profil-btn-edit" onclick="editProfil()">
                        Ubah
                    </button>

                    <button type="submit" class="profil-btn profil-btn-simpan" id="btnSimpan" style="display:none;">
                        Simpan
                    </button>

                    <button type="button" class="profil-btn profil-btn-batal" id="btnBatal" onclick="batalEdit()"
                        style="display:none;">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
setTimeout(function() {
    var msg = document.getElementById('flash-message');
    if (msg) {
        msg.style.display = 'none';
    }
}, 3000); // hilang 3 detik


function confirmHapus(id) {
    document.getElementById("modalHapus").style.display = "block";

    // set link hapus
    document.getElementById("btnYaHapus").href =
        "../src/controllers/PengumumanController.php?action=hapus&id=" + id;
}

function closeModalHapus() {
    document.getElementById("modalHapus").style.display = "none";
}

// klik luar modal = tutup
window.onclick = function(event) {
    const modal = document.getElementById("modalHapus");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}

// simpan foto awal (biar bisa dikembalikan saat batal)
let fotoAwal = document.getElementById('previewFoto').src;

// ================= PREVIEW FOTO =================
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('previewFoto').src = reader.result;
    };

    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

// ================= EDIT =================
function editProfil() {
    // aktifkan input
    document.querySelectorAll('.profil-data input').forEach(input => {
        input.removeAttribute('readonly');
    });

    // tampilkan input foto
    document.getElementById('inputFoto').style.display = 'block';

    // tombol
    document.getElementById('btnSimpan').style.display = 'inline-block';
    document.getElementById('btnBatal').style.display = 'inline-block';
    document.querySelector('.profil-btn-edit').style.display = 'none';
}

// ================= BATAL =================
function batalEdit() {
    // tombol
    document.getElementById('btnSimpan').style.display = 'none';
    document.getElementById('btnBatal').style.display = 'none';
    document.querySelector('.profil-btn-edit').style.display = 'inline-block';

    // readonly lagi
    document.querySelectorAll('.profil-data input').forEach(input => {
        input.setAttribute('readonly', true);
    });

    // reset form
    document.querySelector('.profil-card').reset();

    // sembunyikan input foto
    document.getElementById('inputFoto').style.display = 'none';

    // 🔥 kembalikan foto ke semula
    document.getElementById('previewFoto').src = fotoAwal;
}
</script>
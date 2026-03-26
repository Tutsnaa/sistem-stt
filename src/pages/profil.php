<div class="profil-container">
    <div class="profil-box">
        <h2>PROFIL</h2>
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
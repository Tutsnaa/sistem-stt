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
                    <button type="button" class="btn-edit" onclick="editProfil()">Ubah Profil</button>
                    <button type="submit" class="btn-simpan" id="btnSimpan" style="display:none;">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// preview foto sebelum disimpan
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('previewFoto').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function editProfil() {
    document.querySelectorAll('.profil-data input').forEach(input => input.removeAttribute('readonly'));
    document.getElementById('inputFoto').style.display = 'block';
    document.getElementById('btnSimpan').style.display = 'inline-block';
}
</script>
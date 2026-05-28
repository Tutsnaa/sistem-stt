    <div class="container">

        <!-- =========================
        MODAL TAMBAH ANGGOTA
    ========================== -->
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
        <?php
$old = $_SESSION['old_input'] ?? [];
?>
        <div id="tambahModal" class="modal">
            <div class="modal-content">

                <span class="close" onclick="closeTambahModal()">&times;</span>

                <h3>Tambah Anggota</h3>

                <!-- PESAN ERROR -->
                <?php if(isset($_SESSION['error_tambah'])): ?>
                <div class="alert-error">
                    <?= $_SESSION['error_tambah']; ?>
                </div>
                <?php unset($_SESSION['error_tambah']); ?>
                <?php endif; ?>

                <form class="form-anggota" action="../src/controllers/PenggunaController.php?action=create"
                    method="POST" enctype="multipart/form-data">

                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($old['nama_lengkap'] ?? '') ?>"
                        required>

                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>

                    <label>No HP</label>
                    <input type="text" name="no_hp" value="<?= htmlspecialchars($old['no_hp'] ?? '') ?>" required>

                    <label>Alamat</label>
                    <input type="text" name="alamat" value="<?= htmlspecialchars($old['alamat'] ?? '') ?>" required>

                    <!-- <label>Jabatan</label>
                    <select name="jabatan" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="ketua">Ketua</option>
                        <option value="wakil">Wakil</option>
                        <option value="sekretaris 1">Sekretaris 1</option>
                        <option value="sekretaris 2">Sekretaris 2</option>
                        <option value="bendahara 1">Bendahara 1</option>
                        <option value="bendahara 2">Bendahara 2</option>
                        <option value="anggota">Anggota</option>
                    </select> -->

                    <label>Username</label>
                    <input type="text" name="nama_pengguna" value="<?= htmlspecialchars($old['nama_pengguna'] ?? '') ?>"
                        required>

                    <label>Password</label>
                    <input type="password" name="kata_sandi" required>

                    <label>Foto</label>
                    <input type="file" name="foto_profil" accept="image/*" required>

                    <button type="submit" class="btn-save">Simpan</button>

                </form>

                <?php unset($_SESSION['old_input']); ?>

            </div>
        </div>
        <?php } ?>

        <!-- Data admin tidak tampil -->
        <?php
$dataAnggotaFiltered = array_filter($dataAnggota, function($row) {
    return strtolower($row['jabatan']) !== 'admin';
});
?>
        <!-- TABEL DATA ANGGOTA -->
        <div id="anggota" class="section-anggota">

            <h2>Data Anggota</h2>

            <!-- FORM PENCARIAN -->
            <form method="GET" action="" class="search-box">
                <input type="hidden" name="page" value="anggota">
                <input type="text" name="search" placeholder="Cari nama anggota..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Cari</button>
                <a href="<?= ($_SESSION['user']['jabatan'] == 'anggota') 
            ? 'dashboard_anggota.php?page=anggota' 
            : 'dashboard_pengurus.php?page=anggota'; ?>" class="btn-reset">
                    <i class="fa fa-rotate-right"></i>
                </a>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
                <a href="../src/controllers/PenggunaController.php?action=download_excel" class="btn-download">
                    Unduh Data
                </a>
                <?php } ?>

            </form>
            <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'anggota' &&
    $_SESSION['user']['jabatan'] != 'bendahara 1' &&
    $_SESSION['user']['jabatan'] != 'bendahara 2'
) { 
?>

            <button class="btn-tambah" onclick="openTambahModal()">+ Tambah Anggota</button>
            <?php } ?>


            <!-- TABEL DATA ANGGOTA -->
            <div class="table-wrapper">
                <table class="anggota-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Lengkap</th>
                            <th>No HP</th>
                            <th>Jabatan</th>
                            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>

                            <th>Status</th>
                            <?php } ?>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                $no = 1;
                if(!empty($dataAnggotaFiltered)){
    foreach($dataAnggotaFiltered as $row){
                ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td style="text-align: center;">
                                <?php if(!empty($row['foto'])){ ?>
                                <img src="../uploads/<?= $row['foto']; ?>" width="50" height="50">
                                <?php } else { ?>
                                <img src="../asset/img/default.png" width="50" height="50">
                                <?php } ?>
                            </td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>


                            <td style="text-align: center;"><?= htmlspecialchars($row['no_hp']); ?></td>


                            <td style="text-align: center;"><?= htmlspecialchars($row['jabatan']); ?></td>
                            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>

                            <td style="text-align: center;">
                                <span class="status <?= $row['status'] == 'aktif' ? 'aktif' : 'nonaktif' ?>">
                                    <?= ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <?php } ?>
                            <td>
                                <div class="aksi-btn">
                                    <!-- DETAIL -->
                                    <button class="btn-detail"
                                        data-nama="<?= htmlspecialchars($row['nama_lengkap']); ?>"
                                        data-email="<?= htmlspecialchars($row['email']); ?>"
                                        data-hp="<?= htmlspecialchars($row['no_hp']); ?>"
                                        data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
                                        data-jabatan="<?= htmlspecialchars($row['jabatan']); ?>"
                                        data-nama_pengguna="<?= htmlspecialchars($row['nama_pengguna']); ?>"
                                        data-status="<?= htmlspecialchars($row['status']); ?>"
                                        data-foto="<?= htmlspecialchars($row['foto']); ?>"
                                        onclick="openDetailModal(this)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'anggota' &&
    $_SESSION['user']['jabatan'] != 'bendahara 1' &&
    $_SESSION['user']['jabatan'] != 'bendahara 2'
) { 
?>

                                    <!-- UBAH -->
                                    <button class="btn-edit" data-id="<?= htmlspecialchars($row['id_pengguna']); ?>"
                                        data-nama="<?= htmlspecialchars($row['nama_lengkap']); ?>"
                                        data-email="<?= htmlspecialchars($row['email']); ?>"
                                        data-hp="<?= htmlspecialchars($row['no_hp']); ?>"
                                        data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
                                        data-nama_pengguna="<?= htmlspecialchars($row['nama_pengguna']); ?>"
                                        data-status="<?= htmlspecialchars($row['status']); ?>"
                                        data-foto="<?= htmlspecialchars($row['foto']); ?>"
                                        onclick="openEditModal(this)">
                                        <i class="fa fa-pen-to-square"></i>
                                    </button>

                                    <!-- HAPUS -->
                                    <a href="#" class="btn-hapus"
                                        onclick="confirmHapus(<?= $row['id_pengguna']; ?>); return false;">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <?php } ?>
                                </div>
                            </td>

                        </tr>
                        <?php
                    }
                }else{
                ?>
                        <tr>
                            <td colspan="9" align="center">Data anggota tidak ditemukan</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


            <!-- =========================
            POPUP DETAIL ANGGOTA
        ========================== -->
            <div id="detailModal" class="detail-modal">
                <div class="detail-modal-content">
                    <span class="detail-close" onclick="closeDetailModal()">&times;</span>
                    <h3>Detail Anggota</h3>
                    <div class="detail-body">

                        <!-- KIRI (FOTO) -->
                        <div class="detail-left">
                            <div class="foto-wrapper" onclick="openFotoModal(document.getElementById('d_foto'))">
                                <img id="d_foto" src="../asset/img/default.png" alt="Foto">
                                <div class="foto-overlay">
                                    <i class="fa fa-search-plus"></i>
                                </div>
                            </div>
                        </div>

                        <!-- KANAN (DATA) -->
                        <div class="detail-right">
                            <p><b>Nama:</b> <span id="d_nama"></span></p>
                            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
                            <p><b>Email:</b> <span id="d_email"></span></p>
                            <?php } ?>
                            <p><b>No HP:</b> <span id="d_hp"></span></p>
                            <p><b>Alamat:</b> <span class="alamat-box" id="d_alamat"></span></p>
                            <p><b>Jabatan:</b> <span id="d_jabatan"></span></p>
                            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
                            <p><b>Nama Pengguna:</b> <span id="d_nama_pengguna"></span></p>
                            <p><b>Status:</b> <span id="d_status"></span></p>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>

            <!-- MODAL FULL FOTO -->
            <div id="fotoModal" class="foto-modal">
                <span class="foto-close" onclick="closeFotoModal()">&times;</span>
                <img class="foto-full" id="fotoFull">
            </div>

            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
            <!-- =========================
            FORM UBAH DATA ANGGOTA
            ========================== -->
            <div id="editModal" class="modal">

                <div class="modal-content">

                    <span class="close" onclick="closeModalEdit()">&times;</span>

                    <h3>Ubah Data Anggota</h3>

                    <form action="../src/controllers/PenggunaController.php?action=update" method="POST"
                        enctype="multipart/form-data">

                        <input type="hidden" name="id_pengguna" id="edit_id">

                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit_nama">

                        <label>Email</label>
                        <input type="email" name="email" id="edit_email">

                        <label>No HP</label>
                        <input type="text" name="no_hp" id="edit_hp">

                        <label>Alamat</label>
                        <input type="text" name="alamat" id="edit_alamat">

                        <label>Nama Pengguna</label>
                        <input type="text" name="nama_pengguna" id="edit_nama_pengguna">

                        <label>Password Baru</label>
                        <input type="password" name="kata_sandi">
                        <small>Kosongkan jika tidak ingin mengganti password</small>

                        <label>Status</label>
                        <select name="status" id="edit_status">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        <!-- <label>Foto Saat Ini</label><br>
                        <img id="preview_edit_foto" src="" width="120" style="margin-bottom:10px;"><br> -->

                        <label>Foto Baru</label>
                        <input type="file" name="foto_profil" id="edit_foto">

                        <br><br>

                        <button type="submit" class="btn-save">Simpan</button>

                    </form>

                </div>
            </div>
            <?php } ?>

        </div>

        <div id="modalHapus" class="modal-hapus">
            <div class="modal-box">
                <h3>Konfirmasi Hapus</h3>
                <p>Apakah kamu yakin ingin menghapus data anggota ini?</p>

                <div class="modal-actions">
                    <button class="btn-batal" onclick="closeModalHapus()">Batal</button>
                    <a id="btnYaHapus" class="btn-hapus-yes">Ya, Hapus</a>
                </div>
            </div>
        </div>

        <script>
        // ================= MODAL HAPUS =================
        function confirmHapus(id) {
            const modal = document.getElementById("modalHapus");
            modal.style.display = "block";
            document.getElementById("btnYaHapus").href =
                "../src/controllers/PenggunaController.php?action=delete&id=" + id;
        }

        function closeModalHapus() {
            document.getElementById("modalHapus").style.display = "none";
        }

        // ================= MODAL EDIT =================
        function openEditModal(button) {
            const editModal = document.getElementById("editModal");
            editModal.style.display = "flex";

            document.getElementById("edit_id").value = button.dataset.id;
            document.getElementById("edit_nama").value = button.dataset.nama;
            document.getElementById("edit_email").value = button.dataset.email;
            document.getElementById("edit_hp").value = button.dataset.hp;
            document.getElementById("edit_alamat").value = button.dataset.alamat;
            document.getElementById("edit_nama_pengguna").value = button.dataset.nama_pengguna;
            document.getElementById("edit_status").value = button.dataset.status;

            document.getElementById("preview_edit_foto").src =
                "../uploads/" + (button.dataset.foto || "default.png");
        }

        function closeModalEdit() {
            document.getElementById("editModal").style.display = "none";
        }

        // ================= MODAL TAMBAH =================
        function openTambahModal() {
            document.getElementById("tambahModal").style.display = "flex";
        }

        function closeTambahModal() {
            document.getElementById("tambahModal").style.display = "none";
        }

        // ================= MODAL DETAIL =================
        function openDetailModal(btn) {

            const isAdmin = document.getElementById("d_email") !== null;

            document.getElementById("d_nama").innerText = btn.dataset.nama;
            document.getElementById("d_hp").innerText = btn.dataset.hp;
            document.getElementById("d_alamat").innerText = btn.dataset.alamat;
            document.getElementById("d_jabatan").innerText = btn.dataset.jabatan;

            // EMAIL hanya kalau elemen ada (admin/pengurus)
            if (isAdmin) {
                document.getElementById("d_email").innerText = btn.dataset.email;
            }

            const namaPengguna = document.getElementById("d_nama_pengguna");
            const status = document.getElementById("d_status");

            if (namaPengguna) {
                namaPengguna.innerText = btn.dataset.nama_pengguna;
            }

            if (status) {
                status.innerText = btn.dataset.status;
            }

            const fotoEl = document.getElementById("d_foto");
            fotoEl.src = btn.dataset.foto ?
                "../uploads/" + btn.dataset.foto :
                "../asset/img/default.png";

            document.getElementById("detailModal").classList.add("show");
        }

        function closeDetailModal() {
            document.getElementById("detailModal").classList.remove("show");
        }

        // ================= MODAL FOTO =================
        function openFotoModal(img) {
            const fotoModal = document.getElementById("fotoModal");
            const fotoFull = document.getElementById("fotoFull");
            fotoFull.src = img.src;
            fotoModal.style.display = "flex";
        }

        function closeFotoModal() {
            document.getElementById("fotoModal").style.display = "none";
        }

        // ================= KLIK LUAR MODAL UNTUK CLOSE =================
        window.addEventListener("click", function(event) {
            const hapusModal = document.getElementById("modalHapus");
            const editModal = document.getElementById("editModal");
            const tambahModal = document.getElementById("tambahModal");
            const detailModal = document.getElementById("detailModal");
            const fotoModal = document.getElementById("fotoModal");

            if (event.target === hapusModal) closeModalHapus();
            if (event.target === editModal) closeModalEdit();
            if (event.target === tambahModal) closeTambahModal();
            if (event.target === detailModal) closeDetailModal();
            if (event.target === fotoModal) closeFotoModal();
        });



        window.addEventListener("DOMContentLoaded", function() {

            const params = new URLSearchParams(window.location.search);

            if (params.get('modal') === 'tambah') {
                openTambahModal();
            }

        });
        </script>
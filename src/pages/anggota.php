<div class="container">

    <!-- =========================
     MODAL TAMBAH ANGGOTA
========================== -->
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
    <div id="tambahModal" class="modal">

        <div class="modal-content">

            <span class="close" onclick="closeTambahModal()">&times;</span>

            <h2>Tambah Anggota</h2>

            <form class="form-anggota" action="../src/controllers/PenggunaController.php?action=create" method="POST"
                enctype="multipart/form-data">

                <table>

                    <tr>
                        <td>Nama Lengkap</td>
                        <td><input type="text" name="nama_lengkap" required></td>
                    </tr>

                    <tr>
                        <td>Email</td>
                        <td><input type="email" name="email"></td>
                    </tr>

                    <tr>
                        <td>No HP</td>
                        <td><input type="text" name="no_hp"></td>
                    </tr>

                    <tr>
                        <td>Alamat</td>
                        <td><input type="text" name="alamat"></td>
                    </tr>

                    <tr>
                        <td>Jabatan</td>
                        <td>
                            <select name="jabatan" required>

                                <option value="">-- Pilih Jabatan --</option>
                                <option value="ketua">Ketua</option>
                                <option value="wakil">Wakil</option>
                                <option value="sekretaris 1">Sekretaris 1</option>
                                <option value="sekretaris 2">Sekretaris 2</option>
                                <option value="bendahara 1">Bendahara 1</option>
                                <option value="bendahara 2">Bendahara 2</option>
                                <option value="anggota">Anggota</option>

                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Username</td>
                        <td><input type="text" name="nama_pengguna" required></td>
                    </tr>

                    <tr>
                        <td>Password</td>
                        <td><input type="password" name="kata_sandi" required></td>
                    </tr>

                    <tr>
                        <td>Foto</td>
                        <td>
                            <input type="file" name="foto_profil" accept="image/*">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn-save">
                                Simpan
                            </button>
                        </td>
                    </tr>

                </table>

            </form>

        </div>

    </div>
    <?php } ?>

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
                Tampilkan Semua
            </a>
        </form>
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
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
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Jabatan</th>
                        <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
                        <th>Username</th>
                        <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
            $no = 1;
            if(!empty($dataAnggota)){
                foreach($dataAnggota as $row){
            ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td style="text-align: none;">
                            <?php if(!empty($row['foto'])){ ?>
                            <img src="../uploads/<?= $row['foto']; ?>" width="50" height="50">
                            <?php } else { ?>
                            <img src="../asset/img/default.png" width="50" height="50">
                            <?php } ?>
                        </td>
                        <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($row['no_hp']); ?></td>
                        <td class="alamat"><?= htmlspecialchars($row['alamat']); ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($row['jabatan']); ?></td>
                        <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
                        <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
                        <td>
                            <div class="aksi-btn">
                                <!-- DETAIL -->
                                <button class="btn-detail" data-nama="<?= htmlspecialchars($row['nama_lengkap']); ?>"
                                    data-email="<?= htmlspecialchars($row['email']); ?>"
                                    data-hp="<?= htmlspecialchars($row['no_hp']); ?>"
                                    data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
                                    data-jabatan="<?= htmlspecialchars($row['jabatan']); ?>"
                                    data-foto="<?= htmlspecialchars($row['foto']); ?>" onclick="openDetailModal(this)">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <!-- UBAH -->
                                <button class="btn-edit" data-id="<?= htmlspecialchars($row['id_pengguna']); ?>"
                                    data-nama="<?= htmlspecialchars($row['nama_lengkap']); ?>"
                                    data-email="<?= htmlspecialchars($row['email']); ?>"
                                    data-hp="<?= htmlspecialchars($row['no_hp']); ?>"
                                    data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
                                    data-username="<?= htmlspecialchars($row['nama_pengguna']); ?>"
                                    data-foto="<?= htmlspecialchars($row['foto']); ?>" onclick="openEditModal(this)">
                                    <i class="fa fa-pen-to-square"></i>
                                </button>

                                <!-- HAPUS -->
                                <a class="btn-hapus"
                                    href="../src/controllers/PenggunaController.php?action=delete&id=<?= $row['id_pengguna']; ?>"
                                    onclick="return confirm('Yakin ingin menghapus anggota ini?')"><i
                                        class="fa fa-trash"></i></a>
                            </div>
                        </td>
                        <?php } ?>
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
                <!-- FOTO -->
                <div class="detail-foto">
                    <div class="foto-wrapper" onclick="openFotoModal(document.getElementById('d_foto'))">
                        <img id="d_foto" src="../asset/img/default.png" alt="Foto">
                        <div class="foto-overlay">
                            <i class="fa fa-search-plus"></i>
                        </div>
                    </div>
                </div>
                <p><b>Nama:</b> <span id="d_nama"></span></p>
                <p><b>Email:</b> <span id="d_email"></span></p>
                <p><b>No HP:</b> <span id="d_hp"></span></p>
                <p><b>Alamat:</b> <span id="d_alamat"></span></p>
                <p><b>Jabatan:</b> <span id="d_jabatan"></span></p>
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

                <span class="close" onclick="closeModal()">&times;</span>

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

                    <label>Username</label>
                    <input type="text" name="nama_pengguna" id="edit_username">

                    <label>Password Baru</label>
                    <input type="password" name="kata_sandi">
                    <small>Kosongkan jika tidak ingin mengganti password</small>

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

    <script>
    // ================= EDIT MODAL =================
    function openEditModal(button) {
        document.getElementById("editModal").style.display = "block";
        document.getElementById("edit_id").value = button.dataset.id;
        document.getElementById("edit_nama").value = button.dataset.nama;
        document.getElementById("edit_email").value = button.dataset.email;
        document.getElementById("edit_hp").value = button.dataset.hp;
        document.getElementById("edit_alamat").value = button.dataset.alamat;
        document.getElementById("edit_username").value = button.dataset.username;

        document.getElementById("preview_edit_foto").src =
            "../uploads/" + button.dataset.foto;
    }

    function closeModal() {
        document.getElementById("editModal").style.display = "none";
    }

    // ================= TAMBAH MODAL =================
    function openTambahModal() {
        document.getElementById("tambahModal").style.display = "block";
    }

    function closeTambahModal() {
        document.getElementById("tambahModal").style.display = "none";
    }

    // ================= DETAIL MODAL =================
    function openDetailModal(btn) {
        document.getElementById("d_nama").innerText = btn.dataset.nama;
        document.getElementById("d_email").innerText = btn.dataset.email;
        document.getElementById("d_hp").innerText = btn.dataset.hp;
        document.getElementById("d_alamat").innerText = btn.dataset.alamat;
        document.getElementById("d_jabatan").innerText = btn.dataset.jabatan;

        let fotoEl = document.getElementById("d_foto");
        if (fotoEl) {
            let foto = btn.dataset.foto;
            fotoEl.src = foto ? "../uploads/" + foto : "../asset/img/default.png";
        }

        document.getElementById("detailModal").classList.add("show");
    }

    function closeDetailModal() {
        document.getElementById("detailModal").classList.remove("show");
    }

    // ================= CLICK OUTSIDE (SEMUA MODAL) =================
    window.onclick = function(event) {

        let editModal = document.getElementById("editModal");
        let tambahModal = document.getElementById("tambahModal");
        let detailModal = document.getElementById("detailModal");

        if (event.target == editModal) {
            editModal.style.display = "none";
        }

        if (event.target == tambahModal) {
            tambahModal.style.display = "none";
        }

        if (event.target == detailModal) {
            detailModal.classList.remove("show");
        }
    }

    function openFotoModal(img) {
        document.getElementById("fotoModal").style.display = "flex";
        document.getElementById("fotoFull").src = img.src;
    }

    function closeFotoModal() {
        document.getElementById("fotoModal").style.display = "none";
    }

    // klik luar = close
    window.addEventListener("click", function(e) {
        let modal = document.getElementById("fotoModal");
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
    </script>
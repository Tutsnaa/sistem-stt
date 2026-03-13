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

        <h2>Daftar Anggota</h2>

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
                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($row['jabatan']); ?></td>
                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
                    <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
                    <td>
                        <div class="aksi-btn">
                            <button class="btn-edit" data-id="<?= $row['id_pengguna']; ?>"
                                data-nama="<?= $row['nama_lengkap']; ?>" data-email="<?= $row['email']; ?>"
                                data-hp="<?= $row['no_hp']; ?>" data-alamat="<?= $row['alamat']; ?>"
                                data-username="<?= $row['nama_pengguna']; ?>" onclick="openEditModal(this)">
                                Ubah
                            </button>
                            <a class="btn-hapus"
                                href="../src/controllers/PenggunaController.php?action=delete&id=<?= $row['id_pengguna']; ?>"
                                onclick="return confirm('Yakin ingin menghapus anggota ini?')">Hapus</a>
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

                    <input type="hidden" name="id_pengguna" id="edit_id" value="anggota">

                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="edit_nama" value="anggota">

                    <label>Email</label>
                    <input type="email" name="email" id="edit_email" value="anggota">

                    <label>No HP</label>
                    <input type="text" name="no_hp" id="edit_hp" value="anggota">

                    <label>Alamat</label>
                    <input type="text" name="alamat" id="edit_alamat" value="anggota">

                    <label>Username</label>
                    <input type="text" name="nama_pengguna" id="edit_username" value="anggota">

                    <label>Password Baru</label>
                    <input type="password" name="kata_sandi" value="anggota">
                    <small>Kosongkan jika tidak ingin mengganti password</small>

                    <label>Foto Baru</label>
                    <input type="file" name="foto" value="anggota">

                    <br><br>

                    <button type="submit" class="btn-save">Simpan</button>

                </form>

            </div>
        </div>
        <?php } ?>

    </div>

    <script>
    function openEditModal(button) {

        document.getElementById("editModal").style.display = "block";

        document.getElementById("edit_id").value = button.dataset.id;
        document.getElementById("edit_nama").value = button.dataset.nama;
        document.getElementById("edit_email").value = button.dataset.email;
        document.getElementById("edit_hp").value = button.dataset.hp;
        document.getElementById("edit_alamat").value = button.dataset.alamat;
        document.getElementById("edit_username").value = button.dataset.username;

    }

    function closeModal() {
        document.getElementById("editModal").style.display = "none";
    }


    function openTambahModal() {
        document.getElementById("tambahModal").style.display = "block";
    }

    function closeTambahModal() {
        document.getElementById("tambahModal").style.display = "none";
    }

    window.onclick = function(event) {

        let editModal = document.getElementById("editModal");
        let tambahModal = document.getElementById("tambahModal");

        if (event.target == editModal) {
            editModal.style.display = "none";
        }

        if (event.target == tambahModal) {
            tambahModal.style.display = "none";
        }

    }
    </script>
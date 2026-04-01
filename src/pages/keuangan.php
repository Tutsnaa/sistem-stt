<div id="modalHapus" class="modal-hapus">
    <div class="modal-box">
        <h3>Konfirmasi Hapus</h3>
        <p>Apakah kamu yakin ingin menghapus pengumuman ini?</p>

        <div class="modal-actions">
            <button class="btn-batal" onclick="closeModalHapus()">Batal</button>
            <a id="btnYaHapus" class="btn-hapus-yes">Ya, Hapus</a>
        </div>
    </div>
</div>

<div class="page-content">

    <!-- ================= POPUP TAMBAH DATA ================= -->
    <div id="popupForm" class="popup">
        <div class="popup-content popup-keuangan">

            <span class="close-btn" onclick="closePopup()">&times;</span>

            <h2>Tambah Data Keuangan</h2>

            <form action="../src/controllers/KeuanganController.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="action" value="simpan">
                <input type="hidden" name="id_pengguna" value="<?= $user['id_pengguna']; ?>">

                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis" required>
                        <option value="">Pilih</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" required></textarea>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" required>
                </div>

                <div class="form-group">
                    <label>Upload Bukti</label>
                    <input type="file" name="file_bukti">
                </div>

                <button type="submit" class="btn-save">Simpan</button>

            </form>

        </div>
    </div>


    <!-- ================= MODAL EDIT (HANYA UNTUK ADMIN) ================= -->
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>

    <div id="editModal" class="modal">

        <div class="modal-content">

            <!-- Tombol close modal -->
            <span class="close" onclick="closeModal()">&times;</span>

            <h3>Edit Data Keuangan</h3>

            <!-- Form edit -->
            <form action="../src/controllers/KeuanganController.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_keuangan" id="edit_id">

                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis" id="edit_jenis" required>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" id="edit_keterangan" required></textarea>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" id="edit_jumlah" required>
                </div>

                <button type="submit" class="btn-save">Simpan</button>

            </form>

        </div>
    </div>
    <?php } ?>





    <!-- ================= JUDUL TABEL ================= -->
    <h2>Data Keuangan</h2>
    <div class="filter-container">

        <!-- KIRI -->
        <div class="filter-left">
            <form method="GET">
                <input type="hidden" name="page" value="keuangan">

                <select name="filter" onchange="this.form.submit()" class="filter-dropdown">
                    <option value="all" <?= (!isset($_GET['filter']) || $_GET['filter']=='all') ? 'selected' : '' ?>>
                        Semua</option>
                    <option value="pemasukan"
                        <?= (isset($_GET['filter']) && $_GET['filter']=='pemasukan') ? 'selected' : '' ?>>Pemasukan
                    </option>
                    <option value="pengeluaran"
                        <?= (isset($_GET['filter']) && $_GET['filter']=='pengeluaran') ? 'selected' : '' ?>>Pengeluaran
                    </option>
                </select>
            </form>

            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
            <a href="../src/controllers/KeuanganController.php?action=download&filter=<?= $_GET['filter'] ?? 'all'; ?>"
                class="btn-download">
                Unduh Data
            </a>
            <?php } ?>
        </div>

        <!-- KANAN -->
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
        <button class="btn-tambah" onclick="openPopup()">+ Tambah Data</button>
        <?php } ?>

    </div>

    <!-- ================= TABEL DATA KEUANGAN ================= -->
    <div class="table-wrapper-keuangan">
        <div class="table-keuangan">
            <!-- ================= TABEL ================= -->
            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Bukti</th>
                        <th>Tanggal</th>
                        <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
                        <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody>

                    <?php
                require_once __DIR__ . '/../models/KeuanganModel.php';
                $keuanganModel = new KeuanganModel();
                $filter = $_GET['filter'] ?? 'all';
                $data = $keuanganModel->getAll();

               $no = 1;
foreach ($data as $row):

    // ================= FILTER =================
    if ($filter == 'pemasukan' && $row['jenis'] != 'pemasukan') continue;
    if ($filter == 'pengeluaran' && $row['jenis'] != 'pengeluaran') continue;
?>

                    <tr>

                        <!-- Nomor -->
                        <td style="text-align: center;"><?= $no++; ?></td>

                        <!-- Jenis (warna beda) -->
                        <td>
                            <?php if($row['jenis']=='pemasukan'){ ?>
                            <span style="color:green;">Pemasukan</span>
                            <?php } else { ?>
                            <span style="color:red;">Pengeluaran</span>
                            <?php } ?>
                        </td>

                        <!-- Keterangan -->
                        <td class="kolom-keterangan"><?= $row['keterangan']; ?></td>

                        <!-- Jumlah -->
                        <td>Rp <?= number_format($row['jumlah'],0,',','.'); ?></td>

                        <!-- Bukti -->
                        <td style="text-align: center;">
                            <?php if($row['file_bukti']){ ?>
                            <a href="../uploads/<?= $row['file_bukti']; ?>" target="_blank">Lihat</a>
                            <?php } else { ?>
                            -
                            <?php } ?>
                        </td>

                        <!-- Tanggal -->
                        <td style="text-align: center;">
                            <?= date('d-m-Y', strtotime($row['tanggal_dibuat'])); ?>
                        </td>

                        <!-- Aksi (admin only) -->
                        <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
                        <td style="text-align: center;">

                            <!-- Tombol edit -->
                            <button class="btn-ubah-keuangan" data-id="<?= $row['id_keuangan']; ?>"
                                data-jenis="<?= $row['jenis']; ?>" data-keterangan="<?= $row['keterangan']; ?>"
                                data-jumlah="<?= $row['jumlah']; ?>" onclick="openEditModal(this)">
                                <i class="fa fa-pen-to-square"></i>
                            </button>

                            <!-- Tombol hapus -->
                            <!-- <a class="btn-hapus-keuangan"
                                href="../src/controllers/KeuanganController.php?action=hapus&id=<?= $row['id_keuangan']; ?>"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fa fa-trash"></i>
                            </a> -->

                            <a href="#" class="btn-hapus-keuangan"
                                onclick="confirmHapus(<?= $row['id_keuangan']; ?>); return false;">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                        <?php } ?>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>


<!-- ================= SCRIPT ================= -->
<script>
function confirmHapus(id) {
    document.getElementById("modalHapus").style.display = "block";

    // set link hapus
    document.getElementById("btnYaHapus").href =
        "../src/controllers/KeuanganController.php?action=hapus&id=" + id;
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

// ================= EDIT MODAL =================
function openEditModal(button) {
    document.getElementById("editModal").style.display = "flex";

    document.getElementById("edit_id").value = button.dataset.id;
    document.getElementById("edit_jenis").value = button.dataset.jenis;
    document.getElementById("edit_keterangan").value = button.dataset.keterangan;
    document.getElementById("edit_jumlah").value = button.dataset.jumlah;
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}

// klik luar modal = tutup
window.addEventListener("click", function(e) {
    let popup = document.getElementById("popupForm");
    if (e.target === popup) {
        popup.classList.remove("show");
    }
});


// ================= POPUP TAMBAH =================
function openPopup() {
    document.getElementById("popupForm").classList.add("show");
}

function closePopup() {
    document.getElementById("popupForm").classList.remove("show");
}
</script>
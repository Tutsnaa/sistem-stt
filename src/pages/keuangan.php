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


    <!-- ================= TOMBOL TAMBAH ================= -->
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
    <button class="btn-tambah" onclick="openPopup()">+ Tambah Data</button>
    <?php } ?>


    <!-- ================= JUDUL TABEL ================= -->
    <h2>Data Keuangan</h2>

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
                $data = $keuanganModel->getAll();

                $no = 1;
                foreach ($data as $row):
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
    document.getElementById("editModal").style.display = "block";

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
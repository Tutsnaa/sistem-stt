<div class="page-content">

    <!-- ================= POPUP TAMBAH DATA ================= -->
    <div id="popupForm" class="popup">
        <div class="popup-content">

            <!-- Tombol close popup -->
            <span class="close-btn" onclick="closePopup()">&times;</span>

            <h2>Tambah Data</h2>

            <!-- Form tambah data keuangan -->
            <form action="../src/controllers/KeuanganController.php?action=simpan" method="POST"
                enctype="multipart/form-data">

                <!-- Hidden ID user -->
                <input type="hidden" name="id_pengguna" value="<?= $user['id_pengguna']; ?>">

                <!-- Pilih jenis -->
                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis" required>
                        <option value="">Pilih</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <!-- Input keterangan -->
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" required></textarea>
                </div>

                <!-- Input jumlah -->
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" required>
                </div>

                <!-- Upload bukti -->
                <div class="form-group">
                    <label>Upload Bukti</label>
                    <input type="file" name="file_bukti">
                </div>

                <!-- Tombol simpan -->
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
            <form action="../src/controllers/KeuanganController.php?action=update" method="POST"
                enctype="multipart/form-data">

                <!-- Hidden ID -->
                <input type="hidden" name="id_keuangan" id="edit_id">

                <!-- Jenis -->
                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis" id="edit_jenis" required>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <!-- Keterangan -->
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" id="edit_keterangan" required></textarea>
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" id="edit_jumlah" required>
                </div>

                <!-- Upload bukti -->
                <div class="form-group">
                    <label>Upload Bukti</label>
                    <input type="file" name="file_bukti">
                </div>

                <!-- Tombol simpan -->
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
                                Ubah
                            </button>

                            <!-- Tombol hapus -->
                            <a class="btn-hapus-keuangan"
                                href="../src/controllers/KeuanganController.php?action=hapus&id=<?= $row['id_keuangan']; ?>"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                Hapus
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
window.onclick = function(event) {
    let modal = document.getElementById("editModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


// ================= POPUP TAMBAH =================
function openPopup() {
    document.getElementById("popupForm").style.display = "block";
}

function closePopup() {
    document.getElementById("popupForm").style.display = "none";
}
</script>
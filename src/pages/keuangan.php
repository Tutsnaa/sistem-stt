<div class="page-content">

    <!-- FORM TAMBAH DATA -->
    <div class="form-keuangan">

        <h2>
            Tambah Data
        </h2>

        <form action="../src/controllers/KeuanganController.php?action=simpan" method="POST"
            enctype="multipart/form-data">

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

            <button type="submit" class="btn-save">
                Simpan Data
            </button>

        </form>

    </div>


    <!-- TABEL DATA -->
    <div class="table-keuangan">

        <!-- FORM EDIT -->
        <?php
$editData = null;

if(isset($_GET['edit'])){
    $editData = $keuanganModel->getById($_GET['edit']);
}
?>

        <!-- MODAL EDIT -->
        <div id="editModal" class="modal">

            <div class="modal-content">

                <span class="close" onclick="closeModal()">&times;</span>

                <h3>Edit Data Keuangan</h3>

                <form action="../src/controllers/KeuanganController.php?action=update" method="POST"
                    enctype="multipart/form-data">

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

                    <div class="form-group">
                        <label>Upload Bukti</label>
                        <input type="file" name="file_bukti">
                    </div>

                    <button type="submit" class="btn-save"> Simpan </button>

                </form>

            </div>
        </div>

        <table>
            <h2>Data Keuangan</h2>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Bukti</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
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

                    <td style="text-align: center;"><?= $no++; ?></td>

                    <td>
                        <?php if($row['jenis']=='pemasukan'){ ?>
                        <span style="color:green;">Pemasukan</span>
                        <?php }else{ ?>
                        <span style="color:red;">Pengeluaran</span>
                        <?php } ?>
                    </td>

                    <td class="kolom-keterangan"><?= $row['keterangan']; ?></td>

                    <td>
                        Rp <?= number_format($row['jumlah'],0,',','.'); ?>
                    </td>

                    <td style="text-align: center;">
                        <?php if($row['file_bukti']){ ?>
                        <a href="../uploads/<?= $row['file_bukti']; ?>" target="_blank">
                            Lihat
                        </a>
                        <?php }else{ ?>
                        -
                        <?php } ?>
                    </td>

                    <td style="text-align: center;">
                        <?= date('d-m-Y', strtotime($row['tanggal_dibuat'])); ?>
                    </td>

                    <td style="text-align: center;">

                        <button class="btn-ubah-keuangan" data-id="<?= $row['id_keuangan']; ?>"
                            data-jenis="<?= $row['jenis']; ?>" data-keterangan="<?= $row['keterangan']; ?>"
                            data-jumlah="<?= $row['jumlah']; ?>" onclick="openEditModal(this)">
                            Ubah
                        </button>

                        <a class="btn-hapus-keuangan"
                            href="../src/controllers/KeuanganController.php?action=hapus&id=<?= $row['id_keuangan']; ?>"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                            Hapus
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<script>
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

window.onclick = function(event) {
    let modal = document.getElementById("editModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
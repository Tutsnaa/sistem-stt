<div class="pengumuman-wrapper">

    <!-- ================= JUDUL HALAMAN ================= -->
    <h2 class="pengumuman-title">Daftar Pengumuman</h2>


    <!-- ================= TOMBOL TAMBAH ================= -->
    <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'ketua' &&
    $_SESSION['user']['jabatan'] != 'wakil' &&
    $_SESSION['user']['jabatan'] != 'bendahara 1' &&
    $_SESSION['user']['jabatan'] != 'bendahara 2'
) { 
?>
    <div class="pengumuman-actions">
        <button id="btnTambah" class="btn-add">+ Tambah Pengumuman</button>
    </div>
    <?php } ?>


    <!-- ================= MODAL TAMBAH ================= -->
    <div id="modalTambah" class="modal">
        <div class="modal-content">

            <span class="close" onclick="closeTambahModal()">&times;</span>

            <h3>Tambah Pengumuman</h3>

            <form action="../src/controllers/PengumumanController.php?action=tambah" method="POST"
                enctype="multipart/form-data">

                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" required placeholder="Masukkan judul pengumuman">
                </div>

                <div class="form-group">
                    <label>Isi</label>
                    <textarea name="isi" rows="4" required placeholder="Masukkan isi pengumuman"></textarea>
                </div>

                <!-- <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="tampil">Tampil</option>
                        <option value="tidak_tampil">Tidak Tampil</option>
                    </select>
                </div> -->

                <div class="form-group">
                    <label>File (opsional)</label>
                    <input type="file" name="file">
                </div>

                <button type="submit" class="btn-save">Simpan</button>

            </form>

        </div>
    </div>


    <!-- ================= TABEL DATA ================= -->
    <div class="table-wrapper-pengumuman">
        <div class="pengumuman-table">
            <table>

                <!-- HEADER -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Isi</th>
                        <th>File</th>
                        <th>Status</th>

                        <th>Aksi</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody>

                    <?php if(!empty($pengumuman)): ?>
                    <?php $no = 1; foreach($pengumuman as $p): ?>

                    <tr>

                        <!-- Nomor -->
                        <td><?= $no++; ?></td>

                        <!-- Judul -->
                        <td><?= htmlspecialchars($p['judul']); ?></td>

                        <!-- Isi -->
                        <td><?= nl2br(htmlspecialchars($p['isi'])); ?></td>

                        <!-- File -->
                        <td>
                            <?php if($p['file']): ?>
                            <a href="../uploads/<?= $p['file']; ?>" target="_blank">Lihat</a>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>

                        <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'ketua' &&
    $_SESSION['user']['jabatan'] != 'admin' &&
    $_SESSION['user']['jabatan'] != 'wakil'
) { 
?>
                        <!-- Status -->
                        <td class="status-cell">

                            <?php if($p['status'] == 'Menunggu'): ?>
                            <span class="badge badge-menunggu">
                                Menunggu
                            </span>

                            <?php elseif($p['status'] == 'Disetujui'): ?>
                            <span class="badge badge-disetujui">
                                Disetujui
                            </span>

                            <?php elseif($p['status'] == 'Ditolak'): ?>
                            <span class="badge badge-ditolak">
                                Ditolak
                            </span>
                            <?php endif; ?>

                        </td>
                        <?php } ?>

                        <?php if (
    $_SESSION['user']['jabatan'] == 'ketua' ||
    $_SESSION['user']['jabatan'] == 'admin'
): ?>

                        <td class="aksi-status">


                            <?php if($p['status'] == 'Menunggu'): ?>

                            <a href="../src/controllers/PengumumanController.php?action=toggleStatus&id=<?= $p['id_pengumuman']; ?>&status=Disetujui"
                                class="btn-terima" onclick="return confirm('Terima pengumuman ini?')">
                                Disetujui
                            </a>

                            <a href="../src/controllers/PengumumanController.php?action=toggleStatus&id=<?= $p['id_pengumuman']; ?>&status=Ditolak"
                                class="btn-tolak" onclick="return confirm('Tolak pengumuman ini?')">
                                Ditolak
                            </a>

                            <?php elseif($p['status'] == 'Disetujui'): ?>

                            <span class="badge-disetujui">
                                Disetujui
                            </span>

                            <?php elseif($p['status'] == 'Ditolak'): ?>

                            <span class="badge-ditolak">
                                Ditolak
                            </span>

                            <?php endif; ?>

                        </td>

                        <?php endif; ?>



                        <!-- Aksi -->
                        <td>
                            <div class="aksi-btn">
                                <!-- tombol detail -->
                                <button class="btn-detail" onclick="openDetailModal(
    '<?= htmlspecialchars($p['judul'], ENT_QUOTES); ?>',
    '<?= htmlspecialchars($p['isi'], ENT_QUOTES); ?>',
    '<?= $p['status']; ?>',
    '<?= $p['file']; ?>'
)">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'ketua' &&
    $_SESSION['user']['jabatan'] != 'wakil' &&
    $_SESSION['user']['jabatan'] != 'bendahara 1' &&
    $_SESSION['user']['jabatan'] != 'bendahara 2'
) { 
?>

                                <!-- tombol edit -->
                                <button class="btn-edit" onclick="openEditModal(
                                        '<?= $p['id_pengumuman']; ?>',
                                        '<?= htmlspecialchars($p['judul'], ENT_QUOTES); ?>',
                                        '<?= htmlspecialchars($p['isi'], ENT_QUOTES); ?>',
                                        '<?= $p['status']; ?>',
                                        '<?= $p['file']; ?>'
                                    )">
                                    <i class="fa fa-pen-to-square"></i>
                                </button>

                                <!-- tombol hapus -->
                                <button class="btn-hapus" onclick="confirmHapus(<?= $p['id_pengumuman']; ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <?php } ?>

                            </div>
                        </td>


                    </tr>

                    <?php endforeach; ?>

                    <?php else: ?>

                    <!-- jika kosong -->
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Belum ada pengumuman
                        </td>
                    </tr>

                    <?php endif; ?>

                </tbody>

            </table>
        </div>
    </div>

    <!-- ================= MODAL DETAIL ================= -->
    <div id="modalDetail" class="modal">
        <div class="modal-content">

            <span class="close" onclick="closeDetailModal()">&times;</span>

            <h3>Detail Pengumuman</h3>

            <div class="detail-group">
                <label>Judul</label>
                <p id="detail_judul"></p>
            </div>

            <div class="detail-group">
                <label>Isi</label>
                <p id="detail_isi"></p>
            </div>

            <div class="detail-group">
                <label>Status</label>
                <p id="detail_status"></p>
            </div>

            <div class="detail-group">
                <label>File</label>
                <div id="detail_file"></div>
            </div>

        </div>
    </div>


    <!-- ================= MODAL EDIT ================= -->
    <div id="modalEdit" class="modal">
        <div class="modal-content">

            <span class="close" onclick="closeEditModal()">&times;</span>

            <h3>Ubah Pengumuman</h3>

            <form action="../src/controllers/PengumumanController.php?action=update" method="POST"
                enctype="multipart/form-data">

                <input type="hidden" name="id_pengumuman" id="edit_id">
                <input type="hidden" name="file_lama" id="edit_file_lama">

                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" id="edit_judul" required>
                </div>

                <div class="form-group">
                    <label>Isi</label>
                    <textarea name="isi" id="edit_isi" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label>Ganti File</label>
                    <input type="file" name="file">
                </div>

                <button type="submit" class="btn-save">Simpan</button>

            </form>

        </div>
    </div>

</div>

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

<!-- Script JS untuk modal -->
<script>
function confirmHapus(id) {
    document.getElementById("modalHapus").style.display = "block";

    // set link hapus
    document.getElementById("btnYaHapus").href =
        "../src/controllers/PengumumanController.php?action=hapus&id=" + id;
}

function closeModalHapus() {
    document.getElementById("modalHapus").style.display = "none";
}


window.addEventListener("click", function(event) {
    const modalHapus = document.getElementById("modalHapus");
    const modalTambah = document.getElementById("modalTambah");

    if (event.target === modalHapus) {
        modalHapus.style.display = "none";
    }

    if (event.target === modalTambah) {
        modalTambah.style.display = "none";
    }
});

const modal = document.getElementById('modalTambah');
const btnTambah = document.getElementById('btnTambah');
const spanClose = document.getElementsByClassName('close')[0];
const btnBatal = document.getElementById('btnBatal');

btnTambah.onclick = () => modal.style.display = 'flex';
spanClose.onclick = () => modal.style.display = 'none';
btnBatal.onclick = () => modal.style.display = 'none';



function openEditModal(id, judul, isi, status) {

    document.getElementById("modalEdit").style.display = "flex";

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_judul").value = judul;
    document.getElementById("edit_isi").value = isi;
    document.getElementById("edit_status").value = status;

}

function closeEditModal() {
    document.getElementById("modalEdit").style.display = "none";
}

function openEditModal(id, judul, isi, status, file) {

    document.getElementById("modalEdit").style.display = "flex";

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_judul").value = judul;
    document.getElementById("edit_isi").value = isi;
    document.getElementById("edit_status").value = status;
    document.getElementById("edit_file_lama").value = file;

}

function openDetailModal(judul, isi, status, file) {

    document.getElementById("modalDetail").style.display = "flex";

    document.getElementById("detail_judul").innerText = judul;
    document.getElementById("detail_isi").innerText = isi;
    document.getElementById("detail_status").innerText = status;

    if (file && file !== '') {
        document.getElementById("detail_file").innerHTML =
            `<a href="../uploads/${file}" target="_blank">Lihat File</a>`;
    } else {
        document.getElementById("detail_file").innerHTML = "-";
    }
}

function closeDetailModal() {
    document.getElementById("modalDetail").style.display = "none";
}
</script>
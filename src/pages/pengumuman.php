<div class="pengumuman-wrapper">

    <!-- ================= JUDUL HALAMAN ================= -->
    <h2 class="pengumuman-title">Daftar Pengumuman</h2>


    <!-- ================= TOMBOL TAMBAH ================= -->
    <div class="pengumuman-actions">
        <button id="btnTambah" class="btn-add">+ Tambah Pengumuman</button>
    </div>


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

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="tampil">Tampil</option>
                        <option value="tidak_tampil">Tidak Tampil</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>File (opsional)</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png">
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
                            <a href="../uploads/<?= $p['file']; ?>" target="_blank">Download</a>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>

                        <!-- Status -->
                        <td><?= ucfirst($p['status']); ?></td>

                        <!-- Aksi -->
                        <td>
                            <div class="aksi-btn">

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
                    <label>Status</label>
                    <select name="status" id="edit_status" required>
                        <option value="tampil">Tampil</option>
                        <option value="tidak_tampil">Tidak Tampil</option>
                    </select>
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

btnTambah.onclick = () => modal.style.display = 'block';
spanClose.onclick = () => modal.style.display = 'none';
btnBatal.onclick = () => modal.style.display = 'none';



function openEditModal(id, judul, isi, status) {

    document.getElementById("modalEdit").style.display = "block";

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_judul").value = judul;
    document.getElementById("edit_isi").value = isi;
    document.getElementById("edit_status").value = status;

}

function closeEditModal() {
    document.getElementById("modalEdit").style.display = "none";
}

function openEditModal(id, judul, isi, status, file) {

    document.getElementById("modalEdit").style.display = "block";

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_judul").value = judul;
    document.getElementById("edit_isi").value = isi;
    document.getElementById("edit_status").value = status;
    document.getElementById("edit_file_lama").value = file;

}
</script>
<div class="pengumuman-wrapper">

    <h2 class="pengumuman-title">Daftar Pengumuman</h2>

    <!-- Tombol Tambah Pengumuman -->
    <div class="pengumuman-actions">
        <button id="btnTambah" class="btn-add">+ Tambah Pengumuman</button>
    </div>

    <!-- Modal Popup Tambah Pengumuman -->
    <div id="modalTambah" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Tambah Pengumuman</h3>
            <form action="../src/controllers/PengumumanController.php?action=tambah" method="POST"
                enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Judul</td>
                        <td><input type="text" name="judul" required placeholder="Masukkan judul pengumuman"></td>
                    </tr>
                    <tr>
                        <td>Isi</td>
                        <td><textarea name="isi" rows="5" required placeholder="Masukkan isi pengumuman"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            <select name="status" required>
                                <option value="tampil">Tampil</option>
                                <option value="tidak_tampil">Tidak Tampil</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>File (opsional)</td>
                        <td><input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" class="btn-save">Simpan</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <!-- Tabel Pengumuman -->
    <div class="pengumuman-table" style="margin-top:20px;">
        <table>
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
            <tbody>
                <?php if(!empty($pengumuman)): ?>
                <?php $no = 1; foreach($pengumuman as $p): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($p['judul']); ?></td>
                    <td><?= nl2br(htmlspecialchars($p['isi'])); ?></td>
                    <td>
                        <?php if($p['file']): ?>
                        <a href="../uploads/<?= $p['file']; ?>" target="_blank">Download</a>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <td><?= ucfirst($p['status']); ?></td>
                    <td>
                        <div class="aksi-btn">
                            <button class="btn-edit" onclick="openEditModal(
'<?= $p['id_pengumuman']; ?>',
'<?= htmlspecialchars($p['judul'], ENT_QUOTES); ?>',
'<?= htmlspecialchars($p['isi'], ENT_QUOTES); ?>',
'<?= $p['status']; ?>',
'<?= $p['file']; ?>'
)">
                                Ubah
                            </button>
                            <a href="../src/controllers/PengumumanController.php?action=hapus&id=<?= $p['id_pengumuman']; ?>"
                                class="btn-hapus" onclick="return confirm('Hapus pengumuman ini?')">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align:center;">Belum ada pengumuman</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Edit Pengumuman -->
    <div id="modalEdit" class="modal">
        <div class="modal-content">

            <span class="close" onclick="closeEditModal()">&times;</span>

            <h3>Ubah Pengumuman</h3>

            <form action="../src/controllers/PengumumanController.php?action=update" method="POST"
                enctype="multipart/form-data">

                <input type="hidden" name="id_pengumuman" id="edit_id">
                <input type="hidden" name="file_lama" id="edit_file_lama">

                <table>

                    <tr>
                        <td>Judul</td>
                        <td>
                            <input type="text" name="judul" id="edit_judul" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Isi</td>
                        <td>
                            <textarea name="isi" id="edit_isi" rows="5" required></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <td>
                            <select name="status" id="edit_status" required>
                                <option value="tampil">Tampil</option>
                                <option value="tidak_tampil">Tidak Tampil</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Ganti File</td>
                        <td>
                            <input type="file" name="file">
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" class="btn-save">Simpan</button>
                        </td>
                    </tr>

                </table>

            </form>

        </div>
    </div>

</div>

<!-- Script JS untuk modal -->
<script>
const modal = document.getElementById('modalTambah');
const btnTambah = document.getElementById('btnTambah');
const spanClose = document.getElementsByClassName('close')[0];
const btnBatal = document.getElementById('btnBatal');

btnTambah.onclick = () => modal.style.display = 'block';
spanClose.onclick = () => modal.style.display = 'none';
btnBatal.onclick = () => modal.style.display = 'none';

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};

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
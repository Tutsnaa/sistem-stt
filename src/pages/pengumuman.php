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
                            <button type="button" id="btnBatal" class="btn-cancel">Batal</button>
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
                        <a href="edit_pengumuman.php?id=<?= $p['id_pengumuman']; ?>" class="btn-edit">Edit</a>
                        <a href="../src/controllers/PengumumanController.php?action=hapus&id=<?= $p['id_pengumuman']; ?>"
                            class="btn-delete" onclick="return confirm('Hapus pengumuman ini?')">Hapus</a>
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
</script>
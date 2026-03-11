<div class="kepengurusan-wrapper">

    <h2 class="page-title">Data Kepengurusan</h2>

    <!-- <div class="kepengurusan-actions">
        <button id="btnTambah" class="btn-add">+ Tambah Pengurus</button>
    </div> -->

    <div class="kepengurusan-table">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Masa Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($kepengurusan as $k): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($k['nama_lengkap']) ?></td>
                    <td><?= ucfirst($k['jabatan']) ?></td>
                    <td>
                        <?= date('Y', strtotime($k['masa_awal_jabatan'])) ?>
                        -
                        <?= date('Y', strtotime($k['masa_akhir_jabatan'])) ?>
                    </td>
                    <td>
                        <a href="../src/controllers/KepengurusanController.php?action=hapus&id=<?= $k['id_kepengurusan'] ?>"
                            class="btn-delete" onclick="return confirm('Hapus data kepengurusan ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

</div>

<!-- ================= MODAL TAMBAH ================= -->
<div id="modalTambah" class="modal">

    <div class="modal-content">

        <span class="close">&times;</span>

        <h3>Tambah Kepengurusan</h3>

        <form action="../src/controllers/KepengurusanController.php?action=tambah" method="POST">

            <table>
                <tr>
                    <td>Nama</td>
                    <td>
                        <select name="id_pengguna" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach($pengguna as $p): ?>
                            <option value="<?= $p['id_pengguna'] ?>">
                                <?= $p['nama_lengkap'] ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </td>
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
                    <td>Masa Awal</td>
                    <td>
                        <input type="date" name="masa_awal_jabatan" required>
                    </td>
                </tr>

                <tr>
                    <td>Masa Akhir</td>
                    <td>
                        <input type="date" name="masa_akhir_jabatan" required>
                    </td>
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

<!-- ================= JAVASCRIPT MODAL ================= -->
<script>
const modal = document.getElementById("modalTambah");
const btnTambah = document.getElementById("btnTambah");
const btnClose = document.querySelector(".close");
const btnBatal = document.getElementById("btnBatal");

btnTambah.onclick = () => modal.style.display = "flex";
btnClose.onclick = () => modal.style.display = "none";
btnBatal.onclick = () => modal.style.display = "none";

window.onclick = (event) => {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
</script>
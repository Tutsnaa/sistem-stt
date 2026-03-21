<div class="pages-voting-container">

    <h2>Kelola Voting</h2>
    <button class="btn-tambah" onclick="openVotingPopup()">Tambah Voting</button>
    <br><br>

    <!-- Tabel Voting -->
    <table class="pages-voting-table" border="1" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Voting</th>
                <th>Masa Awal Jabatan</th>
                <th>Masa Akhir Jabatan</th>
                <th>Periode</th>
                <th>Tanggal Buka</th>
                <th>Tanggal Tutup</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($voting as $row): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['judul']) ?></td>

                <!-- AMAN DARI NULL -->
                <td><?= $row['masa_awal_jabatan'] ? htmlspecialchars($row['masa_awal_jabatan']) : '-' ?></td>
                <td><?= $row['masa_akhir_jabatan'] ? htmlspecialchars($row['masa_akhir_jabatan']) : '-' ?></td>

                <td><?= htmlspecialchars($row['periode']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_buka']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_tutup']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>

                <td>
                    <button class="btn btn-ubah" data-id="<?= $row['id_voting'] ?>"
                        data-judul="<?= htmlspecialchars($row['judul']) ?>"
                        data-periode="<?= htmlspecialchars($row['periode']) ?>"
                        data-masa_awal="<?= $row['masa_awal_jabatan'] ?>"
                        data-masa_akhir="<?= $row['masa_akhir_jabatan'] ?>"
                        data-tanggal_buka="<?= date('Y-m-d', strtotime($row['tanggal_buka'])) ?>"
                        data-tanggal_tutup="<?= date('Y-m-d', strtotime($row['tanggal_tutup'])) ?>"
                        data-status="<?= $row['status'] ?>">
                        Ubah
                    </button>

                    <a href="../src/controllers/VotingController.php?action=deleteVoting&id=<?= $row['id_voting'] ?>"
                        onclick="return confirm('Yakin ingin menghapus voting?')">
                        Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h2>Kelola Kandidat</h2>
    <button class="btn-tambah" onclick="openKandidatPopup()">Tambah Kandidat</button>
    <br><br>

    <!-- Tabel Kandidat -->
    <table class="pages-voting-table" border="1" width="100%">
        <thead>
            <tr>
                <th>Voting</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>No Paslon</th>
                <th>Visi</th>
                <th>Misi</th>
                <th>Aksi</th>
                <th>Vote</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($kandidat as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['jabatan']) ?></td>
                <td><?= htmlspecialchars($row['no_paslon']) ?></td>
                <td><?= htmlspecialchars($row['visi']) ?></td>
                <td><?= htmlspecialchars($row['misi']) ?></td>
                <td>
                    <button class="btn btn-ubah-kandidat" data-id="<?= $row['id_calon'] ?>"
                        data-id_voting="<?= $row['id_voting'] ?>" data-id_pengguna="<?= $row['id_pengguna'] ?>"
                        data-jabatan="<?= htmlspecialchars($row['jabatan']) ?>"
                        data-no_paslon="<?= $row['no_paslon'] ?>" data-visi="<?= htmlspecialchars($row['visi']) ?>"
                        data-misi="<?= htmlspecialchars($row['misi']) ?>">
                        Ubah
                    </button> |
                    <a href="../src/controllers/KandidatController.php?action=deleteKandidat&id=<?= $row['id_calon'] ?>"
                        onclick="return confirm('Yakin ingin menghapus kandidat?')">Hapus</a>
                </td>
                <td>
                    <?php if($row['status'] == 'dibuka'): ?>
                    <a href="../src/controllers/VotingController.php?action=vote&id_calon=<?= $row['id_calon'] ?>"
                        onclick="return confirm('Yakin memilih kandidat ini?')" class="btn btn-tambah">
                        Vote
                    </a>
                    <?php else: ?>
                    <button class="btn btn-hapus" disabled>
                        Voting Ditutup
                    </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h2>Rekap Suara Voting</h2>
    <table class="pages-voting-table" border="1" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Voting</th>
                <th>Nama Kandidat</th>
                <th>Jabatan</th>
                <th>No Paslon</th>
                <th>Total Suara</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($kandidat as $row): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['jabatan']) ?></td>
                <td><?= $row['no_paslon'] ?></td>
                <td><?= $votingModel->countSuara($row['id_calon']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<!-- ================= Popup Edit Voting ================= -->
<div id="modalEdit" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="popup-close">&times;</span>
        <h2>Edit Voting</h2>

        <form action="../src/controllers/VotingController.php?action=updateVoting" method="POST">
            <input type="hidden" name="id_voting" id="edit_id">

            <label>Judul Voting</label>
            <input type="text" name="judul" id="edit_judul" required>

            <label>Periode</label>
            <input type="text" name="periode" id="edit_periode" required>

            <!-- 🔥 TAMBAHAN -->
            <label>Masa Awal Jabatan</label>
            <input type="date" name="masa_awal_jabatan" id="edit_masa_awal" required>

            <label>Masa Akhir Jabatan</label>
            <input type="date" name="masa_akhir_jabatan" id="edit_masa_akhir" required>

            <label>Tanggal Buka</label>
            <input type="date" name="tanggal_buka" id="edit_tanggal_buka" required>

            <label>Tanggal Tutup</label>
            <input type="date" name="tanggal_tutup" id="edit_tanggal_tutup" required>

            <label>Status Voting</label>
            <select name="status" id="edit_status" required>
                <option value="draft">Draft</option>
                <option value="dibuka">Dibuka</option>
                <option value="ditutup">Ditutup</option>
                <option value="selesai">Selesai</option>
            </select>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</div>

<!-- ================= Popup Edit Kandidat ================= -->
<div id="modalEditKandidat" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="popup-close" onclick="closeEditKandidat()">&times;</span>
        <h2>Edit Kandidat</h2>
        <form action="../src/controllers/KandidatController.php?action=updateKandidat" method="POST">
            <input type="hidden" name="id_calon" id="editKandidat_id">
            <input type="hidden" name="id_voting" id="editKandidat_id_voting">
            <input type="hidden" name="id_pengguna" id="editKandidat_id_pengguna">
            <label>Jabatan</label>
            <input type="text" name="jabatan" id="editKandidat_jabatan" required>
            <label>No Paslon</label>
            <input type="text" name="no_paslon" id="editKandidat_no_paslon" required>
            <label>Visi</label>
            <input type="text" name="visi" id="editKandidat_visi" required>
            <label>Misi</label>
            <input type="text" name="misi" id="editKandidat_misi" required>
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</div>

<!-- ================= Popup Tambah Voting ================= -->
<div id="popupVoting" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="popup-close" onclick="closeVotingPopup()">&times;</span>
        <h2>Tambah Voting</h2>
        <form action="../src/controllers/VotingController.php?action=createVoting" method="POST">
            <input type="hidden" name="id_pengguna" value="<?= $user['id_pengguna'] ?>">
            <label>Judul Voting</label>
            <input type="text" name="judul" required>
            <label>Masa Awal Jabatan</label>
            <input type="date" name="masa_awal_jabatan" required>
            <label>Masa Akhir Jabatan</label>
            <input type="date" name="masa_akhir_jabatan" required>
            <label>Periode</label>
            <input type="text" name="periode" required>
            <label>Tanggal Buka</label>
            <input type="date" name="tanggal_buka" required>
            <label>Tanggal Tutup</label>
            <input type="date" name="tanggal_tutup" required>
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>

<!-- ================= Popup Tambah Kandidat ================= -->
<div id="popupKandidat" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="popup-close" onclick="closeKandidatPopup()">&times;</span>
        <h2>Tambah Kandidat</h2>
        <form action="../src/controllers/KandidatController.php?action=createKandidat" method="POST">
            <label>Voting</label>
            <select name="id_voting" required>
                <option value="">-- Pilih Voting --</option>
                <?php foreach($voting as $v): ?>
                <option value="<?= $v['id_voting'] ?>"><?= htmlspecialchars($v['judul']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Nama Pengguna</label>
            <select name="id_pengguna" required>
                <option value="">-- Pilih Anggota --</option>
                <?php foreach($anggota as $a): ?>
                <option value="<?= $a['id_pengguna'] ?>"><?= htmlspecialchars($a['nama_lengkap']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Jabatan</label>
            <select name="jabatan" id="editKandidat_jabatan" required>
                <option value="">-- Pilih Jabatan --</option>
                <option value="ketua" <?= isset($row['jabatan']) && $row['jabatan']=='ketua' ? 'selected' : '' ?>>Ketua
                </option>
                <option value="wakil" <?= isset($row['jabatan']) && $row['jabatan']=='wakil' ? 'selected' : '' ?>>Wakil
                </option>
                <option value="sekretaris 1"
                    <?= isset($row['jabatan']) && $row['jabatan']=='sekretaris 1' ? 'selected' : '' ?>>Sekretaris 1
                </option>
                <option value="sekretaris 2"
                    <?= isset($row['jabatan']) && $row['jabatan']=='sekretaris 2' ? 'selected' : '' ?>>Sekretaris 2
                </option>
                <option value="bendahara 1"
                    <?= isset($row['jabatan']) && $row['jabatan']=='bendahara 1' ? 'selected' : '' ?>>Bendahara 1
                </option>
                <option value="bendahara 2"
                    <?= isset($row['jabatan']) && $row['jabatan']=='bendahara 2' ? 'selected' : '' ?>>Bendahara 2
                </option>
                <option value="anggota" <?= isset($row['jabatan']) && $row['jabatan']=='anggota' ? 'selected' : '' ?>>
                    Anggota</option>
            </select>
            <label>No Paslon</label>
            <input type="text" name="no_paslon" required>
            <label>Visi</label>
            <textarea name="visi" required></textarea>
            <label>Misi</label>
            <textarea name="misi" required></textarea>
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>


<!-- ================= Script Popup & Edit ================= -->
<script>
function openVotingPopup() {
    document.getElementById("popupVoting").style.display = "flex";
}

function closeVotingPopup() {
    document.getElementById("popupVoting").style.display = "none";
}

function openKandidatPopup() {
    document.getElementById("popupKandidat").style.display = "flex";
}

function closeKandidatPopup() {
    document.getElementById("popupKandidat").style.display = "none";
}

// Edit Voting
const modalEdit = document.getElementById("modalEdit");
document.querySelectorAll(".btn-ubah").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("edit_id").value = btn.dataset.id;
        document.getElementById("edit_judul").value = btn.dataset.judul;
        document.getElementById('edit_masa_awal').value = btn.dataset.masa_awal;
        document.getElementById('edit_masa_akhir').value = btn.dataset.masa_akhir;
        document.getElementById("edit_periode").value = btn.dataset.periode;
        document.getElementById("edit_tanggal_buka").value = btn.dataset.tanggal_buka;
        document.getElementById("edit_tanggal_tutup").value = btn.dataset.tanggal_tutup;

        // Set status sesuai data row
        if (btn.dataset.status) {
            document.getElementById("edit_status").value = btn.dataset.status;
        }

        modalEdit.style.display = "flex";
    });
});

modalEdit.querySelector(".popup-close").onclick = () => modalEdit.style.display = "none";
window.onclick = (event) => {
    if (event.target == modalEdit) modalEdit.style.display = "none";
};

// Edit Kandidat
const modalEditKandidat = document.getElementById("modalEditKandidat");
document.querySelectorAll(".btn-ubah-kandidat").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("editKandidat_id").value = btn.dataset.id;
        document.getElementById("editKandidat_id_voting").value = btn.dataset.id_voting;
        document.getElementById("editKandidat_id_pengguna").value = btn.dataset.id_pengguna;
        document.getElementById("editKandidat_jabatan").value = btn.dataset.jabatan;
        document.getElementById("editKandidat_no_paslon").value = btn.dataset.no_paslon;
        document.getElementById("editKandidat_visi").value = btn.dataset.visi;
        document.getElementById("editKandidat_misi").value = btn.dataset.misi;
        modalEditKandidat.style.display = "flex";
    });
});

function closeEditKandidat() {
    modalEditKandidat.style.display = "none";
}
window.addEventListener("click", (event) => {
    if (event.target == modalEditKandidat) modalEditKandidat.style.display = "none";
});
</script>
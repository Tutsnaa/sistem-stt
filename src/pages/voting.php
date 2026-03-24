<div class="pages-voting-container">

    <!-- ================= SUBMENU ================= -->
    <div class="submenu">
        <button class="submenu-btn active" onclick="showTab(this, 'voting')">Voting</button>
        <button class="submenu-btn" onclick="showTab(this, 'kandidat')">Kandidat</button>
        <button class="submenu-btn" onclick="showTab(this, 'rekap')">Rekap</button>
    </div>

    <!-- ================= TAB VOTING ================= -->
    <div id="tab-voting" class="tab-content active">
        <div class="header-voting">
            <h2>Kelola Voting</h2>
            <button class="btn btn-tambah" onclick="openVotingPopup()">Tambah Voting</button>
        </div>

        <div class="table-wrapper-voting">
            <div class="voting-table">
                <table class="pages-voting-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Voting</th>
                            <th>Masa Awal</th>
                            <th>Masa Akhir</th>
                            <th>Periode</th>
                            <th>Tgl Buka</th>
                            <th>Tgl Tutup</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($voting as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td class="text-center"><?= $row['masa_awal_jabatan'] ?: '-' ?></td>
                            <td class="text-center"><?= $row['masa_akhir_jabatan'] ?: '-' ?></td>
                            <td class="text-center"><?= $row['periode'] ?></td>
                            <td class="text-center"><?= $row['tanggal_buka'] ?></td>
                            <td class="text-center"><?= $row['tanggal_tutup'] ?></td>
                            <td class="text-center"><?= $row['status'] ?></td>
                            <td>
                                <button class="btn btn-ubah" data-id="<?= $row['id_voting'] ?>"
                                    data-judul="<?= $row['judul'] ?>" data-periode="<?= $row['periode'] ?>"
                                    data-masa_awal="<?= $row['masa_awal_jabatan'] ?>"
                                    data-masa_akhir="<?= $row['masa_akhir_jabatan'] ?>"
                                    data-tanggal_buka="<?= date('Y-m-d', strtotime($row['tanggal_buka'])) ?>"
                                    data-tanggal_tutup="<?= date('Y-m-d', strtotime($row['tanggal_tutup'])) ?>"
                                    data-status="<?= $row['status'] ?>">
                                    <i class="fa fa-pen-to-square"></i>
                                </button>

                                <a class="btn btn-hapus"
                                    href="../src/controllers/VotingController.php?action=deleteVoting&id=<?= $row['id_voting'] ?>"
                                    onclick="return confirm('Yakin hapus?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- ================= TAB KANDIDAT ================= -->
    <div id="tab-kandidat" class="tab-content">

        <div class="header-kandidat">
            <h2>Kelola Kandidat</h2>
            <button class="btn-tambah" onclick="openKandidatPopup()">Tambah Kandidat</button>
        </div>
        <div class="table-wrapper-voting">
            <div class="table-voting">
                <table class="pages-voting-table">
                    <thead>
                        <tr>
                            <th>No</th>
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
                        <?php $no = 1;  foreach($kandidat as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= $row['judul'] ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td class="text-center"><?= $row['jabatan'] ?></td>
                            <td class="text-center"><?= $row['no_paslon'] ?></td>
                            <td><?= $row['visi'] ?></td>
                            <td><?= $row['misi'] ?></td>
                            <td>
                                <button class="btn btn-ubah-kandidat" data-id="<?= $row['id_calon'] ?>"
                                    data-id_voting="<?= $row['id_voting'] ?>"
                                    data-id_pengguna="<?= $row['id_pengguna'] ?>" data-jabatan="<?= $row['jabatan'] ?>"
                                    data-no_paslon="<?= $row['no_paslon'] ?>" data-visi="<?= $row['visi'] ?>"
                                    data-misi="<?= $row['misi'] ?>">
                                    <i class="fa fa-pen-to-square"></i>
                                </button>
                                <a class="btn btn-hapus"
                                    href="../src/controllers/KandidatController.php?action=deleteKandidat&id=<?= $row['id_calon'] ?>"
                                    onclick="return confirm('Yakin hapus?')"><i class="fa fa-trash"></i></a>
                            </td>
                            <td>
                                <?php if($row['status']=='dibuka'): ?>
                                <a href="../src/controllers/VotingController.php?action=vote&id_calon=<?= $row['id_calon'] ?>"
                                    class="btn btn-tambah">Vote</a>
                                <?php else: ?>
                                <button class="btn btn-ditutup" disabled><i class="fa-solid fa-lock"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= TAB REKAP ================= -->
    <div id="tab-rekap" class="tab-content">

        <h2>Rekap Suara</h2>
        <div class="table-wrapper-voting">
            <div class="table-voting">
                <table class="pages-voting-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Voting</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>No</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($kandidat as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= $row['judul'] ?></td>
                            <td><?= $row['nama_lengkap'] ?></td>
                            <td class="text-center"><?= $row['jabatan'] ?></td>
                            <td class="text-center"><?= $row['no_paslon'] ?></td>
                            <td class="total-suara">
                                <?= $votingModel->countSuara($row['id_calon']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ================= Popup Ubah Voting ================= -->
<div id="modalEdit" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="popup-close">&times;</span>
        <h2>Ubah Voting</h2>

        <form action="../src/controllers/VotingController.php?action=updateVoting" method="POST">
            <input type="hidden" name="id_voting" id="edit_id">

            <label>Judul Voting</label>
            <input type="text" name="judul" id="edit_judul" required>

            <label>Periode</label>
            <input type="text" name="periode" id="edit_periode" required>

            <!-- TAMBAHAN -->
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

<!-- ================= Popup Ubah Kandidat ================= -->
<div id="modalEditKandidat" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="popup-close" onclick="closeEditKandidat()">&times;</span>
        <h2>Ubah Kandidat</h2>
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

function showTab(e, tab) {

    // hilangkan semua active
    document.querySelectorAll(".tab-content").forEach(el => {
        el.classList.remove("active");
    });

    document.querySelectorAll(".submenu-btn").forEach(btn => {
        btn.classList.remove("active");
    });

    // tampilkan tab yang dipilih
    document.getElementById("tab-" + tab).classList.add("active");

    // tombol aktif
    e.classList.add("active");
}
</script>
<div class="pages-voting-container">

    <h2>Kelola Voting</h2>

    <button onclick="openVotingPopup()">Tambah Voting</button>

    <br><br>

    <table class="pages-voting-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Voting</th>
                <th>Periode</th>
                <th>Tanggal Buka</th>
                <th>Tanggal Tutup</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach($voting as $row): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['judul']; ?></td>
                <td><?= $row['periode']; ?></td>
                <td><?= $row['tanggal_buka']; ?></td>
                <td><?= $row['tanggal_tutup']; ?></td>
                <td><?= $row['status']; ?></td>
                <td>
                    <a
                        href="../src/controllers/VotingController.php?action=updateStatus&id=<?= $row['id_voting']; ?>&status=aktif">Buka</a>
                    |
                    <a
                        href="../src/controllers/VotingController.php?action=updateStatus&id=<?= $row['id_voting']; ?>&status=selesai">Tutup</a>
                    |
                    <button class="btn-edit" data-id="<?= $row['id_voting']; ?>" data-judul="<?= $row['judul']; ?>"
                        data-periode="<?= $row['periode']; ?>" data-tanggal_buka="<?= $row['tanggal_buka']; ?>"
                        data-tanggal_tutup="<?= $row['tanggal_tutup']; ?>">
                        Ubah
                    </button> |
                    <a href="../src/controllers/VotingController.php?action=deleteVoting&id=<?= $row['id_voting']; ?>"
                        onclick="return confirm('Yakin ingin menghapus voting?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h2>Kelola Kandidat</h2>

    <button onclick="openKandidatPopup()">Tambah Kandidat</button>

    <br><br>

    <table class="pages-voting-table">
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
        <?php foreach($kandidat as $row): ?>
        <tr>
            <td><?= $row['judul'] ?></td>
            <td><?= $row['nama_lengkap'] ?></td>
            <td><?= $row['jabatan'] ?></td>
            <td><?= $row['no_paslon'] ?></td>
            <td><?= $row['visi'] ?></td>
            <td><?= $row['misi'] ?></td>
            <td>
                <button class="btn-edit-kandidat" data-id="<?= $row['id_calon']; ?>"
                    data-id_voting="<?= $row['id_voting']; ?>" data-id_pengguna="<?= $row['id_pengguna']; ?>"
                    data-jabatan="<?= $row['jabatan']; ?>" data-no_paslon="<?= $row['no_paslon']; ?>"
                    data-visi="<?= $row['visi']; ?>" data-misi="<?= $row['misi']; ?>">
                    Ubah
                </button>
                <a href="../src/controllers/VotingController.php?action=deleteKandidat&id=<?= $row['id_calon'] ?>">
                    Hapus
                </a>

            <td>
                <a href="../src/controllers/VotingController.php?action=vote&id_calon=<?= $row['id_calon']; ?>"
                    onclick="return confirm('Yakin memilih kandidat ini?')">Vote</a>
            </td>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- POPUP EDIT KANDIDAT -->
    <div id="modalEditKandidat" class="pages-voting-modal">
        <div class="pages-voting-modal-content">
            <span class="popup-close" onclick="closeEditKandidat()">&times;</span>
            <h3>Edit Kandidat</h3>
            <form action="../src/controllers/VotingController.php?action=updateKandidat" method="POST">
                <input type="hidden" name="id_calon" id="editKandidat_id">

                <label>Pilih Voting</label>
                <select name="id_voting" id="editKandidat_id_voting">
                    <?php foreach($voting as $v): ?>
                    <option value="<?= $v['id_voting'] ?>"><?= $v['judul'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Nama Kandidat</label>
                <select name="id_pengguna" id="editKandidat_id_pengguna">
                    <?php foreach($anggota as $a): ?>
                    <option value="<?= $a['id_pengguna'] ?>"><?= $a['nama_lengkap'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Jabatan</label>
                <select name="jabatan" id="editKandidat_jabatan">
                    <option value="ketua">Ketua</option>
                    <option value="wakil">Wakil</option>
                    <option value="sekretaris 1">Sekretaris 1</option>
                    <option value="sekretaris 2">Sekretaris 2</option>
                    <option value="bendahara 1">Bendahara 1</option>
                    <option value="bendahara 2">Bendahara 2</option>
                </select>

                <label>No Paslon</label>
                <input type="number" name="no_paslon" id="editKandidat_no_paslon" required>

                <label>Visi</label>
                <textarea name="visi" rows="3" id="editKandidat_visi" required></textarea>

                <label>Misi</label>
                <textarea name="misi" rows="3" id="editKandidat_misi" required></textarea>

                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <!-- =========================
TABEL REKAP SUARA
========================= -->
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
            <?php 
        $no = 1; 
        foreach($kandidat as $row): 
            $totalSuara = $votingModel->countSuara($row['id_calon']); // hitung suara per kandidat
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['judul'] ?></td>
                <td><?= $row['nama_lengkap'] ?></td>
                <td><?= $row['jabatan'] ?></td>
                <td><?= $row['no_paslon'] ?></td>
                <td><?= $totalSuara ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<!-- =========================
POPUP TAMBAH VOTING
========================= -->

<div id="popupVoting" class="pages-voting-popup">
    <div class="popup-content">
        <span class="popup-close" onclick="closeVotingPopup()">×</span>
        <h2>Tambah Voting</h2>
        <form method="POST" action="../src/controllers/VotingController.php?action=createVoting">
            <label>Judul Voting</label>
            <input type="text" name="judul" required>

            <label>Periode</label>
            <input type="text" name="periode" required>

            <label>Tanggal Buka</label>
            <input type="date" name="tanggal_buka" required>

            <label>Tanggal Tutup</label>
            <input type="date" name="tanggal_tutup" required>

            <button type="submit">Tambah Voting</button>
        </form>
    </div>
</div>

<!-- Modal Popup Edit Voting -->
<div id="modalEdit" class="pages-voting-modal">
    <div class="modal-content">
        <span class="popup-close" onclick="closeEditModal()">×</span>
        <h3>Edit Voting</h3>
        <form action="../src/controllers/VotingController.php?action=updateVoting" method="POST">
            <input type="hidden" name="id_voting" id="edit_id">

            <label>Judul Voting</label>
            <input type="text" name="judul" id="edit_judul" required>

            <label>Periode</label>
            <input type="text" name="periode" id="edit_periode" required>

            <label>Tanggal Buka</label>
            <input type="date" name="tanggal_buka" id="edit_tanggal_buka" required>

            <label>Tanggal Tutup</label>
            <input type="date" name="tanggal_tutup" id="edit_tanggal_tutup" required>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>
    </div>
</div>

<!-- =========================
POPUP TAMBAH KANDIDAT
========================= -->

<div id="popupKandidat" class="pages-voting-popup">
    <div class="popup-content">
        <span class="popup-close" onclick="closeKandidatPopup()">×</span>
        <h3>Tambah Kandidat</h3>
        <form method="POST" action="../src/controllers/VotingController.php?action=createKandidat">
            <label>Pilih Voting</label>
            <select name="id_voting">
                <?php foreach($voting as $v): ?>
                <option value="<?= $v['id_voting'] ?>"><?= $v['judul'] ?></option>
                <?php endforeach; ?>
            </select>

            <label>Nama Kandidat</label>
            <select name="id_pengguna">
                <?php foreach($anggota as $a): ?>
                <option value="<?= $a['id_pengguna'] ?>"><?= $a['nama_lengkap'] ?></option>
                <?php endforeach; ?>
            </select>

            <label>Jabatan</label>
            <select name="jabatan">
                <option value="ketua">Ketua</option>
                <option value="wakil">Wakil</option>
                <option value="sekretaris 1">Sekretaris 1</option>
                <option value="sekretaris 2">Sekretaris 2</option>
                <option value="bendahara 1">Bendahara 1</option>
                <option value="bendahara 2">Bendahara 2</option>
            </select>

            <label>No Paslon</label>
            <input type="number" name="no_paslon" required>

            <label>Visi</label>
            <textarea name="visi" rows="3" required></textarea>

            <label>Misi</label>
            <textarea name="misi" rows="3" required></textarea>

            <br><br>
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>


<script>
// POPUP EDIT KANDIDAT
const modalEditKandidat = document.getElementById("modalEditKandidat");
const btnsEditKandidat = document.querySelectorAll(".btn-edit-kandidat");

btnsEditKandidat.forEach(btn => {
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

// Tutup jika klik di luar konten
window.addEventListener("click", (event) => {
    if (event.target == modalEditKandidat) {
        modalEditKandidat.style.display = "none";
    }
});
/* =========================
POPUP UBAH VOTING
========================= */
const modalEdit = document.getElementById("modalEdit");
const btnsEdit = document.querySelectorAll(".btn-edit");
const spanClose = modalEdit.querySelector(".close");

// Fungsi buka modal dan isi data
btnsEdit.forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("edit_id").value = btn.dataset.id;
        document.getElementById("edit_judul").value = btn.dataset.judul;
        document.getElementById("edit_periode").value = btn.dataset.periode;
        document.getElementById("edit_tanggal_buka").value = btn.dataset.tanggal_buka;
        document.getElementById("edit_tanggal_tutup").value = btn.dataset.tanggal_tutup;
        modalEdit.style.display = "flex";
    });
});

// Tutup modal
spanClose.onclick = () => {
    modalEdit.style.display = "none";
};

// Tutup modal jika klik di luar konten
window.onclick = (event) => {
    if (event.target == modalEdit) {
        modalEdit.style.display = "none";
    }
};

/* =========================
POPUP VOTING & KANDIDAT
========================= */
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
</script>
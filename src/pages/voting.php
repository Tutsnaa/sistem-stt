<?php
$tab = $_GET['tab'] ?? 'voting';

// cegah warning variable undefined
$voting = $voting ?? [];
$kandidat = $kandidat ?? [];
$anggota = $anggota ?? [];
$user = $user ?? [];
?>

<div class="pages-voting-container">

    <!-- ================= SUBMENU ================= -->
    <div class="submenu">
        <button class="submenu-btn <?= ($tab == 'voting') ? 'active' : '' ?>"
            onclick="showTab(this, 'voting')">Voting</button>

        <button class="submenu-btn <?= ($tab == 'kandidat') ? 'active' : '' ?>"
            onclick="showTab(this, 'kandidat')">Kandidat</button>

        <button class="submenu-btn <?= ($tab == 'rekap') ? 'active' : '' ?>"
            onclick="showTab(this, 'rekap')">Rekap</button>
    </div>

    <!-- ================= TAB VOTING ================= -->
    <div id="tab-voting" class="tab-content <?= ($tab == 'voting') ? 'active' : '' ?>">
        <div class="header-voting">
            <h2>Kelola Voting</h2>
            <button class="btn btn-tambah" onclick="openVotingPopup()">+ Tambah Voting</button>
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



                                <a href="#" class="btn-hapus"
                                    onclick="confirmHapus(<?= $row['id_voting']; ?>); return false;">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- ================= TAB KANDIDAT ================= -->
    <div id="tab-kandidat" class="tab-content <?= ($tab == 'kandidat') ? 'active' : '' ?>">

        <div class="header-kandidat">
            <h2>Kelola Kandidat</h2>
            <button class="btn-tambah" onclick="openKandidatPopup()">+ Tambah Kandidat</button>
        </div>
        <div class="table-wrapper-voting">
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
                            <!-- DETAIL -->
                            <button class="btn btn-detail" data-nama="<?= $row['nama_lengkap'] ?>"
                                data-voting="<?= $row['judul'] ?>" data-jabatan="<?= $row['jabatan'] ?>"
                                data-no="<?= $row['no_paslon'] ?>" data-visi="<?= $row['visi'] ?>"
                                data-misi="<?= $row['misi'] ?>" onclick="openDetailKandidat(this)">
                                <i class="fa fa-eye"></i>
                            </button>
                            <!-- Ubah -->
                            <button class="btn btn-ubah-kandidat" data-id="<?= $row['id_calon'] ?>"
                                data-id_voting="<?= $row['id_voting'] ?>" data-id_pengguna="<?= $row['id_pengguna'] ?>"
                                data-jabatan="<?= $row['jabatan'] ?>" data-no_paslon="<?= $row['no_paslon'] ?>"
                                data-visi="<?= $row['visi'] ?>" data-misi="<?= $row['misi'] ?>">
                                <i class="fa fa-pen-to-square"></i>
                            </button>
                            <!-- Hapus -->
                            <a href="#" class="btn btn-hapus"
                                onclick="confirmHapusKandidat(<?= $row['id_calon']; ?>); return false;">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                        <td>
                            <?php if($row['status']=='dibuka'): ?>
                            <a href="../src/controllers/VotingController.php?action=vote&id_calon=<?= $row['id_calon'] ?>"
                                class="btn btn-vote">Vote</a>
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

    <!-- =========================
   POPUP DETAIL KANDIDAT
========================= -->
    <div id="detailKandidatModal" class="dk-modal">
        <div class="dk-box">
            <span class="dk-close" onclick="closeDetailKandidat()">&times;</span>
            <h3>Detail Kandidat</h3>

            <div class="dk-row">
                <b>Nama</b>
                <span id="dk_nama"></span>
            </div>

            <div class="dk-row">
                <b>Voting</b>
                <span id="dk_voting"></span>
            </div>

            <div class="dk-row">
                <b>Jabatan</b>
                <span id="dk_jabatan"></span>
            </div>

            <div class="dk-row">
                <b>No</b>
                <span id="dk_no"></span>
            </div>

            <div class="dk-block">
                <b>Visi</b>
                <div id="dk_visi"></div>
            </div>

            <div class="dk-block">
                <b>Misi</b>
                <div id="dk_misi"></div>
            </div>
        </div>
    </div>

    <!-- ================= TAB REKAP ================= -->
    <div id="tab-rekap" class="tab-content <?= ($tab == 'rekap') ? 'active' : '' ?>">

        <div class="header-voting">
            <h2>Rekap Suara</h2>
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
<div id="modalEdit" class="popup popup-voting">
    <div class="popup-content popup-voting-content">
        <span class="popup-close">&times;</span>
        <h3>Ubah Voting</h3>

        <form class="form-voting" action="../src/controllers/VotingController.php?action=updateVoting" method="POST">
            <input type="hidden" name="id_voting" id="edit_id">

            <label>Judul Voting</label>
            <input type="text" name="judul" id="edit_judul" required>

            <label>Periode</label>
            <input type="text" name="periode" id="edit_periode" required>

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
        <h3>Ubah Kandidat</h3>
        <form class="form-voting" action="../src/controllers/KandidatController.php?action=updateKandidat"
            method="POST">
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
<div id="popupVoting" class="popup popup-voting">
    <div class="popup-content popup-voting-content">
        <span class="popup-close" onclick="closeVotingPopup()">&times;</span>
        <h3>Tambah Voting</h3>

        <form class="form-voting" action="../src/controllers/VotingController.php?action=createVoting" method="POST">
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
        <h3>Tambah Kandidat</h3>
        <form class="form-voting" action="../src/controllers/KandidatController.php?action=createKandidat"
            method="POST">
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
                <option value="ketua" <?= isset($row['jabatan']) && $row['jabatan']=='ketua' ? 'selected' : '' ?>>
                    Ketua
                </option>
                <option value="wakil" <?= isset($row['jabatan']) && $row['jabatan']=='wakil' ? 'selected' : '' ?>>
                    Wakil
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
            <input name="visi" required></input>
            <label>Misi</label>
            <input name="misi" required></input>
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>

<div id="modalHapus" class="modal-hapus">
    <div class="modal-box">
        <h3>Konfirmasi Hapus</h3>
        <p>Apakah kamu yakin ingin menghapus data anggota ini?</p>

        <div class="modal-actions">
            <button class="btn-batal" onclick="closeModalHapus()">Batal</button>
            <a id="btnYaHapus" class="btn-hapus-yes">Ya, Hapus</a>
        </div>
    </div>
</div>


<script>
// ================= DETAIL KANDIDAT =================
function openDetailKandidat(btn) {

    console.log("Klik detail"); // debug

    const modal = document.getElementById("detailKandidatModal");

    if (!modal) {
        console.error("Modal tidak ditemukan!");
        return;
    }

    document.getElementById("dk_nama").innerText = btn.dataset.nama || '-';
    document.getElementById("dk_voting").innerText = btn.dataset.voting || '-';
    document.getElementById("dk_jabatan").innerText = btn.dataset.jabatan || '-';
    document.getElementById("dk_no").innerText = btn.dataset.no || '-';
    document.getElementById("dk_visi").innerText = btn.dataset.visi || '-';
    document.getElementById("dk_misi").innerText = btn.dataset.misi || '-';

    modal.style.display = "flex";
}

function closeDetailKandidat() {
    const modal = document.getElementById("detailKandidatModal");
    if (modal) modal.style.display = "none";
}
// ================= AUTO TAB DARI URL =================
document.addEventListener("DOMContentLoaded", function() {

    const params = new window.URLSearchParams(window.location.search);

    let tab = params.get("tab");

    if (!tab) {
        tab = "voting";
    }

    const btn = document.querySelector(`.submenu-btn[onclick*="${tab}"]`);

    if (btn) {
        showTab(btn, tab);
    }
});
// ================= FLASH MESSAGE =================
setTimeout(function() {
    var msg = document.getElementById('flash-message');
    if (msg) {
        msg.style.display = 'none';
    }
}, 3000);


// ================= HAPUS =================
function confirmHapus(id) {
    console.log("Hapus ID:", id);

    document.getElementById("modalHapus").style.display = "block";

    // set link hapus
    document.getElementById("btnYaHapus").href =
        "../src/controllers/VotingController.php?action=hapus&id=" + id;
}

function closeModalHapus() {
    document.getElementById("modalHapus").style.display = "none";
}


function confirmHapusKandidat(id) {
    console.log("Hapus Kandidat ID:", id);

    document.getElementById("modalHapus").style.display = "block";

    document.getElementById("btnYaHapus").href =
        "../src/controllers/KandidatController.php?action=deleteKandidat&id=" + id;
}

// ================= POPUP TAMBAH =================
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


// ================= EDIT VOTING =================
const modalEdit = document.getElementById("modalEdit");

document.querySelectorAll(".btn-ubah").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("edit_id").value = btn.dataset.id;
        document.getElementById("edit_judul").value = btn.dataset.judul;
        document.getElementById("edit_masa_awal").value = btn.dataset.masa_awal;
        document.getElementById("edit_masa_akhir").value = btn.dataset.masa_akhir;
        document.getElementById("edit_periode").value = btn.dataset.periode;
        document.getElementById("edit_tanggal_buka").value = btn.dataset.tanggal_buka;
        document.getElementById("edit_tanggal_tutup").value = btn.dataset.tanggal_tutup;

        if (btn.dataset.status) {
            document.getElementById("edit_status").value = btn.dataset.status;
        }

        modalEdit.style.display = "flex";
    });
});

// tombol close (X)
modalEdit.querySelector(".popup-close").onclick = () => {
    modalEdit.style.display = "none";
};


// ================= EDIT KANDIDAT =================
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


// ================= GLOBAL CLICK (FIX UTAMA) =================
window.addEventListener("click", function(event) {
    const modalHapus = document.getElementById("modalHapus");

    // klik luar modal hapus
    if (event.target === modalHapus) {
        modalHapus.style.display = "none";
    }

    // klik luar modal edit voting
    if (event.target === modalEdit) {
        modalEdit.style.display = "none";
    }

    // klik luar modal edit kandidat
    if (event.target === modalEditKandidat) {
        modalEditKandidat.style.display = "none";
    }
});


// ================= TAB =================
function showTab(e, tab) {

    // hapus semua active
    document.querySelectorAll(".tab-content").forEach(el => el.classList.remove("active"));
    document.querySelectorAll(".submenu-btn").forEach(btn => btn.classList.remove("active"));

    // aktifkan tab & tombol
    document.getElementById("tab-" + tab).classList.add("active");
    e.classList.add("active");

    // update URL tanpa reload
    const url = new window.URL(window.location.href);

    url.searchParams.set('tab', tab);

    window.history.pushState({}, '', url);

    // simpan tab
    localStorage.setItem("activeTab", tab);
}
</script>
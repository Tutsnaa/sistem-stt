<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$user = $_SESSION['user'] ?? null;

// Cek apakah pengguna adalah anggota
$isAnggota = (isset($_SESSION['user']) && $_SESSION['user']['jabatan'] == 'anggota');
?>


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

<div class="page-content">

    <!-- ================= POPUP TAMBAH DATA ================= -->
    <div id="popupForm" class="popup">
        <div class="popup-content popup-keuangan">

            <span class="close" onclick="closePopup()">&times;</span>

            <h3>Tambah Data Keuangan</h3>

            <form action="../src/controllers/KeuanganController.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="action" value="simpan">
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
                    <input type="text" name="jumlah" id="jumlah" required>
                </div>

                <div class="form-group">
                    <label>Upload Bukti</label>
                    <input type="file" name="file_bukti">
                </div>

                <button type="submit" class="btn-save">Simpan</button>

            </form>

        </div>
    </div>


    <!-- ================= MODAL EDIT (HANYA UNTUK ADMIN) ================= -->
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>

    <div id="editModal" class="modal">

        <div class="modal-content">

            <!-- Tombol close modal -->
            <span class="close" onclick="closeModal()">&times;</span>

            <h3>Edit Data Keuangan</h3>

            <!-- Form edit -->
            <form action="../src/controllers/KeuanganController.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="action" value="update">
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
                    <input type="text" name="jumlah" id="edit_jumlah" required>
                </div>

                <div class="form-group">
                    <label>Ubah Bukti (Opsional)</label>
                    <input type="file" name="file_bukti">
                </div>

                <button type="submit" class="btn-save">Simpan</button>

            </form>

        </div>
    </div>
    <?php } ?>


    <!-- ================= JUDUL TABEL ================= -->
    <h2>Data Keuangan</h2>
    <div class="filter-container">

        <!-- KIRI -->
        <div class="filter-left">
            <form method="GET">
                <input type="hidden" name="page" value="keuangan">

                <select name="filter" onchange="this.form.submit()" class="filter-dropdown">
                    <option value="all" <?= (!isset($_GET['filter']) || $_GET['filter']=='all') ? 'selected' : '' ?>>
                        Semua</option>
                    <option value="pemasukan"
                        <?= (isset($_GET['filter']) && $_GET['filter']=='pemasukan') ? 'selected' : '' ?>>Pemasukan
                    </option>
                    <option value="pengeluaran"
                        <?= (isset($_GET['filter']) && $_GET['filter']=='pengeluaran') ? 'selected' : '' ?>>Pengeluaran
                    </option>
                </select>
            </form>

            <?php
function getDashboard() {
    $jabatan = strtolower($_SESSION['user']['jabatan']);
    $pengurus = ['admin','ketua','wakil','sekretaris 1','sekretaris 2','bendahara 1','bendahara 2'];

    return in_array($jabatan, $pengurus)
        ? 'dashboard_pengurus.php'
        : 'dashboard_anggota.php';
}
?>

            <form method="GET" class="filter-tanggal">
                <input type="hidden" name="page" value="keuangan">
                <input type="hidden" name="filter" value="<?= $_GET['filter'] ?? 'all'; ?>">

                <label>Awal</label>
                <input type="date" name="tanggal_awal" value="<?= $_GET['tanggal_awal'] ?? '' ?>">

                <label>Akhir</label>
                <input type="date" name="tanggal_akhir" value="<?= $_GET['tanggal_akhir'] ?? '' ?>">

                <button type="submit">Filter</button>

                <a href="<?= getDashboard(); ?>?page=keuangan&filter=<?= $_GET['filter'] ?? 'all'; ?>"
                    class="btn-reset">
                    <i class="fa fa-rotate-right"></i>
                </a>
            </form>

            <?php if(isset($_SESSION['user']) && $_SESSION['user']['jabatan'] != 'anggota'){ ?>
            <a href="../src/controllers/KeuanganController.php?action=download
&filter=<?= $_GET['filter'] ?? 'all'; ?>
&bulan=<?= $_GET['bulan'] ?? ''; ?>
&tahun=<?= $_GET['tahun'] ?? ''; ?>
&tanggal_awal=<?= $_GET['tanggal_awal'] ?? ''; ?>
&tanggal_akhir=<?= $_GET['tanggal_akhir'] ?? ''; ?>" class="btn-download">
                Unduh Excel
            </a>

            <a href="../src/controllers/KeuanganController.php?action=pdf
&filter=<?= $_GET['filter'] ?? 'all'; ?>
&bulan=<?= $_GET['bulan'] ?? ''; ?>
&tahun=<?= $_GET['tahun'] ?? ''; ?>
&tanggal_awal=<?= $_GET['tanggal_awal'] ?? ''; ?>
&tanggal_akhir=<?= $_GET['tanggal_akhir'] ?? ''; ?>" class="btn-download">
                Unduh PDF
            </a>
            <?php } ?>
        </div>

        <!-- KANAN -->
        <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'ketua' &&
    $_SESSION['user']['jabatan'] != 'wakil' &&
    $_SESSION['user']['jabatan'] != 'anggota' &&
    $_SESSION['user']['jabatan'] != 'sekretaris 1' &&
    $_SESSION['user']['jabatan'] != 'sekretaris 2'
) { 
?>

        <button class="btn-tambah" onclick="openPopup()">+ Tambah Data</button>
        <?php } ?>

    </div>

    <!-- ================= TABEL DATA KEUANGAN ================= -->
    <div class="table-wrapper-keuangan">
        <div class="table-keuangan">
            <!-- ================= TABEL ================= -->
            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Bukti</th>
                        <?php if(!$isAnggota){ ?>
                        <th>Status</th>
                        <?php } ?>
                        <th>Tanggal</th>
                        <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'ketua' &&
    $_SESSION['user']['jabatan'] != 'wakil' &&
    $_SESSION['user']['jabatan'] != 'anggota' &&
    $_SESSION['user']['jabatan'] != 'sekretaris 1' &&
    $_SESSION['user']['jabatan'] != 'sekretaris 2'
) { 
?>
                        <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody>

                    <?php
$filter = $_GET['filter'] ?? 'all';
$tanggalAwal = $_GET['tanggal_awal'] ?? null;
$tanggalAkhir = $_GET['tanggal_akhir'] ?? null;

$data = $keuanganModel->getAll();

$no = 1;
foreach ($data as $row):

     // Jika yang login anggota, hanya tampilkan yang disetujui
    if ($isAnggota && $row['status'] != 'disetujui') {
        continue;
    }


    $tanggalRow = date('Y-m-d', strtotime($row['tanggal_dibuat']));

    // FILTER JENIS
    if ($filter == 'pemasukan' && $row['jenis'] != 'pemasukan') continue;
    if ($filter == 'pengeluaran' && $row['jenis'] != 'pengeluaran') continue;

    // FILTER TANGGAL
    if ($tanggalAwal && $tanggalRow < $tanggalAwal) continue;
    if ($tanggalAkhir && $tanggalRow > $tanggalAkhir) continue;
?>

                    <tr>

                        <!-- Nomor -->
                        <td style="text-align: center;"><?= $no++; ?></td>

                        <!-- Jenis (warna beda) -->
                        <td>
                            <?php if($row['jenis']=='pemasukan'){ ?>
                            <span style="color:green;">Pemasukan</span>
                            <?php } else { ?>
                            <span style="color:red;">Pengeluaran</span>
                            <?php } ?>
                        </td>

                        <!-- Keterangan -->
                        <td class="kolom-keterangan"><?= $row['keterangan']; ?></td>

                        <!-- Jumlah -->
                        <td>Rp <?= number_format($row['jumlah'],0,',','.'); ?></td>

                        <!-- Bukti -->
                        <td class="td-lihat">
                            <?php if($row['file_bukti']){ ?>
                            <button class="btn-lihat" onclick="openBukti('<?= $row['file_bukti']; ?>')">
                                Lihat
                            </button>
                            <?php } else { ?>
                            -
                            <?php } ?>
                        </td>

                        <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'ketua' &&
    $_SESSION['user']['jabatan'] != 'admin' &&
    $_SESSION['user']['jabatan'] != 'wakil' 
) { 
?>

                        <!-- STATUS (untuk non anggota) -->
                        <?php if(!$isAnggota){ ?>
                        <td class="aksi-status">

                            <?php if($row['status'] == 'menunggu'): ?>
                            <span class="badge badge-menunggu">Menunggu</span>

                            <?php elseif($row['status'] == 'disetujui'): ?>
                            <span class="badge badge-disetujui">Disetujui</span>

                            <?php elseif($row['status'] == 'ditolak'): ?>
                            <span class="badge badge-ditolak">Ditolak</span>

                            <?php else: ?>
                            <span class="badge">tidak diketahui</span>
                            <?php endif; ?>

                        </td>
                        <?php } ?>

                        <?php } ?>


                        <?php if (
    $_SESSION['user']['jabatan'] == 'ketua' ||
    $_SESSION['user']['jabatan'] == 'admin'
): ?>

                        <!-- AKSI KEUANGAN -->
                        <td class="aksi-status">

                            <?php if($row['status'] == 'menunggu'): ?>

                            <a href="javascript:void(0)" class="btn-terima"
                                onclick="confirmSetujui('../src/controllers/KeuanganController.php?action=terima&id=<?= $row['id_keuangan']; ?>')">
                                Disetujui
                            </a>

                            <a href="javascript:void(0)" class="btn-tolak"
                                onclick="confirmTolak('../src/controllers/KeuanganController.php?action=tolak&id=<?= $row['id_keuangan']; ?>')">
                                Ditolak
                            </a>

                            <?php elseif($row['status'] == 'disetujui'): ?>

                            <span class="badge-disetujui">Disetujui</span>

                            <?php elseif($row['status'] == 'ditolak'): ?>

                            <span class="badge-ditolak">Ditolak</span>

                            <?php endif; ?>

                        </td>

                        <?php endif; ?>

                        <!-- Tanggal -->
                        <td style="text-align: center;">
                            <?= date('d-m-Y', strtotime($row['tanggal_dibuat'])); ?>
                        </td>

                        <!-- Aksi (admin only) -->
                        <?php 
if (
    isset($_SESSION['user']) && 
    $_SESSION['user']['jabatan'] != 'ketua' &&
    $_SESSION['user']['jabatan'] != 'wakil' &&
    $_SESSION['user']['jabatan'] != 'anggota' &&
    $_SESSION['user']['jabatan'] != 'sekretaris 1' &&
    $_SESSION['user']['jabatan'] != 'sekretaris 2'
) { 
?>
                        <td style="text-align: center;">

                            <!-- ================= EDIT (TERKUNCI JIKA DISETUJUI) ================= -->
                            <?php if($row['status'] != 'disetujui'): ?>
                            <button class="btn-ubah-keuangan" data-id="<?= $row['id_keuangan']; ?>"
                                data-jenis="<?= $row['jenis']; ?>" data-keterangan="<?= $row['keterangan']; ?>"
                                data-jumlah="<?= $row['jumlah']; ?>" data-file="<?= $row['file_bukti']; ?>"
                                onclick="openEditModal(this)">
                                <i class="fa fa-pen-to-square"></i>
                            </button>
                            <?php endif; ?>

                            <a href="#" class="btn-hapus-keuangan"
                                onclick="confirmHapus(<?= $row['id_keuangan']; ?>); return false;">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                        <?php } ?>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

<!-- popup lihat bukti -->
<div id="modalBukti" class="modal-bukti">
    <div class="modal-content-bukti">
        <span class="close-btn" onclick="closeBukti()">&times;</span>

        <!-- GAMBAR -->
        <img id="imgBukti" src="" style="display:none;" />

        <!-- PDF -->
        <iframe id="pdfBukti" class="pdf-viewer"></iframe>

    </div>
</div>

<style>
/* MODAL BACKGROUND */
.modal-bukti {
    display: none;
    position: fixed;
    z-index: 9999;

    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    background-color: rgba(0, 0, 0, 0.8);

    justify-content: center;

    /* agar tidak ketutup navbar */
    align-items: flex-start;
    padding-top: 80px;
}

/* aktif saat dibuka */
.modal-bukti.show {
    display: flex;
}

/* KONTEN (ikuti ukuran gambar/PDF) */
.modal-content-bukti {
    position: relative;
    display: inline-block;
    /* KUNCI AGAR IKUT KONTEN */
    max-width: 90%;
    max-height: 90%;
}

/* GAMBAR */
.modal-content-bukti img {
    display: none;
    max-width: 100%;
    max-height: 80vh;
    border-radius: 10px;
}

/* PDF */
.pdf-viewer {
    display: none;
    width: 80vw;
    height: 80vh;
    border: none;
    border-radius: 10px;
}

/* TOMBOL CLOSE */
.close-btn {
    position: absolute;
    top: -10px;
    right: -10px;

    background: white;
    color: black;

    width: 35px;
    height: 35px;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 18px;
    font-weight: bold;
    cursor: pointer;

    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    transition: 0.2s;
}

.close-btn:hover {
    background: #f1f1f1;
    transform: scale(1.1);
}
</style>


<!-- ================= SCRIPT ================= -->
<script>
// Format Jumlah
const jumlah = document.getElementById('jumlah');

jumlah.addEventListener('input', function() {

    // Hapus semua karakter selain angka
    let angka = this.value.replace(/\D/g, '');

    // Tambahkan titik sebagai pemisah ribuan
    this.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    function formatRupiah(input) {
        input.addEventListener('input', function() {
            let angka = this.value.replace(/\D/g, '');
            this.value = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });
    }

    // Form tambah
    const jumlah = document.getElementById('jumlah');
    if (jumlah) {
        formatRupiah(jumlah);
    }

    // Form edit
    const editJumlah = document.getElementById('edit_jumlah');
    if (editJumlah) {
        formatRupiah(editJumlah);
    }
});


// Lihat Bukti
function openBukti(file) {
    let modal = document.getElementById("modalBukti");
    let img = document.getElementById("imgBukti");
    let pdf = document.getElementById("pdfBukti");

    modal.style.display = "flex";

    let filePath = "../uploads/" + file;

    // 🔥 CEK EXTENSION FILE
    let ext = file.split('.').pop().toLowerCase();

    if (ext === "pdf") {
        pdf.style.display = "flex";
        img.style.display = "none";
        pdf.src = filePath;
    } else {
        img.style.display = "flex";
        pdf.style.display = "none";
        img.src = filePath;
    }
}

function closeBukti() {
    document.getElementById("modalBukti").style.display = "none";

    // reset
    document.getElementById("imgBukti").src = "";
    document.getElementById("pdfBukti").src = "";
}

// ================= FORMAT RUPIAH =================
function formatRupiah(angka) {
    return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// ================= FORMAT INPUT =================
function handleRupiahInput(input) {
    input.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = value ? formatRupiah(value) : '';
    });
}

// ================= AKTIFKAN SEMUA INPUT JUMLAH =================
document.querySelectorAll("form").forEach(function(form) {
    form.addEventListener("submit", function() {
        let input = this.querySelector('input[name="jumlah"]');
        if (input) {
            input.value = input.value.replace(/\./g, ''); // 🔥 hapus titik
        }
    });
});


function confirmHapus(id) {
    document.getElementById("modalHapus").style.display = "block";

    // set link hapus
    document.getElementById("btnYaHapus").href =
        "../src/controllers/KeuanganController.php?action=hapus&id=" + id;
}

function closeModalHapus() {
    document.getElementById("modalHapus").style.display = "none";
}

// klik luar modal = tutup
window.onclick = function(event) {
    const modal = document.getElementById("modalHapus");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}

// ================= EDIT MODAL =================
function openEditModal(button) {
    document.getElementById("editModal").style.display = "flex";

    document.getElementById("edit_id").value = button.dataset.id;
    document.getElementById("edit_jenis").value = button.dataset.jenis;
    document.getElementById("edit_keterangan").value = button.dataset.keterangan;
    let jumlah = button.dataset.jumlah;

    // 🔥 BUANG DESIMAL (.00)
    jumlah = parseFloat(jumlah).toString();

    // 🔥 HAPUS SEMUA SELAIN ANGKA (BIAR AMAN)
    jumlah = jumlah.replace(/\D/g, '');

    document.getElementById("edit_jumlah").value = formatRupiah(jumlah);
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}

// klik luar modal = tutup
window.addEventListener("click", function(e) {
    let popup = document.getElementById("popupForm");
    if (e.target === popup) {
        popup.classList.remove("show");
    }
});


// ================= POPUP TAMBAH =================
function openPopup() {
    document.getElementById("popupForm").classList.add("show");
}

function closePopup() {
    document.getElementById("popupForm").classList.remove("show");
}
</script>
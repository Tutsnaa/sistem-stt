<?php
$dataAnggota = $dataAnggota ?? [];
$page = $page ?? 'home';
?>

<!-- ================= HOME ================= -->
<div id="home" class="hero-section">
    <div class="hero-container">

        <div class="hero-text">

            <h1>Sekaa Truna Truni Galuh Mantri </h1>
            <h3>Media Informasi, Kegiatan, dan Administrasi Organisasi</h3>

            <p>
                Sekaa Truna Truni Galuh Mantri yang berlokasi di Banjar Celuk, Kecamatan Sukawati,
                Kabupaten Gianyar merupakan organisasi kepemudaan yang menjadi
                wadah kebersamaan dan kreativitas generasi muda.
            </p>

        </div>

        <div class="hero-image">
            <div class="image-blob">
                <img src="../asset/img/LOGOSTT.png">
            </div>
        </div>

    </div>
</div>



<!-- =========================
     SECTION PENGURUS
========================= -->
<?php
// daftar jabatan pengurus
$pengurusList = [
    'admin',
    'ketua',
    'wakil',
    'sekretaris 1',
    'sekretaris 2',
    'bendahara 1',
    'bendahara 2'
];

// urutan jabatan
$urutanJabatan = [
    'ketua' => 1,
    'wakil' => 2,
    'sekretaris 1' => 3,
    'sekretaris 2' => 4,
    'bendahara 1' => 5,
    'bendahara 2' => 6
];

// filter hanya pengurus
$pengurus = array_filter($dataAnggota, function($row) use ($pengurusList) {
    return !empty($row['jabatan']) && in_array(strtolower($row['jabatan']), $pengurusList);
});

// urutkan sesuai jabatan
usort($pengurus, function($a, $b) use ($urutanJabatan) {
    $jabA = strtolower($a['jabatan']);
    $jabB = strtolower($b['jabatan']);

    return ($urutanJabatan[$jabA] ?? 99) <=> ($urutanJabatan[$jabB] ?? 99);
});
?>

<!-- ================= PENGURUS ================= -->
<div class="section-pengurus">

    <h2>Struktur Pengurus</h2>

    <div class="pengurus-container">

        <?php foreach($pengurus as $row): ?>

        <div class="pengurus-card">

            <!-- FOTO PENGURUS -->
            <div class="pengurus-foto">

                <?php if(!empty($row['foto'])): ?>

                <img src="../uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto Pengurus">

                <?php else: ?>

                <img src="../asset/img/default.png" alt="Foto Default">

                <?php endif; ?>

            </div>

            <!-- NAMA PENGURUS -->
            <h3><?= htmlspecialchars($row['nama_lengkap']); ?></h3>

            <!-- JABATAN -->
            <p class="jabatan">
                <?= htmlspecialchars($row['jabatan']); ?>
            </p>

            <!-- KONTAK -->
            <?php if(isset($_SESSION['user']['id_pengguna'])){ ?>

            <div class="pengurus-info">

                <p><?= htmlspecialchars($row['email']); ?></p>

                <p><?= htmlspecialchars($row['no_hp']); ?></p>

            </div>

            <?php } ?>

        </div>

        <?php endforeach; ?>

    </div>

</div>



<!-- =========================
     SECTION PENGUMUMAN
========================= -->
<div id="pengumuman" class="section-pengumuman">

    <h2>Pengumuman</h2>

    <div class="pengumuman-list">

        <?php if(!empty($dataPengumuman)): ?>
        <?php foreach($dataPengumuman as $p): ?>

        <?php if($p['status'] == 'Disetujui'): ?>

        <div class="pengumuman-card">

            <!-- Judul -->
            <h3><?= htmlspecialchars($p['judul']) ?></h3>

            <!-- Isi -->
            <p><?= nl2br(htmlspecialchars($p['isi'])) ?></p>

            <?php
            $icon = "fa-file";
            $color = "#555";
            ?>

            <?php if($p['file']): ?>

            <?php 
            $ext = strtolower(pathinfo($p['file'], PATHINFO_EXTENSION));
            $fileUrl = "../uploads/".$p['file'];
            ?>

            <!-- ================= FILE GAMBAR ================= -->
            <?php if(in_array($ext, ['jpg','jpeg','png','gif'])): ?>
            <img src="<?= $fileUrl ?>" alt="File Pengumuman">

            <!-- ================= FILE PDF ================= -->
            <?php elseif($ext === 'pdf'): ?>
            <div class="file-card" onclick="window.open('<?= $fileUrl ?>', '_blank')">
                <div class="file-info">
                    <i class="fa-solid fa-file"></i>
                    <span><?= basename($p['file']); ?></span>
                </div>
            </div>

            <?php

                $icon = "fa-file";
                $color = "#555";

                if(in_array($ext, ['doc','docx'])){
                    $icon = "fa-file-word";
                    $color = "#2b579a";
                }
                elseif(in_array($ext, ['xls','xlsx'])){
                  $icon = "fa-file-excel";
                  $color = "#217346";
                }
                elseif(in_array($ext, ['ppt','pptx'])){
                 $icon = "fa-file-powerpoint";
                 $color = "#d24726";
                }

            ?>

            <!-- ================= FILE OFFICE ================= -->
            <?php elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx'])): ?>
            <div class="file-card" onclick="window.open('<?= $fileUrl ?>', '_blank')">
                <div class="file-info">
                    <i class="fa-solid <?= $icon ?>" style="color: <?= $color ?>"></i>
                    <span><?= basename($p['file']); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <?php endif; ?>

            <!-- 🔥 FOOTER (SATU SAJA) -->
            <div class="pengumuman-footer">

                <?php if($p['file']): ?>
                <a href="<?= $fileUrl ?>" download class="btn-download-pengumuman">
                    Unduh
                </a>
                <?php endif; ?>

                <small>
                    Oleh: <?= $p['nama_lengkap'] ?><br>
                    <?= date('d M Y', strtotime($p['tanggal_dibuat'])) ?>
                </small>

            </div>

        </div>

        <?php endif; ?>

        <?php endforeach; ?>
        <?php else: ?>

        <p>Tidak ada pengumuman.</p>

        <?php endif; ?>

    </div>

</div>

<!-- ================= FOOTER ================= -->
<footer id="kontak" class="footer">

    <div class="footer-container">

        <!-- Bagian Kiri -->
        <div class="footer-left">
            <h3>Sistem Informasi Organisasi</h3>
            <p>
                Sistem ini digunakan untuk mengelola data anggota, pengumuman,
                keuangan, dan voting organisasi secara digital.
            </p>
        </div>


        <?php
$dashboard = (isset($_SESSION['user']) && $_SESSION['user']['jabatan'] == 'anggota')
    ? 'dashboard_anggota.php'
    : 'dashboard_umum.php';
?>
        <!-- Bagian Tengah -->
        <div class="footer-center">
            <h4>Menu</h4>
            <ul>
                <li>
                    <a href="<?= $dashboard; ?>?page=home"
                        class="<?php echo ($page == 'home' && !isset($_GET['section'])) ? 'active' : ''; ?>">
                        Beranda
                    </a>
                </li>

                <li>
                    <a href="<?= $dashboard; ?>?page=home&section=pengumuman#pengumuman"
                        class="<?php echo (isset($_GET['section']) && $_GET['section'] == 'pengumuman') ? 'active' : ''; ?>">
                        Pengumuman
                    </a>
                </li>

                <li>
                    <a href="<?= isset($_SESSION['user']) ? 'dashboard_anggota.php?page=anggota' : '#' ?>"
                        <?php if(!isset($_SESSION['user'])) echo 'onclick="openLogin()"'; ?>>
                        Data Anggota
                    </a>
                </li>

                <li>
                    <a href="<?= isset($_SESSION['user']) ? 'dashboard_anggota.php?page=keuangan' : '#' ?>"
                        <?php if(!isset($_SESSION['user'])) echo 'onclick="openLogin()"'; ?>>
                        Keuangan
                    </a>
                </li>

                <li>
                    <a href="<?= isset($_SESSION['user']) ? 'dashboard_anggota.php?page=voting' : '#' ?>"
                        <?php if(!isset($_SESSION['user'])) echo 'onclick="openLogin()"'; ?>>
                        Voting
                    </a>
                </li>

                <li>
                    <a href="<?= $dashboard; ?>?page=home&section=kontak#kontak"
                        class="<?php echo (isset($_GET['section']) && $_GET['section'] == 'kontak') ? 'active' : ''; ?>">
                        Kontak
                    </a>
                </li>
            </ul>
        </div>

        <!-- Bagian Kanan -->
        <div class="footer-right">
            <h4>Kontak</h4>
            <p>Email : organisasi@email.com</p>
            <p>Telp : 0812-3456-7890</p>
            <p class="alamat-footer">
                Alamat : Jl. Raya Celuk No.11x, Celuk, Kec. Sukawati,
                Kabupaten Gianyar, Bali 80582
            </p>
        </div>

    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <p>© 2026 Sistem Informasi Organisasi | All Rights Reserved</p>
    </div>

</footer>
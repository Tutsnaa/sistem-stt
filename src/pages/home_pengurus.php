<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">

    <div class="main-content-header">
        <h2>Informasi Keuangan</h2>

        <!-- ===== FILTER ===== -->
        <div class="filter-box">
            <form method="GET" action="dashboard_pengurus.php">

                <input type="hidden" name="page" value="home_pengurus">

                <select name="bulan">
                    <option value="">-- Pilih Bulan --</option>
                    <option value="1" <?= ($_GET['bulan'] ?? '') == 1 ? 'selected' : '' ?>>Januari</option>
                    <option value="2" <?= ($_GET['bulan'] ?? '') == 2 ? 'selected' : '' ?>>Februari</option>
                    <option value="3" <?= ($_GET['bulan'] ?? '') == 3 ? 'selected' : '' ?>>Maret</option>
                    <option value="4" <?= ($_GET['bulan'] ?? '') == 4 ? 'selected' : '' ?>>April</option>
                    <option value="5" <?= ($_GET['bulan'] ?? '') == 5 ? 'selected' : '' ?>>Mei</option>
                    <option value="6" <?= ($_GET['bulan'] ?? '') == 6 ? 'selected' : '' ?>>Juni</option>
                    <option value="7" <?= ($_GET['bulan'] ?? '') == 7 ? 'selected' : '' ?>>Juli</option>
                    <option value="8" <?= ($_GET['bulan'] ?? '') == 8 ? 'selected' : '' ?>>Agustus</option>
                    <option value="9" <?= ($_GET['bulan'] ?? '') == 9 ? 'selected' : '' ?>>September</option>
                    <option value="10" <?= ($_GET['bulan'] ?? '') == 10 ? 'selected' : '' ?>>Oktober</option>
                    <option value="11" <?= ($_GET['bulan'] ?? '') == 11 ? 'selected' : '' ?>>November</option>
                    <option value="12" <?= ($_GET['bulan'] ?? '') == 12 ? 'selected' : '' ?>>Desember</option>
                </select>

                <select name="tahun">
                    <option value="">-- Pilih Tahun --</option>

                    <?php for($i = 2026; $i <= date('Y'); $i++): ?>
                    <option value="<?= $i ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : '' ?>>
                        <?= $i ?>
                    </option>
                    <?php endfor; ?>
                </select>

                <button type="submit">Filter</button>
                <a href="dashboard_pengurus.php?page=home_pengurus" class="btn-reset">Reset</a>

            </form>
        </div>

        <!-- ===== CARD ===== -->
        <div class="card-container">

            <div class="card pemasukan">
                <div class="card-icon">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>
                <div class="card-text">
                    <h3>Pemasukan</h3>
                    <div class="card-amount">
                        Rp <?= number_format($totalPemasukan,0,',','.'); ?>
                    </div>
                </div>
            </div>

            <div class="card pengeluaran">
                <div class="card-icon">
                    <i class="fa-solid fa-arrow-up"></i>
                </div>
                <div class="card-text">
                    <h3>Pengeluaran</h3>
                    <div class="card-amount">
                        Rp <?= number_format($totalPengeluaran,0,',','.'); ?>
                    </div>
                </div>
            </div>

            <div class="card kas">
                <div class="card-icon">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="card-text">
                    <h3>Uang Kas</h3>
                    <div class="card-amount">
                        Rp <?= number_format($uangKas,0,',','.'); ?>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- =========================
     REKAP SUARA PER JABATAN
========================= -->
    <div class="rekap-wrapper">

        <h2 class="rekap-main-title">Rekap Suara Kandidat</h2>

        <?php
    $kategori = ['ketua', 'wakil', 'sekretaris', 'bendahara'];

    foreach($kategori as $jabatan):

        $filtered = array_filter($kandidat, function($row) use ($jabatan){
            return strtolower($row['jabatan']) === $jabatan;
        });

        if(empty($filtered)) continue;
    ?>

        <!-- CONTAINER PER JABATAN -->
        <div class="rekap-group">

            <h3 class="rekap-group-title">
                Calon <?= ucfirst($jabatan) ?>
            </h3>

            <div class="rekap-container">

                <?php foreach($filtered as $row): ?>
                <div class="rekap-card">

                    <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>" class="rekap-img">

                    <div class="rekap-info">
                        <h3><?= htmlspecialchars($row['nama_lengkap']) ?></h3>
                        <p>No: <?= htmlspecialchars($row['no_paslon']) ?></p>

                        <div class="rekap-suara">
                            <?= $votingModel->countSuara($row['id_calon']) ?> Suara
                        </div>
                    </div>

                </div>
                <?php endforeach; ?>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <!-- =========================
     SECTION PENGUMUMAN PENGURUS
========================= -->
    <div id="pengumuman" class="pengurus-pengumuman-section">

        <h2 class="pengurus-title">Pengumuman</h2>

        <div class="pengurus-pengumuman-list">

            <?php if(!empty($dataPengumuman)): ?>
            <?php foreach($dataPengumuman as $p): ?>

            <?php if($p['status'] == 'tampil'): ?>

            <div class="pengurus-pengumuman-card">

                <h3 class="pengurus-judul"><?= htmlspecialchars($p['judul']) ?></h3>

                <p class="pengurus-isi"><?= nl2br(htmlspecialchars($p['isi'])) ?></p>

                <?php if($p['file']): ?>

                <?php 
            $ext = strtolower(pathinfo($p['file'], PATHINFO_EXTENSION));
            $fileUrl = "../uploads/".$p['file'];
            ?>

                <?php if(in_array($ext, ['jpg','jpeg','png','gif'])): ?>

                <img src="<?= $fileUrl ?>" class="pengurus-img">

                <a href="<?= $fileUrl ?>" download class="pengurus-btn">
                    Unduh
                </a>

                <?php elseif($ext === 'pdf'): ?>

                <div class="file-card" onclick="window.open('<?= $fileUrl ?>', '_blank')">

                    <div class="file-info">
                        <i class="fa-solid fa-file"></i>

                        <span><?= basename($p['file']); ?></span>
                    </div>


                </div>

                <a href="<?= $fileUrl ?>" download class="pengurus-btn">
                    Unduh
                </a>

                <?php elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx'])): ?>

                <div class="file-card" onclick="window.open('<?= $fileUrl ?>', '_blank')">

                    <div class="file-info">
                        <i class="fa-solid fa-file"></i>

                        <span><?= basename($p['file']); ?></span>
                    </div>


                </div>

                <a href="<?= $fileUrl ?>" download class="pengurus-btn">
                    unduh
                </a>

                <?php else: ?>

                <a href="<?= $fileUrl ?>" download class="pengurus-btn">
                    unduh
                </a>

                <?php endif; ?>

                <?php endif; ?>

                <small class="pengurus-info">
                    Oleh: <?= $p['nama_lengkap'] ?><br>
                    <?= date('d M Y', strtotime($p['tanggal_dibuat'])) ?>
                </small>

            </div>

            <?php endif; ?>

            <?php endforeach; ?>
            <?php else: ?>

            <p class="pengurus-kosong">Tidak ada pengumuman.</p>

            <?php endif; ?>

        </div>

    </div>

</div>
</div>
<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">

    <div class="card-container">

        <!-- Card Pemasukan -->
        <div class="card">
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

        <!-- Card Pengeluaran -->
        <div class="card">
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

        <!-- Card Uang Kas -->
        <div class="card">
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
                    Download Gambar
                </a>

                <?php elseif($ext === 'pdf'): ?>

                <iframe src="<?= $fileUrl ?>" class="pengurus-frame"></iframe>

                <a href="<?= $fileUrl ?>" download class="pengurus-btn">
                    Download PDF
                </a>

                <?php elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx'])): ?>

                <iframe
                    src="https://docs.google.com/gview?url=<?= urlencode('http://yourdomain.com/uploads/'.$p['file']) ?>&embedded=true"
                    class="pengurus-frame">
                </iframe>

                <a href="<?= $fileUrl ?>" download class="pengurus-btn">
                    Download Dokumen
                </a>

                <?php else: ?>

                <a href="<?= $fileUrl ?>" download class="pengurus-btn">
                    Download File
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
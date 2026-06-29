<?php
$uangKas = $uangKas ?? 0;
$kandidat = $kandidat ?? [];
?>

<!-- ===== MAIN CONTENT ===== -->
<div class="home-content">

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

                <a href="dashboard_pengurus.php?page=home_pengurus" class="btn-reset-pengurus">

                    <i class="fa fa-rotate-right"></i>

                </a>

            </form>

        </div>
        <!-- ===== END FILTER ===== -->

        <!-- ===== CARD ===== -->
        <div class="finance-card-wrapper">

            <!-- PEMASUKAN -->
            <div class="finance-card pemasukan">

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

            <!-- PENGELUARAN -->
            <div class="finance-card pengeluaran">

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

            <!-- UANG KAS -->
            <div class="finance-card kas">

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
        <!-- ===== END CARD ===== -->

    </div>

    <?php if (!empty($agenda)): ?>

    <!-- =========================
         CALON KANDIDAT
    ========================= -->
    <div class="rekap-wrapper">

        <?php if (!empty($agendas)) : ?>
        <?php foreach ($agendas as $v) : ?>

        <?php if (!in_array($v['status'], ['selesai', 'menunggu', 'disetujui', 'ditolak'])) : ?>
        <!-- ================= INFO agenda ================= -->
        <div class="voting-info">

            <h3 class="voting-title">
                <?= htmlspecialchars($v['judul']) ?>
            </h3>

            <p>

                Periode:
                <?= htmlspecialchars($v['periode']) ?>
                <br>

                Masa Jabatan:
                <?= $v['masa_awal_jabatan'] ?>
                -
                <?= $v['masa_akhir_jabatan'] ?>
                <br>

                <?php if($v['status'] == 'dibuka' || $v['status'] == 'ditutup') : ?>

                Dibuka:
                <?= $v['tanggal_buka'] ?>

                |

                Ditutup:
                <?= $v['tanggal_tutup'] ?>
                <br>

                <?php endif; ?>

                Status:
                <b><?= strtoupper($v['status']) ?></b>

            </p>

        </div>

        <!-- ================= CALON KANDIDAT ================= -->
        <?php if ($v['status'] == 'dibuka') : ?>

        <div class="calon-wrapper">

            <?php if (!empty($v['calon'])) : ?>

            <?php
            // 🔥 GROUPING BERDASARKAN JABATAN
            $grouped = [];
            

            foreach ($v['calon'] as $c) {
                $grouped[$c['jabatan']][] = $c;
            }
            ?>

            <?php foreach ($grouped as $jabatanCalon => $listCalon) : ?>

            <h3 class="kategori-title">
                Calon <?= ucfirst($jabatanCalon) ?>
            </h3>

            <?php foreach ($listCalon as $c) : ?>

            <div class="calon-card">

                <!-- FOTO -->
                <div class="calon-foto">

                    <img src="../uploads/<?= htmlspecialchars($c['foto']) ?>" class="foto-kandidat">

                </div>

                <!-- CONTENT -->
                <div class="calon-content">

                    <div>

                        <strong>
                            <?= htmlspecialchars($c['nama_lengkap']) ?>
                        </strong>

                        <small>
                            No Kandidat:
                            <?= htmlspecialchars($c['no_kandidat']) ?>
                        </small>

                        <small>
                            Jabatan:
                            <?= htmlspecialchars($c['jabatan']) ?>
                        </small>

                        <div class="calon-visi">

                            <b>Visi:</b><br>

                            <?= nl2br(htmlspecialchars($c['visi'])) ?>

                        </div>

                        <div class="calon-misi">

                            <b>Misi:</b><br>

                            <?= nl2br(htmlspecialchars($c['misi'])) ?>

                        </div>

                    </div>

                    <!-- BUTTON VOTE -->
                    <div class="card-action">

                        <?php if ($v['status'] !== 'dibuka') : ?>

                        <span class="btn-vote disabled">
                            Voting Ditutup
                        </span>

                        <?php else : ?>

                        <a href="../src/controllers/AgendaController.php?action=vote&id_calon=<?= $c['id_calon'] ?>"
                            onclick="return confirm('Yakin memilih kandidat ini?')" class="btn-vote">

                            Vote

                        </a>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>
            <?php endforeach; ?>

            <?php else : ?>

            <p class="no-calon">
                Belum ada kandidat
            </p>

            <?php endif; ?>

        </div>

        <?php elseif ($v['status'] == 'draft') : ?>

        <p style="text-align:center;color:#888;margin:20px 0;">
            Agenda belum dibuka
        </p>

        <?php endif; ?>

        <?php endif; ?>
        <?php endforeach; ?>

        <?php else : ?>

        <p style="text-align:center;color:#888;">
            Tidak ada Agenda yang sedang dibuka
        </p>

        <?php endif; ?>

        <!-- =========================
         PEMENANG
========================= -->

        <?php
$pemenang = $agendaModel->getPemenangAgenda($v['id_agenda']);

/* batas tampil 7 hari setelah agenda selesai */
$tanggalSelesai = strtotime($v['tanggal_tutup']);
$batasTampil = strtotime('+7 days', $tanggalSelesai);
$sekarang = time();
?>

        <?php if($v['status'] == 'selesai' && $sekarang <= $batasTampil): ?>

        <?php foreach($pemenang as $row): ?>

        <div class="pemenang-wrapper">

            <h2 class="judul-voting-pemenang">
                Pemenang <?= htmlspecialchars($v['judul']) ?>
            </h2>

            <div class="pemenang-card-container">

                <?php foreach($pemenang as $row): ?>

                <div class="pemenang-card">

                    <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>">

                    <h3><?= htmlspecialchars($row['nama_lengkap']) ?></h3>

                    <p>Jabatan: <b><?= htmlspecialchars($row['jabatan']) ?></b></p>
                    <p>No Kandidat: <b><?= htmlspecialchars($row['no_kandidat']) ?></b></p>
                    <p>Total Suara: <b><?= $row['total_suara'] ?></b></p>

                </div>

                <?php endforeach; ?>

            </div>

        </div>

        <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <?php endif; ?>

    <!-- =========================
         SECTION PENGUMUMAN PENGURUS
    ========================= -->
    <div id="pengumuman" class="pengurus-pengumuman-section">

        <h2 class="pengurus-title">
            Pengumuman
        </h2>

        <div class="pengurus-pengumuman-list">

            <?php if(!empty($dataPengumuman)): ?>
            <?php foreach($dataPengumuman as $p): ?>

            <?php if($p['status'] == 'Disetujui'): ?>

            <div class="pengurus-pengumuman-card">

                <h3 class="pengurus-judul">
                    <?= htmlspecialchars($p['judul']) ?>
                </h3>

                <p class="pengurus-isi">
                    <?= nl2br(htmlspecialchars($p['isi'])) ?>
                </p>

                <?php $fileUrl = ''; ?>

                <?php if($p['file']): ?>

                <?php
                $ext = strtolower(pathinfo($p['file'], PATHINFO_EXTENSION));
                $fileUrl = "../uploads/".$p['file'];
                ?>

                <!-- GAMBAR -->
                <?php if(in_array($ext, ['jpg','jpeg','png','gif'])): ?>

                <img src="<?= $fileUrl ?>" class="pengurus-img">

                <!-- PDF -->
                <?php elseif($ext === 'pdf'): ?>

                <div class="file-card" onclick="window.open('<?= $fileUrl ?>', '_blank')">

                    <div class="file-info">

                        <i class="fa-solid fa-file"></i>

                        <span>
                            <?= basename($p['file']); ?>
                        </span>

                    </div>

                </div>

                <!-- OFFICE -->
                <?php elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx'])): ?>

                <div class="file-card" onclick="window.open('<?= $fileUrl ?>', '_blank')">

                    <div class="file-info">

                        <i class="fa-solid fa-file"></i>

                        <span>
                            <?= basename($p['file']); ?>
                        </span>

                    </div>

                </div>

                <?php endif; ?>
                <?php endif; ?>

                <!-- FOOTER -->
                <div class="pengurus-footer">

                    <?php if($p['file']): ?>

                    <a href="<?= $fileUrl ?>" download class="pengurus-btn">

                        Unduh

                    </a>

                    <?php endif; ?>

                    <small class="pengurus-info">

                        Oleh:
                        <?= $p['nama_lengkap'] ?>
                        <br>

                        <?= date('d M Y', strtotime($p['tanggal_dibuat'])) ?>

                    </small>

                </div>

            </div>

            <?php endif; ?>
            <?php endforeach; ?>

            <?php else: ?>

            <p class="pengurus-kosong">
                Tidak ada pengumuman.
            </p>

            <?php endif; ?>

        </div>

    </div>

</div>
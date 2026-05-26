<?php
$kandidat = $kandidat ?? [];

/* ===============================
   AMBIL VOTING (HANYA DIBUKA)
=============================== */
$votings = array_values(array_filter(
    $votingModel->getAllVoting(),
    fn($v) => $v['status'] === 'dibuka'
));

/* ===============================
   AMBIL VOTING TERBARU SELESAI
=============================== */
$selesaiVoting = array_values(array_filter(
    $votingModel->getAllVoting(),
    fn($v) => $v['status'] === 'selesai'
));

usort($selesaiVoting, fn($a, $b) =>
    strtotime($b['tanggal_tutup']) <=> strtotime($a['tanggal_tutup'])
);

$latestVoting = $selesaiVoting[0] ?? null;
?>

<?php if (!empty($votings)): ?>

<?php foreach ($votings as $voting): ?>

<!-- ================= INFO VOTING (HANYA DIBUKA) ================= -->
<div class="voting-info">
    <h2 class="voting-title">
        <?= htmlspecialchars($voting['judul'] ?? 'Voting tanpa judul') ?>
    </h2>

    <p>
        Masa Jabatan: <?= htmlspecialchars($voting['masa_awal_jabatan'] ?? '-') ?>
        s/d <?= htmlspecialchars($voting['masa_akhir_jabatan'] ?? '-') ?><br>

        Periode: <?= htmlspecialchars($voting['periode'] ?? '-') ?><br>

        Tanggal Buka: <?= htmlspecialchars($voting['tanggal_buka'] ?? '-') ?> |
        Tanggal Tutup: <?= htmlspecialchars($voting['tanggal_tutup'] ?? '-') ?><br>

        Status: <b><?= strtoupper($voting['status']) ?></b>
    </p>
</div>

<?php
$kategori = ['ketua', 'wakil', 'sekretaris', 'bendahara'];
?>

<div class="card-kategori-wrapper">

    <?php foreach ($kategori as $jabatan): ?>

    <?php
    $filtered = array_filter($kandidat, function($row) use ($jabatan, $voting) {
        return strtolower($row['jabatan']) === $jabatan
            && $row['id_voting'] == $voting['id_voting'];
    });
    ?>

    <?php if (!empty($filtered)): ?>

    <h3 class="kategori-title">Calon <?= ucfirst($jabatan) ?></h3>

    <div class="card-container">

        <?php foreach ($filtered as $row): ?>
        <div class="card-kandidat">

            <img src="../uploads/<?= htmlspecialchars($row['foto'] ?? '') ?>" class="foto-kandidat">

            <div class="card-info">

                <div>
                    <h1><?= htmlspecialchars($row['no_kandidat']) ?></h1>
                    <h2><?= htmlspecialchars($row['nama_lengkap']) ?></h2>
                    <h3>Calon <?= htmlspecialchars($row['jabatan']) ?></h3>

                    <div class="visi">
                        <strong>Visi:</strong>
                        <p><?= nl2br(htmlspecialchars($row['visi'])) ?></p>
                    </div>

                    <div class="misi">
                        <strong>Misi:</strong>
                        <p><?= nl2br(htmlspecialchars($row['misi'])) ?></p>
                    </div>
                </div>

                <div class="card-action">

                    <a href="../src/controllers/VotingController.php?action=vote&id_calon=<?= $row['id_calon'] ?>"
                        onclick="return confirm('Yakin memilih kandidat ini?')" class="btn-vote">
                        Vote
                    </a>

                </div>

            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <?php endif; ?>

    <?php endforeach; ?>

</div>

<?php endforeach; ?>

<?php endif; ?>


<!-- ================= PEMENANG (HANYA VOTING TERBARU SELESAI) ================= -->
<?php if ($latestVoting): ?>

<?php
$pemenang = $votingModel->getPemenangVoting($latestVoting['id_voting']);

$tanggalSelesai = strtotime($latestVoting['tanggal_tutup']);
$batasTampil = strtotime('+7 days', $tanggalSelesai);
$sekarang = time();
?>

<?php if ($sekarang <= $batasTampil && !empty($pemenang)): ?>

<h3 style="text-align:center;margin-top:90px;margin-bottom:20px;font-size:30px;" class="judul-voting-pemenang">
    Pemenang <?= htmlspecialchars($latestVoting['judul']) ?>
</h3>

<div class="card-container">

    <?php foreach ($pemenang as $row): ?>
    <div class="pemenang-card">

        <img src="../uploads/<?= htmlspecialchars($row['foto'] ?? '') ?>" width="120">

        <h3><?= htmlspecialchars($row['nama_lengkap'] ?? '-') ?></h3>
        <p>Jabatan: <b><?= htmlspecialchars($row['jabatan'] ?? '-') ?></b></p>
        <p>No Kandidat: <b><?= htmlspecialchars($row['no_kandidat'] ?? '-') ?></b></p>
        <p>Total Suara: <b><?= $row['total_suara'] ?? 0 ?></b></p>

    </div>
    <?php endforeach; ?>

</div>

<?php endif; ?>

<?php endif; ?>

<style>
/* ================= INFO VOTING ================= */
.voting-info {
    width: 100%;
    max-width: 1200px;
    margin: 80px auto 30px auto;
    background-color: #fff3e0;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    text-align: center;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.voting-title {
    font-size: 24px;
    font-weight: bold;
    color: #ff9644;
    margin-bottom: 12px;
}

.voting-info p {
    font-size: 14px;
    line-height: 1.6;
    color: #333;
    margin: 0;
}

/* ================= KATEGORI ================= */
.kategori-title {
    text-align: center;
    font-size: 28px;
    margin: 50px 0 20px;
    color: #ff9644;
    font-weight: bold;
}

.card-kategori-wrapper {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* ================= CARD CONTAINER ================= */
.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(600px, 1fr));
    gap: 20px;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

/* ================= CARD KANDIDAT HORIZONTAL ================= */
.card-kandidat {
    display: flex;
    flex-direction: row;
    height: 450px;
    /* tinggi card tetap */
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.card-kandidat:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* FOTO KANDIDAT DI KIRI */
.foto-kandidat {
    width: 500px;
    height: 100%;
    object-fit: cover;
    border-top-left-radius: 16px;
    border-bottom-left-radius: 16px;
}

/* INFO DI KANAN */
.card-info {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex: 1;
}

.card-info h1,
.card-info h2,
.card-info h3 {
    margin: 0;
}

/* VISI & MISI */
.visi,
.misi {
    margin: 5px 0;
    padding: 5px;
    background: #f9fafb;
    border-radius: 8px;
    font-size: 13px;
    line-height: 1.4;
    overflow-y: auto;
}

/* BUTTON VOTE */
.card-action {
    margin-top: 5px;
    text-align: right;
}

.btn-vote {
    padding: 6px 12px;
    background: #007bff;
    color: #fff;
    border-radius: 6px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}

.btn-vote:hover {
    background: #0056b3;
}

/* Jika tidak ada kandidat */
.no-voting {
    text-align: center;
    font-size: 16px;
    color: #888;
    padding: 50px 0;
    font-weight: bold;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 1300px) {
    .card-container {
        grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
    }
}

@media (max-width: 768px) {
    .card-kandidat {
        flex-direction: column;
        height: auto;
    }

    .foto-kandidat {
        width: 100%;
        height: 200px;
    }

    .card-info {
        padding: 15px;
    }
}

@media (max-width: 500px) {
    .card-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}
</style>
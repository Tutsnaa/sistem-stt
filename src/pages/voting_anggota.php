<div class="card-container">
    <?php 
    $adaVoting = false; // flag untuk mengecek apakah ada voting

    foreach($kandidat as $row): 
        if($row['status'] != 'dibuka') continue;
        $adaVoting = true; // ada kandidat yang tampil
    ?>
    <div class="card-kandidat">
        <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Kandidat" class="foto-kandidat">

        <h1 class="no-paslon"><?= htmlspecialchars($row['no_paslon']) ?></h1>

        <h2 class="calon">Calon <?= htmlspecialchars($row['jabatan']) ?></h2>

        <h3 class="nama-kandidat">
            <?= htmlspecialchars($row['nama_lengkap']) ?>
        </h3>

        <div class="visi">
            <strong>Visi:</strong>
            <p><?= htmlspecialchars($row['visi']) ?></p>
        </div>

        <div class="misi">
            <strong>Misi:</strong>
            <p><?= htmlspecialchars($row['misi']) ?></p>
        </div>

        <div class="card-action">
            <a href="../src/controllers/VotingController.php?action=vote&id_calon=<?= $row['id_calon'] ?>"
                onclick="return confirm('Yakin memilih kandidat ini?')" class="btn-vote">
                Vote
            </a>
        </div>

    </div>
    <?php endforeach; ?>

    <?php if(!$adaVoting): ?>
    <div class="no-voting">
        Belum ada voting
    </div>
    <?php endif; ?>
</div>

<style>
.no-voting {
    grid-column: 1 / -1;
    /* mengambil seluruh kolom grid */
    text-align: center;
    font-size: 18px;
    color: #888;
    padding: 50px 0;
    font-weight: bold;
    width: 100%;
    height: 570px;
}

.card-container {
    width: 100%;
    padding: 100px 30px 0px 30px;
    background: #f4f6f9;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 25px;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* CARD */
.card-kandidat {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.card-kandidat:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* FOTO */
.foto-kandidat {
    width: 100%;
    height: 150px;
    object-fit: contain;
    padding: 10px;
}

/* NO PASLON */
.no-paslon {
    text-align: center;
    font-size: 28px;
    font-weight: bold;
    color: black;
    margin-top: 10px;
}

/* JABATAN */
.calon {
    text-align: center;
    font-size: 16px;
    color: black;
    margin-top: 3px;
}

/* NAMA */
.nama-kandidat {
    text-align: center;
    font-size: 18px;
    font-weight: normal;
    color: #222;
    margin: 5px 15px 10px;
}

/* VISI MISI */
.visi,
.misi {
    margin: 12px 15px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 10px;
    font-size: 14px;
    line-height: 1.7;
    min-height: 120px;
    /* ini bikin lebih lega */
}

/* BORDER AKSEN */
/* .visi {
    border-left: 4px solid #007bff;
}

.misi {
    border-left: 4px solid #28a745;
} */

/* SCROLL JIKA PANJANG */
.visi p,
.misi p {
    max-height: 180px;
    /* lebih besar */
    overflow-y: auto;
}

/* ACTION */
.card-action {
    margin-top: auto;
    padding: 15px;
    text-align: center;
}

/* BUTTON */
.btn-vote {
    display: inline-block;
    width: 100%;
    padding: 10px;
    background: #007bff;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.btn-vote:hover {
    background: #0056b3;
}

/* RESPONSIVE */
@media (max-width: 500px) {
    .card-container {
        padding: 30px 15px;
    }

    .foto-kandidat {
        height: 180px;
    }
}
</style>
<?php
$kepengurusan = $kepengurusan ?? [];
?>

<div class="kepengurusan-wrapper">

    <h2 class="page-title">Data Kepengurusan</h2>

    <!-- FILTER PERIODE -->
    <div class="filter-wrapper">

        <form method="GET">

            <!-- supaya tetap di halaman kepengurusan -->
            <input type="hidden" name="page" value="kepengurusan">

            <select name="periode" class="filter-select" onchange="this.form.submit()">

                <option value="">-- Semua Periode --</option>

                <?php foreach($daftarPeriode as $p): ?>

                <option value="<?= $p['periode'] ?>"
                    <?= (isset($_GET['periode']) && $_GET['periode'] == $p['periode']) ? 'selected' : '' ?>>

                    <?= htmlspecialchars($p['periode']) ?>

                </option>

                <?php endforeach; ?>

            </select>

        </form>

    </div>
    <div class="table-wrapper-kepengurusan">
        <div class="kepengurusan-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Periode</th>
                        <th>Masa Awal Jabatan</th>
                        <th>Masa Akhir Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($kepengurusan as $k): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars($k['nama_lengkap']) ?>
                        </td>

                        <td class="text-center">
                            <?= ucfirst($k['jabatan']) ?>
                        </td>

                        <!-- PERIODE -->
                        <td class="text-center">
                            <?= htmlspecialchars($p['periode'] ?? '-') ?>
                        </td>

                        <!-- MASA AWAL -->
                        <td class="text-center">
                            <?= !empty($k['masa_awal_jabatan']) 
                ? date('Y-m-d', strtotime($k['masa_awal_jabatan'])) 
                : '-' ?>
                        </td>

                        <!-- MASA AKHIR -->
                        <td class="text-center">
                            <?= !empty($k['masa_akhir_jabatan']) 
                ? date('Y-m-d', strtotime($k['masa_akhir_jabatan'])) 
                : '-' ?>
                        </td>

                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
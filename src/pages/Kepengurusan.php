<div class="kepengurusan-wrapper">

    <h2 class="page-title">Data Kepengurusan</h2>

    <div class="kepengurusan-table">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Masa Awal Jabatan</th>
                    <th>Masa Akhir Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($kepengurusan as $k): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($k['nama_lengkap']) ?></td>
                    <td class="text-center"><?= ucfirst($k['jabatan']) ?></td>
                    <!-- 🔥 MASA AWAL -->
                    <td class="text-center">
                        <?= !empty($k['masa_awal_jabatan']) 
                                ? date('Y-m-d', strtotime($k['masa_awal_jabatan'])) 
                                : '-' ?>
                    </td>

                    <!-- 🔥 MASA AKHIR -->
                    <td class="text-center">
                        <?= !empty($k['masa_akhir_jabatan']) 
                                ? date('Y-m-d', strtotime($k['masa_akhir_jabatan'])) 
                                : '-' ?>
                    </td>
                    <td>
                        <a href="../src/controllers/KepengurusanController.php?action=hapus&id=<?= $k['id_kepengurusan'] ?>"
                            class="btn-delete" onclick="return confirm('Hapus data kepengurusan ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

</div>
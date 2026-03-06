<!-- ===== PROFIL PENGGUNA ===== -->
<div class="main-content profil-container">
    <h2>Profil Pengguna</h2>

    <div class="profil-card">
        <!-- Foto -->
        <div class="profil-foto">
            <img src="../asset/img/<?php echo $user['foto']; ?>" alt="Foto <?php echo $user['nama_lengkap']; ?>">
        </div>

        <!-- Data -->
        <div class="profil-data">
            <div class="data-row">
                <div class="data-label">Nama Lengkap</div>
                <div class="data-value"><?php echo $user['nama_lengkap']; ?></div>
            </div>
            <div class="data-row">
                <div class="data-label">Email</div>
                <div class="data-value"><?php echo $user['email']; ?></div>
            </div>
            <div class="data-row">
                <div class="data-label">No. HP</div>
                <div class="data-value"><?php echo $user['no_hp']; ?></div>
            </div>
            <div class="data-row">
                <div class="data-label">Alamat</div>
                <div class="data-value"><?php echo $user['alamat']; ?></div>
            </div>
            <div class="data-row">
                <div class="data-label">Jabatan</div>
                <div class="data-value"><?php echo $user['jabatan']; ?></div>
            </div>
            <div class="data-row">
                <div class="data-label">Nama Pengguna</div>
                <div class="data-value"><?php echo $user['nama_pengguna']; ?></div>
            </div>
            <div class="data-row">
                <div class="data-label">Kata Sandi</div>
                <div class="data-value">********</div>
            </div>
        </div>
    </div>

</div>
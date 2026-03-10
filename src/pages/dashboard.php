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

</div>
</div>
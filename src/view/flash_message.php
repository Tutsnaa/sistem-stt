<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['flash_message'])):
?>

<div id="flash-message" class="alert <?= $_SESSION['flash_type'] ?? 'success'; ?>">
    <?= $_SESSION['flash_message']; ?>
</div>

<script>
setTimeout(function() {
    const flash = document.getElementById('flash-message');
    if (flash) {
        flash.style.opacity = '0';
        setTimeout(() => flash.remove(), 500);
    }
}, 3000);
</script>

<?php
unset($_SESSION['flash_message']);
unset($_SESSION['flash_type']);
endif;
?>

<style>
.alert-success {
    position: fixed;
    top: 80px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #4CAF50;
    color: white;
    padding: 15px 30px;
    border-radius: 10px;
    font-size: 16px;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.alert {
    position: fixed;
    top: 80px;
    left: 50%;
    transform: translateX(-50%);
    padding: 15px 30px;
    border-radius: 10px;
    color: white;
    z-index: 9999;
}

/* sukses */
.alert.success {
    background-color: #4CAF50;
}

/* hapus */
.alert.danger {
    background-color: #e74c3c;
}

.modal-hapus {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.modal-box {
    background: white;
    padding: 25px;
    border-radius: 12px;
    width: 350px;
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-box h3 {
    margin-bottom: 10px;
    color: #333;
}

.modal-box p {
    color: #666;
    margin-bottom: 20px;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn-batal {
    background: #ccc;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-batal:hover {
    background: #999;
}

.btn-hapus-yes {
    background: #e74c3c;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
}

.btn-hapus-yes:hover {
    background: #c0392b;
}
</style>
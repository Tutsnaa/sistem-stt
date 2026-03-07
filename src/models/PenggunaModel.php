<?php
require_once __DIR__ . '/../../config/Database.php';

class PenggunaModel {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ===============================
    // UPDATE 
    // ===============================
  public function update($data){
    $query = "UPDATE pengguna SET
                nama_lengkap = :nama_lengkap,
                email = :email,
                no_hp = :no_hp,
                alamat = :alamat,
                nama_pengguna = :nama_pengguna";

    $params = [
        ':nama_lengkap' => $data['nama_lengkap'],
        ':email' => $data['email'],
        ':no_hp' => $data['no_hp'],
        ':alamat' => $data['alamat'],
        ':nama_pengguna' => $data['nama_pengguna'],
        ':id_pengguna' => $data['id_pengguna']
    ];

    // update password jika ada
    if(isset($data['kata_sandi']) && !empty($data['kata_sandi'])){
        $query .= ", kata_sandi = :kata_sandi";
        $params[':kata_sandi'] = password_hash($data['kata_sandi'], PASSWORD_DEFAULT);
    }

    // update foto jika ada
    if(isset($data['foto']) && !empty($data['foto'])){
        $query .= ", foto = :foto";
        $params[':foto'] = $data['foto'];
    }

    $query .= " WHERE id_pengguna = :id_pengguna";

    $stmt = $this->conn->prepare($query);
    return $stmt->execute($params);
}

    // ===============================
// Verifikasi Login
// ===============================
public function verifyLogin($nama_pengguna, $kata_sandi)
{

    $query = "SELECT * FROM pengguna
              WHERE nama_pengguna = :nama_pengguna
              LIMIT 1";

    $stmt = $this->conn->prepare($query);

    $stmt->execute([
        ':nama_pengguna' => $nama_pengguna
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        return false;
    }

    if($user['status'] != 'aktif'){
        return false;
    }

    // cek password hash
    if(password_verify($kata_sandi,$user['kata_sandi'])){
        return $user;
    }

    return false;
}
}
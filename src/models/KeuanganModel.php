<?php

require_once __DIR__ . '/../../config/database.php';

class KeuanganModel {

    private $conn;
    private $table = "keuangan";

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll(){
        $query = "SELECT * FROM " . $this->table . " ORDER BY tanggal_dibuat DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data){
        $query = "INSERT INTO ".$this->table."
        (id_pengguna, jenis, keterangan, jumlah, file_bukti)
        VALUES
        (:id_pengguna, :jenis, :keterangan, :jumlah, :file_bukti)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_pengguna", $data['id_pengguna']);
        $stmt->bindParam(":jenis", $data['jenis']);
        $stmt->bindParam(":keterangan", $data['keterangan']);
        $stmt->bindParam(":jumlah", $data['jumlah']);
        $stmt->bindParam(":file_bukti", $data['file_bukti']);

        return $stmt->execute();
    }

    public function getById($id){
        $query = "SELECT * FROM keuangan WHERE id_keuangan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data){
        $query = "UPDATE keuangan
                  SET jenis=:jenis,
                      keterangan=:keterangan,
                      jumlah=:jumlah,
                      file_bukti=:file_bukti
                  WHERE id_keuangan=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":jenis",$data['jenis']);
        $stmt->bindParam(":keterangan",$data['keterangan']);
        $stmt->bindParam(":jumlah",$data['jumlah']);
        $stmt->bindParam(":file_bukti",$data['file_bukti']);
        $stmt->bindParam(":id",$data['id_keuangan']);

        return $stmt->execute();
    }

    public function delete($id){
        $query = "DELETE FROM keuangan WHERE id_keuangan=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id",$id);
        return $stmt->execute();
    }

    // =========================
    // TOTAL PEMASUKAN (FILTER)
    // =========================
    public function getTotalPemasukan($bulan = null, $tahun = null){

        $query = "SELECT SUM(jumlah) as total 
                  FROM keuangan 
                  WHERE jenis='pemasukan'";

        if($bulan && $tahun){
            $query .= " AND MONTH(tanggal_dibuat) = :bulan 
                        AND YEAR(tanggal_dibuat) = :tahun";
        } elseif($tahun){
            $query .= " AND YEAR(tanggal_dibuat) = :tahun";
        }

        $stmt = $this->conn->prepare($query);

        if($bulan && $tahun){
            $stmt->bindParam(":bulan", $bulan);
            $stmt->bindParam(":tahun", $tahun);
        } elseif($tahun){
            $stmt->bindParam(":tahun", $tahun);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] ?? 0;
    }

    // =========================
    // TOTAL PENGELUARAN (FILTER)
    // =========================
    public function getTotalPengeluaran($bulan = null, $tahun = null){

        $query = "SELECT SUM(jumlah) as total 
                  FROM keuangan 
                  WHERE jenis='pengeluaran'";

        if($bulan && $tahun){
            $query .= " AND MONTH(tanggal_dibuat) = :bulan 
                        AND YEAR(tanggal_dibuat) = :tahun";
        } elseif($tahun){
            $query .= " AND YEAR(tanggal_dibuat) = :tahun";
        }

        $stmt = $this->conn->prepare($query);

        if($bulan && $tahun){
            $stmt->bindParam(":bulan", $bulan);
            $stmt->bindParam(":tahun", $tahun);
        } elseif($tahun){
            $stmt->bindParam(":tahun", $tahun);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'] ?? 0;
    }
}
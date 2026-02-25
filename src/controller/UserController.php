<?php
session_start();
require_once __DIR__ . '/../model/User.php';

/* ==============================
   CONTROLLER USER
   Menghandle request dari view dan memanggil model
   ============================== */
class UserController
{
    private $userModel;

    /* ==============================
       CONSTRUCTOR
       Membuat instance model User
       ============================== */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /* ==============================
       CREATE USER
       Menambah user baru
       ============================== */
    public function createUser($data)
    {
        $this->userModel->nama     = $data['nama'];
        $this->userModel->alamat   = $data['alamat'];
        $this->userModel->no_hp    = $data['no_hp'];
        $this->userModel->role     = $data['role'];
        $this->userModel->status   = $data['status'];
        $this->userModel->username = $data['username'];
        $this->userModel->password = password_hash($data['password'], PASSWORD_DEFAULT);

        return $this->userModel->create();
    }

    /* ==============================
       UPDATE USER
       Mengubah data user berdasarkan ID
       ============================== */
    public function updateUser($id, $data)
    {
        $this->userModel->nama     = $data['nama'];
        $this->userModel->alamat   = $data['alamat'];
        $this->userModel->no_hp    = $data['no_hp'];
        $this->userModel->role     = $data['role'];
        $this->userModel->status   = $data['status'];
        $this->userModel->username = $data['username'];
        $this->userModel->password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : '';

        return $this->userModel->update($id);
    }

    /* ==============================
       DELETE USER
       Menghapus user berdasarkan ID
       ============================== */
    public function deleteUser($id)
    {
        return $this->userModel->delete($id);
    }

    /* ==============================
       GET ALL USERS
       Mengambil semua data user
       ============================== */
    public function getAllUsers()
    {
        return $this->userModel->getAll();
    }

    /* ==============================
       GET USER BY ID
       Mengambil data user berdasarkan ID
       ============================== */
    public function getUser($id)
    {
        return $this->userModel->getById($id);
    }
}
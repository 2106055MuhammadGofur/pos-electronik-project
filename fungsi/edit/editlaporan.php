<?php
session_start();
include '../../config.php';

if(!empty($_GET['laporan'])){
    $admin_code = $_POST['admin_code'];
    
    // Periksa kode admin
    if ($admin_code !== 'admin') { // Ganti 'admin' dengan kode admin yang sebenarnya
        $_SESSION['error'] = 'Kode admin salah. Pengeditan dibatalkan.';
        echo "<script>window.location='../../index.php?page=laporan';</script>";
        exit();
    }

    $id_nota = $_POST['id'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $jumlah_lama = $_POST['jumlah_lama'];

    // Logging untuk debugging
    error_log("Editing laporan: ID_Nota=$id_nota, ID_Barang=$id_barang, Jumlah=$jumlah, Jumlah_Lama=$jumlah_lama");

    try {
        // Update tabel nota
        $sql_nota = "UPDATE nota SET jumlah=? WHERE id_nota=? AND id_barang=?";
        $row_nota = $config->prepare($sql_nota);
        $row_nota->execute(array($jumlah, $id_nota, $id_barang));

        // Update tabel barang
        $sql_barang = "UPDATE barang SET stok=stok+? WHERE id_barang=?";
        $row_barang = $config->prepare($sql_barang);
        $row_barang->execute(array(($jumlah_lama - $jumlah), $id_barang));

        $_SESSION['success'] = 'Data Berhasil Diubah';
        error_log("Laporan berhasil diubah");
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
        error_log("Error saat mengubah laporan: " . $e->getMessage());
    }

    echo "<script>window.location='../../index.php?page=laporan';</script>";
}
?>
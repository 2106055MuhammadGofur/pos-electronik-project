<?php

session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';
    require_once '../../fungsi/view/view.php';
    $lihat = new view($config);

    if (!empty(htmlentities($_GET['kategori']))) {
        $id= htmlentities($_GET['id']);
        $data[] = $id;
        $sql = 'DELETE FROM kategori WHERE id_kategori=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=kategori&&remove=hapus-data"</script>';
    }

    if (!empty(htmlentities($_GET['barang']))) {
        $id= htmlentities($_GET['id']);
        $data[] = $id;
        $sql = 'DELETE FROM barang WHERE id_barang=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=barang&&remove=hapus-data"</script>';
    }

    if (!empty(htmlentities($_GET['jual']))) {
        $id = $_GET['id'];
        $brg = $_GET['brg'];
        $jml = $_GET['jml'];

        $sql = "DELETE FROM penjualan WHERE id_penjualan = ?";
        $row = $config->prepare($sql);
        $row->execute(array($id));

        // Hapus baris ini untuk mencegah penambahan stok saat item dihapus dari keranjang
        // $sql2 = "UPDATE barang SET stok = stok + ? WHERE id_barang = ?";
        // $row2 = $config->prepare($sql2);
        // $row2->execute(array($jml, $brg));

        echo '<script>window.location="../../index.php?page=jual&remove=success"</script>';
    }

    if (!empty(htmlentities($_GET['penjualan']))) {
        $sql = 'DELETE FROM penjualan';
        $row = $config -> prepare($sql);
        $row -> execute();
        echo '<script>window.location="../../index.php?page=jual"</script>';
    }
    
    if (!empty(htmlentities($_GET['laporan']))) {
        if($_GET['laporan'] == 'jual'){
            $id = $_GET['id'];
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            var_dump($id);
            $hasil = $lihat->hapus_laporan_jual($id);
            var_dump($hasil);
            if($hasil){
                $_SESSION['success'] = "Data berhasil dihapus dan stok dikembalikan.";
                header("location:../../index.php?page=laporan");
                exit();
            } else {
                $_SESSION['error'] = "Gagal menghapus data.";
                header("location:../../index.php?page=laporan");
                exit();
            }
        }
    }
}
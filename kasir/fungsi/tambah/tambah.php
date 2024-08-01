<?php

session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';
    if (!$config) {
        die("Koneksi database gagal");
    }

    if (!empty($_GET['kategori'])) {
        $nama= htmlentities(htmlentities($_POST['kategori']));
        $tgl= date("j F Y, G:i");
        $data[] = $nama;
        $data[] = $tgl;
        $sql = 'INSERT INTO kategori (nama_kategori,tgl_input) VALUES(?,?)';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=kategori&&success=tambah-data"</script>';
    }

    if (!empty($_GET['barang'])) {
        try {
            // Validasi input
            if (empty($_POST['id']) || empty($_POST['kategori']) || empty($_POST['nama'])) {
                echo '<script>alert("Semua field harus diisi!"); window.history.back();</script>';
                exit;
            }

            $id = htmlentities($_POST['id']);
            $kategori = htmlentities($_POST['kategori']);
            $nama = htmlentities($_POST['nama']);
            $merk = htmlentities($_POST['merk']);
            $type = htmlentities($_POST['type']);
            $spesifikasi = htmlentities($_POST['spesifikasi']);
            $warna = htmlentities($_POST['warna']);
            $beli = htmlentities($_POST['beli']);
            $jual = htmlentities($_POST['jual']);
            $satuan = htmlentities($_POST['satuan']);
            $stok_awal = htmlentities($_POST['stok_awal']);
            $stok_akhir = htmlentities($_POST['stok_akhir']);
            $stok = htmlentities($_POST['stok']);
            $tgl = htmlentities($_POST['tgl']);

            $data = [$id, $kategori, $nama, $merk, $type, $spesifikasi, $warna, $beli, $jual, $satuan, $stok_awal, $stok_akhir, $stok, $tgl];
            $sql = 'INSERT INTO barang (id_barang, id_kategori, nama_barang, merk, type, spesifikasi, warna, harga_beli, harga_jual, satuan_barang, stok_awal, stok_akhir, stok, tgl_input) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $row = $config->prepare($sql);
            $row->execute($data);
            echo '<script>window.location="../../index.php?page=barang&success=tambah-data"</script>';
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            // Atau log error ke file
            error_log("Error in tambah.php: " . $e->getMessage());
        }
    }
    
    if (!empty($_GET['jual'])) {
        $id = $_GET['id'];

        // get tabel barang id_barang
        $sql = 'SELECT * FROM barang WHERE id_barang = ?';
        $row = $config->prepare($sql);
        $row->execute(array($id));
        $hsl = $row->fetch();

        if ($hsl['stok'] > 0) {
            $kasir =  $_GET['id_kasir'];
            $jumlah = 1;
            $total = $hsl['harga_jual'];
            $tgl = date("j F Y, G:i");

            $data1[] = $id;
            $data1[] = $kasir;
            $data1[] = $jumlah;
            $data1[] = $total;
            $data1[] = $tgl;

            $sql1 = 'INSERT INTO penjualan (id_barang,id_member,jumlah,total,tanggal_input) VALUES (?,?,?,?,?)';
            $row1 = $config -> prepare($sql1);
            $row1 -> execute($data1);

            echo '<script>window.location="../../index.php?page=jual&success=tambah-data"</script>';
        } else {
            echo '<script>alert("Stok Barang Anda Telah Habis !");
                    window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }

    if (!empty($_GET['penjualan'])) {
        if ($_GET['penjualan'] == 'tambah') {
            try {
                $id_barang = htmlentities($_POST['nama_barang']);
                $jumlah = htmlentities($_POST['jumlah']);
                $id_member = $_SESSION['admin']['id_member'];

                // Ambil harga barang
                $sql = "SELECT harga_jual FROM barang WHERE id_barang = ?";
                $row = $config->prepare($sql);
                $row->execute(array($id_barang));
                $harga = $row->fetch();

                $total = $jumlah * $harga['harga_jual'];

                // Tambahkan ke tabel penjualan
                $sql = "INSERT INTO penjualan (id_barang, id_member, jumlah, total, tanggal_input) VALUES (?, ?, ?, ?, NOW())";
                $row = $config->prepare($sql);
                $row->execute(array($id_barang, $id_member, $jumlah, $total));

                if($row->rowCount() > 0){
                    $_SESSION['success_message'] = "Penjualan berhasil ditambahkan!";
                } else {
                    $_SESSION['error_message'] = "Gagal menambahkan penjualan.";
                }

                header("Location: ../../index.php?page=jual");
                exit;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                // Atau log error ke file
                error_log("Error in tambah.php: " . $e->getMessage());
            }
        }
    }
    
    if (!empty($_GET['penjualan'])) {
        require '../../config.php';
        
        if (!empty($_POST['nama_barang']) && !empty($_POST['jumlah'])) {
            $id = $_POST['nama_barang'];
            $jumlah = $_POST['jumlah'];
            $kasir = $_SESSION['admin']['id_member'];
            $tgl = date("j F Y, G:i");
            
            // Check if the item already exists in the cart
            $sql_check = "SELECT * FROM penjualan WHERE id_barang = ? AND id_member = ?";
            $row_check = $config->prepare($sql_check);
            $row_check->execute(array($id, $kasir));
            $existing_item = $row_check->fetch();
            
            if ($existing_item) {
                // Item already exists, return an error
                $_SESSION['error_message'] = "Barang sudah ada di keranjang. Silakan update jumlahnya di tabel keranjang.";
            } else {
                // Item doesn't exist, insert new record
                $sql_tambah = "INSERT INTO penjualan (id_barang, id_member, jumlah, tanggal_input) VALUES (?, ?, ?, ?)";
                $row_tambah = $config->prepare($sql_tambah);
                $row_tambah->execute(array($id, $kasir, $jumlah, $tgl));
                $_SESSION['success_message'] = "Barang berhasil ditambahkan ke keranjang.";
            }
            
            echo '<script>window.location="../../index.php?page=jual"</script>';
        }
    }
}
<?php
require_once '../../config.php';

if(isset($_POST['id_barang'])){
    $id_barang = $_POST['id_barang'];
    $sql = $config->prepare("SELECT harga_jual FROM barang WHERE id_barang = ?");
    $sql->execute([$id_barang]);
    $result = $sql->fetch();
    
    if($result){
        echo $result['harga_jual'];
    } else {
        echo '0';
    }
} else {
    echo '0';
}
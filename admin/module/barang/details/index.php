<?php 
	$id = $_GET['barang'];
	$hasil = $lihat -> barang_edit($id);
?>
<a href="index.php?page=barang" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Kembali </a>
<h4>Details Barang</h4>
<?php if(isset($_GET['success-stok'])){?>
<div class="alert alert-success">
	<p>Tambah Stok Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success">
	<p>Tambah Data Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger">
	<p>Hapus Data Berhasil !</p>
</div>
<?php }?>
<div class="card card-body">
	<div class="table-responsive">
		<table class="table table-striped">
			<tr>
				<td>ID Barang</td>
				<td><?php echo $hasil['id_barang'];?></td>
			</tr>
			<tr>
				<td>Kategori</td>
				<td><?php echo $hasil['nama_kategori'];?></td>
			</tr>
			<tr>
				<td>Nama Barang</td>
				<td><?php echo $hasil['nama_barang'];?></td>
			</tr>
			<tr>
				<td>Merk Barang</td>
				<td><?php echo $hasil['merk'];?></td>
			</tr>
			<tr>
				<td>Type Barang</td>
				<td><?php echo $hasil['type'];?></td>
			</tr>
			<tr>
				<td>Spesifikasi Barang</td>
				<td><?php echo $hasil['spesifikasi'];?></td>
			</tr>
			<tr>
				<td>Warna Barang</td>
				<td><?php echo $hasil['warna'];?></td>
			</tr>
			<tr>
				<td>Harga Beli</td>
				<td><?php echo $hasil['harga_beli'];?></td>
			</tr>
			<tr>
				<td>Harga Jual</td>
				<td><?php echo $hasil['harga_jual'];?></td>
			</tr>
			<tr>
				<td>Kembalian</td>
				<td>
					<?php
					// Asumsikan ada variabel $jumlah_bayar yang menyimpan jumlah yang dibayarkan
					$jumlah_bayar = isset($_GET['jumlah_bayar']) ? floatval($_GET['jumlah_bayar']) : 0;
					$harga_jual = floatval($hasil['harga_jual']);
					$kembalian = $jumlah_bayar - $harga_jual;
					echo ($kembalian >= 0) ? number_format($kembalian, 2) : "Pembayaran kurang";
					?>
				</td>
			</tr>
			<tr>
				<td>Satuan Barang</td>
				<td><?php echo $hasil['satuan_barang'];?></td>
			</tr>
			<tr>
				<td>Stok Awal</td>
				<td><?php echo $hasil['stok_awal'];?></td>
			</tr><tr>
				<td>Stok Akhir</td>
				<td><?php echo $hasil['stok_akhir'];?></td>
			</tr>
			<tr>
				<td>Stok</td>
				<td><?php echo $hasil['stok'];?></td>
			</tr>
			<tr>
				<td>Tanggal Input</td>
				<td><?php echo $hasil['tgl_input'];?></td>
			</tr>
			<tr>
				<td>Tanggal Update</td>
				<td><?php echo $hasil['tgl_update'];?></td>
			</tr>
		</table>
	</div>
</div>
 <?php 
	$bulan_tes =array(
		'01'=>"Januari",
		'02'=>"Februari",
		'03'=>"Maret",
		'04'=>"April",
		'05'=>"Mei",
		'06'=>"Juni",
		'07'=>"Juli",
		'08'=>"Agustus",
		'09'=>"September",
		'10'=>"Oktober",
		'11'=>"November",
		'12'=>"Desember"
	);
?>
<div class="row">
	<div class="col-md-12">
		<h4>
			<!--<a  style="padding-left:2pc;" href="fungsi/hapus/hapus.php?laporan=jual" onclick="javascript:return confirm('Data Laporan akan di Hapus ?');">
						<button class="btn btn-danger">RESET</button>
					</a>-->
			<?php if(!empty($_GET['cari'])){ ?>
			Data Laporan Penjualan <?= $bulan_tes[$_POST['bln']];?> <?= $_POST['thn'];?>
			<?php }elseif(!empty($_GET['hari'])){?>
			Data Laporan Penjualan <?= $_POST['hari'];?>
			<?php }else{?>
			Data Laporan Penjualan <?= $bulan_tes[date('m')];?> <?= date('Y');?>
			<?php }?>
		</h4>
		<br />
		<div class="card">
			<div class="card-header">
				<h5 class="card-title mt-2">Cari Laporan Per Bulan</h5>
			</div>
			<div class="card-body p-0">
				<form method="post" action="index.php?page=laporan&cari=ok">
					<table class="table table-striped">
						<tr>
							<th>
								Pilih Bulan
							</th>
							<th>
								Pilih Tahun
							</th>
							<th>
								Aksi
							</th>
						</tr>
						<tr>
							<td>
								<select name="bln" class="form-control">
									<option selected="selected">Bulan</option>
									<?php
								$bulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
								$jlh_bln=count($bulan);
								$bln1 = array('01','02','03','04','05','06','07','08','09','10','11','12');
								$no=1;
								for($c=0; $c<$jlh_bln; $c+=1){
									echo"<option value='$bln1[$c]'> $bulan[$c] </option>";
								$no++;}
							?>
								</select>
							</td>
							<td>
							<?php
								$now=date('Y');
								echo "<select name='thn' class='form-control'>";
								echo '
								<option selected="selected">Tahun</option>';
								for ($a=2017;$a<=$now;$a++)
								{
									echo "<option value='$a'>$a</option>";
								}
								echo "</select>";
							?>
							</td>
							<td>
								<input type="hidden" name="periode" value="ya">
								<button class="btn btn-primary">
									<i class="fa fa-search"></i> Cari
								</button>
								<a href="index.php?page=laporan" class="btn btn-success">
									<i class="fa fa-refresh"></i> Refresh</a>

								<?php if(!empty($_GET['cari'])){?>
								<a href="excel.php?cari=yes&bln=<?=$_POST['bln'];?>&thn=<?=$_POST['thn'];?>"
									class="btn btn-info"><i class="fa fa-download"></i>
									Excel</a>
								<?php }else{?>
								<a href="excel.php" class="btn btn-info"><i class="fa fa-download"></i>
									Excel</a>
								<?php }?>
							</td>
						</tr>
					</table>
				</form>
				<form method="post" action="index.php?page=laporan&hari=cek">
					<table class="table table-striped">
						<tr>
							<th>
								Pilih Hari
							</th>
							<th>
								Aksi
							</th>
						</tr>
						<tr>
							<td>
								<input type="date" value="<?= date('Y-m-d');?>" class="form-control" name="hari">
							</td>
							<td>
								<input type="hidden" name="periode" value="ya">
								<button class="btn btn-primary">
									<i class="fa fa-search"></i> Cari
								</button>
								<a href="index.php?page=laporan" class="btn btn-success">
									<i class="fa fa-refresh"></i> Refresh</a>

								<?php if(!empty($_GET['hari'])){?>
								<a href="excel.php?hari=cek&tgl=<?= $_POST['hari'];?>" class="btn btn-info"><i
										class="fa fa-download"></i>
									Excel</a>
								<?php }else{?>
								<a href="excel.php" class="btn btn-info"><i class="fa fa-download"></i>
									Excel</a>
								<?php }?>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
         <br />
         <br />
         <!-- view barang -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered w-100 table-sm" id="example1">
						<thead>
							<tr style="background:#DFF0D8;color:#333;">
								<th> No</th>
								<th> ID Barang</th>
								<th> Nama Barang</th>
								<th style="width:10%;"> Jumlah</th>
								<th style="width:10%;"> Modal</th>
								<th style="width:10%;"> Total</th>
								<th> Kasir</th>
								<th> Tanggal Input</th>
								<th> Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$no=1; 
								if(!empty($_GET['cari'])){
									$periode = $_POST['bln'].'-'.$_POST['thn'];
									$no=1; 
									$jumlah = 0;
									$bayar = 0;
									$hasil = $lihat->periode_jual($periode);
								}elseif(!empty($_GET['hari'])){
									$hari = $_POST['hari'];
									$no=1; 
									$jumlah = 0;
									$bayar = 0;
									$hasil = $lihat->hari_jual($hari);
								}else{
									$hasil = $lihat->jual();
								}
							?>
							<?php 
								$bayar = 0;
								$jumlah = 0;
								$modal = 0;
								foreach($hasil as $isi){ 
									$bayar += $isi['total'];
									$modal += $isi['harga_beli']* $isi['jumlah'];
									$jumlah += $isi['jumlah'];
							?>
							<tr>
								<td><?php echo $no;?></td>
								<td><?php echo $isi['id_barang'];?></td>
								<td><?php echo $isi['nama_barang'];?></td>
								<td><?php echo $isi['jumlah'];?> </td>
								<td>Rp.<?php echo number_format($isi['harga_beli']* $isi['jumlah']);?>,-</td>
								<td>Rp.<?php echo number_format($isi['total']);?>,-</td>
								<td><?php echo $isi['nm_member'];?></td>
								<td><?php echo $isi['tanggal_input'];?></td>
								<td>
									<button onclick="openEditModal('<?php echo $isi['id_nota'];?>', '<?php echo $isi['id_barang'];?>', '<?php echo $isi['jumlah'];?>', '<?php echo $isi['jumlah'];?>')" class="btn btn-warning btn-sm">Edit</button>
									<button onclick="openDeleteConfirmModal('<?php echo $isi['id_nota'];?>')" class="btn btn-danger btn-sm">Hapus</button>
								</td>
							</tr>
							<?php $no++; }?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="3">Total Terjual</td>
								<th><?php echo $jumlah;?></td>
								<th>Rp.<?php echo number_format($modal);?>,-</th>
								<th>Rp.<?php echo number_format($bayar);?>,-</th>
								<th style="background:#0bb365;color:#fff;">Keuntungan</th>
								<th style="background:#0bb365;color:#fff;">
									Rp.<?php echo number_format($bayar-$modal);?>,-</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
     </div>
 </div>

 <?php
 if(isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
if(isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}
?>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Laporan Penjualan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="post" action="fungsi/edit/editlaporan.php?laporan=jual">
        <div class="modal-body">
          <input type="hidden" id="editId" name="id">
          <input type="hidden" id="editIdBarang" name="id_barang">
          <input type="hidden" id="editJumlahLama" name="jumlah_lama">
          <input type="hidden" name="admin_code" value="admin">
          <div class="form-group">
            <label for="editJumlah">Jumlah</label>
            <input type="number" class="form-control" id="editJumlah" name="jumlah" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="close" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Confirmation -->
<div class="modal fade" id="editConfirmModal" tabindex="-1" role="dialog" aria-labelledby="editConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editConfirmModalLabel">Konfirmasi Kode Admin</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="editAdminCode">Masukkan Kode Admin</label>
          <input type="password" class="form-control" id="editAdminCode" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="confirmEditButton">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete Confirmation -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Masukkan kode admin untuk menghapus:</p>
        <input type="password" id="deleteAdminCode" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Lanjutkan</button>
      </div>
    </div>
  </div>
</div>

<script>
function openEditModal(idNota, idBarang, jumlah, jumlahLama) {
    $('#editConfirmModal').modal('show');
    document.getElementById('confirmEditButton').onclick = function() {
        var adminCode = document.getElementById('editAdminCode').value;
        if (adminCode === 'admin') { // Ganti 'admin' dengan kode admin yang benar
            $('#editConfirmModal').modal('hide');
            $('#editModal').modal('show');
            document.getElementById('editId').value = idNota;
            document.getElementById('editIdBarang').value = idBarang;
            document.getElementById('editJumlah').value = jumlah;
            document.getElementById('editJumlahLama').value = jumlahLama;
        } else {
            $('#editConfirmModal').modal('hide');
            $('#alertModal').modal('show');
            document.getElementById('alertMessage').innerText = 'Kode admin salah. Pengeditan dibatalkan.';
        }
    };
}

function openDeleteConfirmModal(idNota) {
    $('#deleteConfirmModal').modal('show');
    document.getElementById('confirmDeleteButton').onclick = function() {
        var adminCode = document.getElementById('deleteAdminCode').value;
        if (adminCode === 'admin') { // Ganti 'admin' dengan kode admin yang benar
            $('#deleteConfirmModal').modal('hide');
            $('#deleteModal').modal('show');
            document.getElementById('deleteId').value = idNota;
        } else {
            $('#deleteConfirmModal').modal('hide');
            $('#alertModal').modal('show');
            document.getElementById('alertMessage').innerText = 'Kode admin salah. Penghapusan dibatalkan.';
        }
    };
}
</script>
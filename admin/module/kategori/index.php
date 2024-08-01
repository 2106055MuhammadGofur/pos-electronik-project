<h4>Kategori</h4>
<br />
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success">
    <p>Tambah Data Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['success-edit'])){?>
<div class="alert alert-success">
    <p>Update Data Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger">
    <p>Hapus Data Berhasil !</p>
</div>
<?php }?>
<?php 
	if(!empty($_GET['uid'])){
	$sql = "SELECT * FROM kategori WHERE id_kategori = ?";
	$row = $config->prepare($sql);
	$row->execute(array($_GET['uid']));
	$edit = $row->fetch();
?>

<?php }else{?>

<?php }?>
<!-- Tombol untuk membuka modal Insert -->
<button class="btn btn-success mb-3 mt-3" data-toggle="modal" data-target="#insertModal">Tambah Kategori</button>
<br />
<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm" id="example1">
            <thead>
                <tr style="background:#DFF0D8;color:#333;">
                    <th>No.</th>
                    <th>Kategori</th>
                    <th>Tanggal Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				$hasil = $lihat -> kategori();
				$no=1;
				foreach($hasil as $isi){
			?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo $isi['nama_kategori'];?></td>
                    <td><?php echo $isi['tgl_input'];?></td>
                    <td>
                        <!-- Ganti tombol Edit dengan pemicu modal -->
                        <button class="btn btn-warning" data-toggle="modal" data-target="#editModal<?= $isi['id_kategori']; ?>">Edit</button>
                        <!-- Modal untuk Edit -->
                        <div class="modal fade" id="editModal<?= $isi['id_kategori']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Kategori</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="fungsi/edit/edit.php?kategori=edit">
                                            <input type="text" class="form-control" value="<?= $isi['nama_kategori'];?>" required name="kategori" placeholder="Masukan Kategori Barang Baru">
                                            <input type="hidden" name="id" value="<?= $isi['id_kategori'];?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Ubah Data</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="fungsi/hapus/hapus.php?kategori=hapus&id=<?php echo $isi['id_kategori'];?>"
                            onclick="javascript:return confirm('Hapus Data Kategori ?');"><button
                                class="btn btn-danger">Hapus</button></a>
                    </td>
                </tr>
                <?php $no++; }?>
            </tbody>
        </table>
    </div>
</div>
<!-- Tambahkan modal untuk Insert di bawah modal Edit -->
<div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insertModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="insertForm" method="POST" action="fungsi/tambah/tambah.php?kategori=tambah">
                    <input type="text" class="form-control" id="insertKategori" name="kategori" required placeholder="Masukan Kategori Barang Baru">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" form="insertForm" class="btn btn-primary">Insert Data</button>
            </div>
        </div>
    </div>
</div>
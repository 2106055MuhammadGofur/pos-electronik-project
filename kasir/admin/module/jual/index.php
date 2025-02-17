<!--sidebar end-->

<!-- ****************************************************
	  MAIN CONTENT
	  ***************************************************** -->
<!--main content start-->
<?php
$id = $_SESSION['admin']['id_member'];
$hasil = $lihat->member_edit($id);
?>
<h4>Keranjang Penjualan</h4>
<br>
<?php if (isset($_GET['success'])) { ?>
	<div class="alert alert-success">
		<p>Edit Data Berhasil !</p>
	</div>
<?php } ?>
<?php if (isset($_GET['remove'])) { ?>
	<div class="alert alert-danger">
		<p>Hapus Data Berhasil !</p>
	</div>
<?php } ?>
<div class="row">
	<div class="col-sm-4">
		<div class="card card-primary mb-3">
			<div class="card-header bg-primary text-white">
				<h5><i class="fa fa-search"></i> Cari Barang</h5>
			</div>
			<div class="card-body">
				<input type="text" id="cari" class="form-control" name="cari"
					placeholder="Masukan : Kode / Nama Barang  [ENTER]">
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="card card-primary mb-3">
			<div class="card-header bg-primary text-white">
				<h5><i class="fa fa-list"></i> Hasil Pencarian</h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<div id="hasil_cari"></div>
					<div id="tunggu"></div>
				</div>
			</div>
		</div>
	</div>


	<div class="col-sm-12">
		<div class="card card-primary">
			<div class="card-header bg-primary text-white">
				<h5><i class="fa fa-shopping-cart"></i> KASIR
					<button class="btn btn-danger float-right" data-toggle="modal" data-target="#resetModal">
						<b>RESET KERANJANG</b>
					</button>
				</h5>
			</div>
			<div class="card-body">
				<!-- Tambahkan form baru untuk menambah penjualan -->
				<form id="penjualanForm" method="POST" action="fungsi/tambah/tambah.php?penjualan=tambah">
					<div class="form-row">
						<div class="form-group col-md-3">
							<label for="nama_barang">Nama Barang</label>
							<select class="form-control" id="nama_barang" name="nama_barang" required>
								<option value="">Pilih Barang</option>
								<?php
								$sql_barang = "SELECT id_barang, nama_barang, stok FROM barang ORDER BY nama_barang ASC";
								$row_barang = $config->prepare($sql_barang);
								$row_barang->execute();
								while ($barang = $row_barang->fetch(PDO::FETCH_ASSOC)) {
									echo "<option value='" . $barang['id_barang'] . "' data-stok='" . $barang['stok'] . "'>" . $barang['nama_barang'] . "</option>";
								}
								?>
							</select>
						</div>
						<div class="form-group col-md-3">
							<label for="stok_akhir">Stok Akhir</label>
							<input type="text" class="form-control" id="stok_akhir" name="stok_akhir" readonly>
						</div>
						<div class="form-group col-md-3">
							<label for="jumlah">Jumlah</label>
							<input type="number" class="form-control" id="jumlah" name="jumlah" required>
						</div>
						<div class="form-group col-md-3">
							<label>&nbsp;</label>
							<button type="submit" class="btn btn-success btn-block">Tambah Penjualan</button>
						</div>
					</div>
				</form>

				<!-- Modal -->
				<div class="modal fade" id="stokModal" tabindex="-1" role="dialog" aria-labelledby="stokModalLabel"
					aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="stokModalLabel">Peringatan</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								Jumlah melebihi stok akhir!
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
							</div>
						</div>
					</div>
				</div>

				<script>
					document.getElementById('nama_barang').addEventListener('change', function () {
						var stok = this.options[this.selectedIndex].getAttribute('data-stok');
						document.getElementById('stok_akhir').value = stok;
					});

					document.getElementById('penjualanForm').addEventListener('submit', function (event) {
						event.preventDefault(); // Mencegah form dikirim secara default
						var stokAkhir = parseInt(document.getElementById('stok_akhir').value);
						var jumlah = parseInt(document.getElementById('jumlah').value);

						if (jumlah > stokAkhir) {
							$('#stokModal').modal('show'); // Menampilkan modal Bootstrap
						} else {
							// Kirim form menggunakan AJAX
							var formData = new FormData(this);
							fetch(this.action, {
								method: 'POST',
								body: formData
							})
							.then(response => response.text())
							.then(data => {
								// Refresh halaman atau update tampilan keranjang
								location.reload();
							})
							.catch(error => {
								console.error('Error:', error);
							});
						}
					});
				</script>
				<!-- Akhir form baru -->
				<div class="card-body">
					<div id="keranjang" class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<td><b>Tanggal</b></td>
								<td><input type="text" readonly="readonly" class="form-control"
										value="<?php echo date("j F Y, G:i"); ?>" name="tgl"></td>
							</tr>
						</table>
						<table class="table table-bordered w-100" id="example1">
							<thead>
								<tr>
									<td> No</td>
									<td> Nama Barang</td>
									<td style="width:10%;"> Jumlah</td>
									<td style="width:10%;"> Stok Akhir</td>
									<td style="width:20%;"> Total</td>
									<td> Kasir</td>
									<td> Aksi</td>
								</tr>
							</thead>
							<tbody>
								<?php $total_bayar = 0;
								$no = 1;
								$hasil_penjualan = $lihat->penjualan(); ?>
								<?php foreach ($hasil_penjualan as $isi) { ?>
									<tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $isi['nama_barang']; ?></td>
										<td>
											<!-- aksi ke table penjualan -->
											<form method="POST" action="fungsi/edit/edit.php?jual=jual">
												<input type="number" name="jumlah" value="<?php echo $isi['jumlah']; ?>"
													class="form-control">
												<input type="hidden" name="id" value="<?php echo $isi['id_penjualan']; ?>"
													class="form-control">
												<input type="hidden" name="id_barang"
													value="<?php echo $isi['id_barang']; ?>" class="form-control">
										</td>
										<td style="width:10%;">
											<?php
											// Panggil stok akhir dari database
											$sql_stok = "SELECT stok FROM barang WHERE id_barang = ?";
											$row_stok = $config->prepare($sql_stok);
											$row_stok->execute(array($isi['id_barang']));
											$stok_akhir = $row_stok->fetch(PDO::FETCH_ASSOC)['stok'];
											echo $stok_akhir;
											?>
										</td>
										<td>Rp.<?php echo number_format($isi['total']); ?>,-</td>
										<td><?php echo $isi['nm_member']; ?></td>
										<td>
											<button type="submit" class="btn btn-warning">Update Jumlah</button>
											</form>
											<!-- aksi ke table penjualan -->
											<a href="fungsi/hapus/hapus.php?jual=jual&id=<?php echo $isi['id_penjualan']; ?>&brg=<?php echo $isi['id_barang']; ?>
											&jml=<?php echo $isi['jumlah']; ?>" class="btn btn-danger"><i class="fa fa-times"></i>
											</a>
										</td>
									</tr>
									<?php $no++;
									$total_bayar += $isi['total'];
								} ?>
							</tbody>
						</table>
						<br />
						<?php $hasil = $lihat->jumlah(); ?>
						<div id="kasirnya">
							<table class="table table-stripped">
								<?php
								// proses bayar dan ke nota
								if (!empty($_GET['nota'] == 'yes')) {
									$total = $_POST['total'];
									$bayar = $_POST['bayar'];
									if (!empty($bayar)) {
										$hitung = $bayar - $total;
										if ($bayar >= $total) {
											$id_barang = $_POST['id_barang'];
											$id_member = $_POST['id_member'];
											$jumlah = $_POST['jumlah'];
											$total = $_POST['total1'];
											$tgl_input = $_POST['tgl_input'];
											$periode = $_POST['periode'];
											$jumlah_dipilih = count($id_barang);

											for ($x = 0; $x < $jumlah_dipilih; $x++) {

												$d = array($id_barang[$x], $id_member[$x], $jumlah[$x], $total[$x], $tgl_input[$x], $periode[$x]);
												$sql = "INSERT INTO nota (id_barang,id_member,jumlah,total,tanggal_input,periode) VALUES(?,?,?,?,?,?)";
												$row = $config->prepare($sql);
												$row->execute($d);

												// ubah stok barang
												$sql_barang = "SELECT * FROM barang WHERE id_barang = ?";
												$row_barang = $config->prepare($sql_barang);
												$row_barang->execute(array($id_barang[$x]));
												$hsl = $row_barang->fetch();

												$stok = $hsl['stok'];
												$idb = $hsl['id_barang'];

												$total_stok = $stok - $jumlah[$x];
												// echo $total_stok;
												$sql_stok = "UPDATE barang SET stok = ? WHERE id_barang = ?";
												$row_stok = $config->prepare($sql_stok);
												$row_stok->execute(array($total_stok, $idb));
											}
											echo '<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="successModalLabel">Pembayaran Berhasil</h5>
															</div>
															<div class="modal-body">
																Belanjaan Berhasil Di Bayar!
															</div>
															<div class="modal-footer">
																<a href="print.php?nm_member=' . $_SESSION['admin']['nm_member'] . '&bayar=' . $bayar . '&kembali=' . $hitung . '&total=' . $total_bayar . '" target="_blank" class="btn btn-secondary">
																	<i class="fa fa-print"></i> Print Untuk Bukti Pembayaran
																</a>
																<a href="fungsi/hapus/hapus.php?penjualan=jual" class="btn btn-danger">
																	<i class="fa fa-refresh"></i> Reset Pembayaran
																</a>
															</div>
														</div>
													</div>
												</div>';
											echo '<script>
													$(document).ready(function(){
														$("#successModal").modal("show");
													});
												</script>';
										} else {
											echo '<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="errorModalLabel">Pembayaran Gagal</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																Uang Kurang! Rp.' . $hitung . '
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
															</div>
														</div>
													</div>
												</div>';
											echo '<script>
													$(document).ready(function(){
														$("#errorModal").modal("show");
													});
												</script>';
										}
									}
								}
								?>
								<!-- aksi ke table nota -->
								<form method="POST" action="index.php?page=jual&nota=yes#kasirnya">
									<?php foreach ($hasil_penjualan as $isi) {
										; ?>
										<input type="hidden" name="id_barang[]" value="<?php echo $isi['id_barang']; ?>">
										<input type="hidden" name="id_member[]" value="<?php echo $isi['id_member']; ?>">
										<input type="hidden" name="jumlah[]" value="<?php echo $isi['jumlah']; ?>">
										<input type="hidden" name="total1[]" value="<?php echo $isi['total']; ?>">
										<input type="hidden" name="tgl_input[]"
											value="<?php echo $isi['tanggal_input']; ?>">
										<input type="hidden" name="periode[]" value="<?php echo date('m-Y'); ?>">
										<?php $no++;
									} ?>
									<tr>
										<td>Total Semua </td>
										<td><input type="text" class="form-control" name="total"
												value="<?php echo $total_bayar; ?>"></td>

										<td>Bayar </td>
										<td><input type="text" class="form-control" name="bayar"
												value="<?php echo $bayar; ?>"></td>
										<td><button class="btn btn-success" id="btnBayar" disabled><i
													class="fa fa-shopping-cart"></i> Bayar</button></td>
										<?php if (!empty($_GET['nota'] == 'yes')) { ?>
											</td><?php } ?></td>
									</tr>
								</form>
								<!-- aksi ke table nota -->
								<tr>
									<td>Kembalian</td>
									<td><input type="text" class="form-control" value="<?php echo $hitung; ?>"></td>
									<td></td>
									<td>
										<a href="print.php?nm_member=<?php echo $_SESSION['admin']['nm_member']; ?>
									&bayar=<?php echo $bayar; ?>&kembali=<?php echo $hitung; ?>&total=<?php echo $total_bayar; ?>"
											target="_blank">

									</td>
								</tr>
							</table>
							<br />
							<br />
						</div>
					</div>
				</div>
			</div>
		</div>


		<script>
			// AJAX call for autocomplete 
			$(document).ready(function () {
				$("#cari").change(function () {
					$.ajax({
						type: "POST",
						url: "fungsi/edit/edit.php?cari_barang=yes",
						data: 'keyword=' + $(this).val(),
						beforeSend: function () {
							$("#hasil_cari").hide();
							$("#tunggu").html('<p style="color:green"><blink>tunggu sebentar</blink></p>');
						},
						success: function (html) {
							$("#tunggu").html('');
							$("#hasil_cari").show();
							$("#hasil_cari").html(html);
						}
					});
				});
			});
			//To select country name
		</script>

		<script>
			$(document).ready(function () {
				// Mengaktifkan/menonaktifkan tombol bayar berdasarkan input bayar
				$('input[name="bayar"]').on('input', function () {
					var bayarValue = $(this).val();
					$('#btnBayar').prop('disabled', !bayarValue); // Nonaktifkan jika kosong
				});
			});
		</script>

		<!-- Modal -->
		<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="resetModalLabel">Konfirmasi Reset Keranjang</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Apakah anda ingin reset keranjang?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
						<a class="btn btn-danger" href="fungsi/hapus/hapus.php?penjualan=jual">Reset Keranjang</a>
					</div>
				</div>
			</div>
		</div>
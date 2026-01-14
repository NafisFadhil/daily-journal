<?php
include "koneksi.php";

$keyword = $_POST['keyword'];

$sql = "SELECT * FROM galeri 
        WHERE deskripsi LIKE ? OR tanggal LIKE ? OR username LIKE ?
        ORDER BY tanggal DESC";

$stmt = $conn->prepare($sql);
$search = "%" . $keyword . "%";
$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();

$result = $stmt->get_result();

$no = 1;
while ($row = $result->fetch_assoc()) {
	?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $row["deskripsi"] ?>
			<br><small class="text-muted">pada : <?= $row["tanggal"] ?></small>
			<br><small class="text-muted">oleh : <?= $row["username"] ?></small>
		</td>
		<td>
			<?php
			if ($row["gambar"] != '') {
				if (file_exists($row["gambar"] . '')) {
					echo '<img src="' . $row["gambar"] . '" class="img-fluid" alt="Gambar Galeri" style="max-width: 200px; max-height: 200px; object-fit: cover;">';
				}
			}
			?>
		</td>
		<td>
			<a href="#" title="edit" class="badge rounded-pill text-bg-success" data-bs-toggle="modal"
				data-bs-target="#modalEdit<?= $row["id"] ?>"><i class="bi bi-pencil"></i></a>
			<a href="#" title="delete" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal"
				data-bs-target="#modalHapus<?= $row["id"] ?>"><i class="bi bi-x-circle"></i></a>
			<!-- Awal Modal Edit -->
			<div class="modal fade" id="modalEdit<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false"
				tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Galeri</h1>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<form method="post" action="" enctype="multipart/form-data">
							<div class="modal-body">
								<div class="mb-3">
									<label for="gambar" class="form-label">Ganti Gambar</label>
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<input type="file" class="form-control" name="gambar">
								</div>
								<div class="mb-3">
									<label for="formGroupExampleInput3" class="form-label">Gambar Lama</label>
									<?php
									if ($row["gambar"] != '') {
										if (file_exists($row["gambar"] . '')) {
											echo '<br><img src="' . $row["gambar"] . '" class="img-fluid" alt="Gambar Galeri" style="max-width: 200px; max-height: 200px; object-fit: cover;">';
										}
									}
									?>
									<input type="hidden" name="gambar_lama" value="<?= $row["gambar"] ?>">
								</div>
								<div class="mb-3">
									<label for="deskripsi">Deskripsi</label>
									<textarea class="form-control" placeholder="Tuliskan Deskripsi Galeri" name="deskripsi"
										required><?= $row["deskripsi"] ?></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<input type="submit" value="simpan" name="simpan" class="btn btn-primary">
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Akhir Modal Edit -->

			<!-- Awal Modal Hapus -->
			<div class="modal fade" id="modalHapus<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false"
				tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Galeri</h1>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<form method="post" action="" enctype="multipart/form-data">
							<div class="modal-body">
								<div class="mb-3">
									<label for="formGroupExampleInput" class="form-label">Yakin akan menghapus galeri ini?</label>
									<?php
									if ($row["gambar"] != '') {
										if (file_exists($row["gambar"] . '')) {
											echo '<br><img src="' . $row["gambar"] . '" class="img-fluid" alt="Gambar Galeri" style="max-width: 200px; max-height: 200px; object-fit: cover;">';
										}
									}
									?>
									<input type="hidden" name="id" value="<?= $row["id"] ?>">
									<input type="hidden" name="gambar" value="<?= $row["gambar"] ?>">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">batal</button>
								<input type="submit" value="hapus" name="hapus" class="btn btn-primary">
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Akhir Modal Hapus -->
		</td>
	</tr>
	<?php
}
?>

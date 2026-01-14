<?php
// ambil data user untuk menampilkan profil
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

//query untuk mengambil data article
$sql1 = "SELECT * FROM article ORDER BY tanggal DESC";
$hasil1 = $conn->query($sql1);

//menghitung jumlah baris data article
$jumlah_article = $hasil1->num_rows; 

//query untuk mengambil data galeri
$sql2 = "SELECT * FROM galeri ORDER BY tanggal DESC";
$hasil2 = $conn->query($sql2);

//menghitung jumlah baris data galeri
$jumlah_galeri = $hasil2->num_rows; 

?>

<!-- Welcome Section -->
<div class="text-center mb-5">
    <h2 class="mb-2">Selamat Datang,</h2>
    <h1 class="text-danger fw-bold mb-4" style="font-size: 2.5rem;"><?php echo htmlspecialchars($user['username']); ?></h1>
    
    <!-- Profile Picture -->
    <div class="mb-4">
        <?php if (!empty($user['foto_profil']) && file_exists($user['foto_profil'])): ?>
            <img src="<?php echo htmlspecialchars($user['foto_profil']); ?>" 
                 alt="Foto Profil" 
                 class="rounded-circle" 
                 style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ffd5db;">
        <?php else: ?>
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light" 
                 style="width: 150px; height: 150px; border: 3px solid #ffd5db;">
                <i class="bi bi-person-circle" style="font-size: 120px; color: #ccc;"></i>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Cards -->
<div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center pt-4">
    <div class="col">
        <div class="card border border-danger-subtle mb-3 shadow-sm" style="max-width: 300px; margin: 0 auto;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0"><i class="bi bi-newspaper"></i> Article</h5> 
                    </div>
                    <div>
                        <span class="badge rounded-pill text-bg-danger fs-4"><?php echo $jumlah_article; ?></span>
                    </div> 
                </div>
            </div>
        </div>
    </div> 
    <div class="col">
        <div class="card border border-danger-subtle mb-3 shadow-sm" style="max-width: 300px; margin: 0 auto;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0"><i class="bi bi-camera"></i> Gallery</h5> 
                    </div>
                    <div>
                        <span class="badge rounded-pill text-bg-danger fs-4"><?php echo $jumlah_galeri; ?></span>
                    </div> 
                </div>
            </div>
        </div>
    </div> 
</div>

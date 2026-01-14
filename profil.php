<?php
include 'upload_foto.php';

$username = $_SESSION['username'];
$message = '';
$messageType = '';

// ambil data user
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// jika ada request dengan method POST dan ada tombol save yang diklik
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    $updateUsername = false;
    $updatePassword = false;
    $updatePhoto = false;
    $errors = [];
    $oldUsername = $username;
    
    // jika ada username yang dikirimkan dan username tidak kosong
    if (isset($_POST['username']) && !empty(trim($_POST['username']))) {
        $newUsername = trim($_POST['username']);
        
        if ($newUsername != $oldUsername) {
            // cek apakah username sudah ada
            $checkStmt = $conn->prepare("SELECT username FROM user WHERE username = ? AND username != ?");
            $checkStmt->bind_param("ss", $newUsername, $oldUsername);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                $errors[] = "Username sudah digunakan!";
            } else {
                $updateUsername = true;
            }
            $checkStmt->close();
        }
    } else {
        $errors[] = "Username tidak boleh kosong!";
    }
    
    // jika ada password baru yang dikirimkan dan password tidak kosong
    if (!empty($_POST['new_password'])) {
        $newPassword = $_POST['new_password'];
        
        if (strlen($newPassword) < 6) {
            $errors[] = "Password baru minimal 6 karakter!";
        } else {
            $hashedPassword = md5($newPassword);
            $updatePassword = true;
        }
    }
    
    // jika ada foto profil yang dikirimkan dan foto profil tidak kosong
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $uploadResult = upload_foto($_FILES['profile_photo']);
        
        if ($uploadResult['status']) {
            $photoPath = $uploadResult['message'];
            
            // cek apakah user table has foto_profil column, jika tidak, tambahkan itu
            $checkColumn = $conn->query("SHOW COLUMNS FROM user LIKE 'foto_profil'");
            if ($checkColumn->num_rows == 0) {
                $conn->query("ALTER TABLE user ADD COLUMN foto_profil VARCHAR(255) DEFAULT NULL");
            }
            
            // hapus foto profil lama jika ada
            if (!empty($user['foto_profil']) && file_exists($user['foto_profil'])) {
                unlink($user['foto_profil']);
            }
            
            $updatePhoto = true;
        } else {
            $errors[] = $uploadResult['message'];
        }
    }
    
    // update database jika tidak ada error
    if (empty($errors)) {
        $success = true;
        $currentUsername = $oldUsername; // gunakan username lama untuk WHERE clause
        
        // update username pertama jika perlu
        if ($updateUsername) {
            $updateStmt = $conn->prepare("UPDATE user SET username = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $newUsername, $currentUsername);
            if (!$updateStmt->execute()) {
                $success = false;
                $errors[] = "Gagal mengubah username!";
            } else {
                // update session dan username saat ini untuk update selanjutnya
                $_SESSION['username'] = $newUsername;
                $currentUsername = $newUsername;
            }
            $updateStmt->close();
        }
        
        if ($updatePassword) {
            $updateStmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $hashedPassword, $currentUsername);
            if (!$updateStmt->execute()) {
                $success = false;
                $errors[] = "Gagal mengubah password!";
            }
            $updateStmt->close();
        }
        
        if ($updatePhoto) {
            $updateStmt = $conn->prepare("UPDATE user SET foto_profil = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $photoPath, $currentUsername);
            if (!$updateStmt->execute()) {
                $success = false;
                $errors[] = "Gagal mengubah foto profil!";
            } else {
                $user['foto_profil'] = $photoPath;
            }
            $updateStmt->close();
        }
        
        if ($success && ($updateUsername || $updatePassword || $updatePhoto)) {
            $message = "Data berhasil disimpan!";
            $messageType = "success";
            // update username variable untuk display
            if ($updateUsername) {
                $username = $newUsername;
            }
        } elseif (!$updateUsername && !$updatePassword && !$updatePhoto) {
            $message = "Tidak ada perubahan yang disimpan.";
            $messageType = "info";
        }
    } else {
        $message = implode(" ", $errors);
        $messageType = "danger";
    }
}

// ambil data user terbaru (gunakan session username jika sudah diupdate)
$currentUsername = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<div class="container-fluid py-4">
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="max-w-600">
        <div class="mb-4">
            <label for="username" class="form-label fw-bold">Username</label>
            <input type="text" 
                   class="form-control" 
                   id="username" 
                   name="username" 
                   value="<?php echo htmlspecialchars($user['username']); ?>" 
                   required>
        </div>

        <div class="mb-4">
            <label for="new_password" class="form-label fw-bold">Ganti Password</label>
            <input type="text" 
                   class="form-control" 
                   id="new_password" 
                   name="new_password" 
                   placeholder="Tuliskan Password Baru Jika Ingin Mengganti Password Saja">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
        </div>

        <div class="mb-4">
            <label for="profile_photo" class="form-label fw-bold">Ganti Foto Profil</label>
            <input type="file" 
                   class="form-control" 
                   id="profile_photo" 
                   name="profile_photo" 
                   accept="image/jpeg,image/jpg,image/png,image/gif">
            <small class="text-muted">Format: JPG, PNG, GIF (Max: 500KB). Kosongkan jika tidak ingin mengubah foto</small>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Foto Profil Saat Ini</label>
            <div class="mt-2">
                <?php if (!empty($user['foto_profil']) && file_exists($user['foto_profil'])): ?>
                    <img src="<?php echo htmlspecialchars($user['foto_profil']); ?>" 
                         alt="Foto Profil" 
                         class="img-thumbnail" 
                         style="max-width: 300px; max-height: 300px; object-fit: cover;">
                <?php else: ?>
                    <div class="border rounded p-4 d-inline-block bg-light">
                        <i class="bi bi-person-circle" style="font-size: 100px; color: #ccc;"></i>
                        <p class="text-muted mb-0 mt-2">Belum ada foto profil</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-4">
            <button type="submit" name="save" class="btn btn-primary">
                simpan
            </button>
        </div>
    </form>
</div>

<style>
    .max-w-600 {
        max-width: 600px;
    }
    
    body.dark-mode .bg-light {
        background-color: #2d2d2d !important;
    }
</style>

<?php

session_start();

include "koneksi.php";

if(isset($_SESSION['username'])) {
	header('location:admin.php');
}

//check apakah ada request dengan method POST yang dilakukan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$userInput = $_POST['user'];
	$passInput = $_POST['pass'];

	if ($userInput == "") {
		echo "Username tidak boleh kosong!";
		exit; 
	}

	if ($passInput == "") {
		echo "Password tidak boleh kosong!";
		exit;
	}

	$username = $userInput;
	$password = md5($passInput);

	$stmt = $conn->prepare("SELECT * 
                                FROM user 
                                WHERE username=? AND password=?");

	$stmt->bind_param("ss", $username, $password);

	$stmt->execute();

	$hasil = $stmt->get_result();

	$row = $hasil->fetch_array(MYSQLI_ASSOC);

	if (!empty($row)) {
		$_SESSION['username'] = $username;
		header("location:admin.php");
	} else {
		header("location:login.php");
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Login | Muhammad Nafis Fadhil</title>
	<link rel="icon" href="images/logo-3.png" />

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />

	<style>
		:root {
			--brand-primary: red;
			--brand-primary-dark: #cc0000;
			--bg-soft: #ffd5db;
		}

		body {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			background: radial-gradient(circle at 20% 20%, #ffe9ee, #fff5f7),
				linear-gradient(135deg, #fff7f8 0%, #ffe4eb 60%, #ffd5db 100%);
			font-family: "Inter", system-ui, -apple-system, "Segoe UI", sans-serif;
			color: #2b2b2b;
		}

		.login-card {
			max-width: 420px;
			width: 100%;
			border: none;
			border-radius: 16px;
			box-shadow: 0 20px 45px rgba(255, 59, 59, 0.1);
		}

		.brand-badge {
			height: 56px;
			width: 56px;
			display: grid;
			place-items: center;
			border-radius: 14px;
			background: var(--bg-soft);
			color: var(--brand-primary);
			font-size: 24px;
			box-shadow: inset 0 0 0 1px rgba(255, 0, 0, 0.15);
		}

		.btn-primary {
			background-color: var(--brand-primary);
			border-color: var(--brand-primary);
		}

		.btn-primary:hover {
			background-color: var(--brand-primary-dark);
			border-color: var(--brand-primary-dark);
		}

		.form-control:focus {
			border-color: var(--brand-primary);
			box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.15);
		}

		.small-link {
			color: var(--brand-primary);
			text-decoration: none;
			font-weight: 600;
		}

		.small-link:hover {
			color: var(--brand-primary-dark);
			text-decoration: underline;
		}

		/* Dark mode support mengikuti tema index */
		body.dark-mode {
			background: #121212;
			color: #e0e0e0;
		}

		body.dark-mode .card {
			background-color: #1e1e1e;
			color: #e0e0e0;
			box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
		}

		body.dark-mode .form-control {
			background-color: #2d2d2d;
			border-color: #404040;
			color: #e0e0e0;
		}

		body.dark-mode .form-control:focus {
			border-color: var(--brand-primary);
			box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.2);
		}

		body.dark-mode .small-link {
			color: #ff6b6b;
		}

		body.dark-mode .small-link:hover {
			color: #ff8a8a;
		}
	</style>
</head>

<body>
	<main class="container py-5">
		<div class="row justify-content-center">
			<div class="col-12 col-md-10 col-lg-6 d-flex justify-content-center">
				<div class="card login-card p-4 p-md-5">
					<div class="d-flex align-items-center justify-content-between mb-4">
						<div class="d-flex align-items-center gap-3">
							<div class="brand-badge">
								<i class="bi bi-shield-lock-fill"></i>
							</div>
							<div>
								<h4 class="mb-0">Selamat Datang</h4>
								<small class="text-muted">Masuk untuk melanjutkan</small>
							</div>
						</div>
						<button type="button" class="btn btn-outline-secondary btn-sm" id="themeToggle"
							aria-label="Toggle dark mode">
							<i class="bi bi-moon-stars"></i>
						</button>
					</div>

					<form novalidate method="POST">
						<div class="mb-3">
							<label for="email" class="form-label">Email / Username</label>
							<div class="input-group">
								<span class="input-group-text"><i class="bi bi-envelope"></i></span>
								<input type="text" class="form-control" id="user" name="user" placeholder="nama@example.com" required />
							</div>
							<div class="invalid-feedback">Mohon isi email atau username.</div>
						</div>

						<div class="mb-3">
							<div class="d-flex justify-content-between align-items-center mb-1">
								<label for="password" class="form-label mb-0">Password</label>
								<button type="button" class="btn btn-link p-0 small-link small" id="togglePassword">
									Tampilkan
								</button>
							</div>
							<div class="input-group">
								<span class="input-group-text"><i class="bi bi-lock"></i></span>
								<input type="password" class="form-control" id="password" name="pass" placeholder="••••••••" required />
							</div>
							<div class="invalid-feedback">Password wajib diisi.</div>
						</div>

						<div class="d-grid mb-3">
							<button type="submit" class="btn btn-primary py-2">
								<i class="bi bi-box-arrow-in-right me-1"></i>
								Masuk
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>

	<script>
		const form = document.querySelector("form");
		const passwordInput = document.getElementById("password");
		const togglePassword = document.getElementById("togglePassword");
		const themeToggle = document.getElementById("themeToggle");

		form.addEventListener("submit", (event) => {
			if (!form.checkValidity()) {
				event.preventDefault();
				event.stopPropagation();
			}
			form.classList.add("was-validated");
		});

		togglePassword.addEventListener("click", () => {
			const isHidden = passwordInput.type === "password";
			passwordInput.type = isHidden ? "text" : "password";
			togglePassword.textContent = isHidden ? "Sembunyikan" : "Tampilkan";
		});

		themeToggle.addEventListener("click", () => {
			document.body.classList.toggle("dark-mode");
		});
	</script>
</body>

</html>

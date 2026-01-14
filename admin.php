<?php 
include 'koneksi.php';
session_start(); 

if (!isset($_SESSION['username'])) { 
	header("location:login.php"); 
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Belajar Bootstrap | Muhammad Nafis Fadhil</title>
		<link rel="icon" href="images/logo-3.png" />
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

		<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
		/>
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
			crossorigin="anonymous"
		/>

		<style>
			.accordion-button:not(.collapsed) {
				background-color: red !important;
				color: white;
			}

			#loader {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: #020024;
				background: #ffd5db;
				display: flex;
				justify-content: center;
				align-items: center;
				z-index: 9999;
				transition: opacity 0.5s ease-out;
			}

			.loader-content {
				text-align: center;
				color: red;
			}

			.spinner {
				width: 60px;
				height: 60px;
				border: 4px solid rgba(255, 255, 255, 0.3);
				border-top: 4px solid red;
				border-radius: 50%;
				animation: spin 1s linear infinite;
				margin: 0 auto 20px;
			}

			@keyframes spin {
				0% {
					transform: rotate(0deg);
				}
				100% {
					transform: rotate(360deg);
				}
			}

			.loader-text {
				font-size: 1.2rem;
				font-weight: 500;
				margin-bottom: 10px;
			}

			.loader-subtext {
				font-size: 0.9rem;
				opacity: 0.8;
			}

			.fade-out {
				opacity: 0;
			}

			html {
				scroll-behavior: smooth;
			}

			.navbar-nav .nav-link.active {
				color: red !important;
				font-weight: 600;
			}

			section {
				scroll-margin-top: 80px;
			}

			/* Theme Switcher Styles */
			.theme-toggle {
				background: none;
				border: 1px solid rgba(255, 255, 255, 0.3);
				color: white;
				padding: 8px 12px;
				border-radius: 5px;
				cursor: pointer;
				transition: all 0.3s ease;
				margin-left: 10px;
			}

			.theme-toggle:hover {
				background: rgba(255, 255, 255, 0.1);
			}

			#content {
				flex: 1;
			}

			body {
				min-height: 100dvh;
				display: flex;
				flex-direction: column;
			}

			/* Dark Mode Styles */
			body.dark-mode {
				background-color: #121212;
				color: #e0e0e0;
				transition: background-color 0.3s ease, color 0.3s ease;
			}

			body.dark-mode section {
				background-color: #1e1e1e;
				color: #e0e0e0;
			}

			body.dark-mode #home {
				background-color: #2d1b1f !important;
			}

			body.dark-mode .card {
				background-color: #2d2d2d;
				border-color: #404040;
				color: #e0e0e0;
			}

			body.dark-mode .card-body {
				color: #e0e0e0;
			}

			body.dark-mode .card-title {
				color: #e0e0e0;
			}

			body.dark-mode .card-text {
				color: #b0b0b0;
			}

			body.dark-mode .text-muted {
				color: #888 !important;
			}

			body.dark-mode .accordion-item {
				background-color: #2d2d2d;
				border-color: #404040;
			}

			body.dark-mode .accordion-button {
				background-color: #2d2d2d;
				color: #e0e0e0;
			}

			body.dark-mode .accordion-button:not(.collapsed) {
				background-color: red !important;
				color: white;
			}

			body.dark-mode .accordion-body {
				background-color: #2d2d2d;
				color: #e0e0e0;
			}

			body.dark-mode .form-control {
				background-color: #2d2d2d;
				border-color: #404040;
				color: #e0e0e0;
			}

			body.dark-mode .form-control:focus {
				background-color: #2d2d2d;
				border-color: red;
				color: #e0e0e0;
			}

			body.dark-mode .form-label {
				color: #e0e0e0;
			}

			body.dark-mode .bg-light {
				background-color: #1e1e1e !important;
			}

			body.dark-mode h1,
			body.dark-mode h2,
			body.dark-mode h3,
			body.dark-mode h4,
			body.dark-mode h5,
			body.dark-mode h6 {
				color: #e0e0e0;
			}

			body.dark-mode .text-primary {
				color: #ff6b6b !important;
			}

			body.dark-mode .btn-primary {
				background-color: red;
				border-color: red;
			}

			body.dark-mode .btn-primary:hover {
				background-color: #cc0000;
				border-color: #cc0000;
			}

			#scrollToTop {
				position: fixed;
				bottom: 30px;
				right: 30px;
				width: 50px;
				height: 50px;
				background-color: red;
				color: white;
				border: none;
				border-radius: 50%;
				cursor: pointer;
				display: none;
				align-items: center;
				justify-content: center;
				font-size: 20px;
				z-index: 1000;
				transition: all 0.3s ease;
				box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
			}

			#scrollToTop:hover {
				background-color: #cc0000;
				transform: translateY(-3px);
				box-shadow: 0 6px 8px rgba(0, 0, 0, 0.4);
			}

			#scrollToTop.show {
				display: flex;
			}

			#tanggal {
				font-size: 1.2rem;
				font-weight: 500;
				margin-bottom: 10px;
			}

			#jam {
				font-size: 2rem;
				font-weight: bold;
				color: red;
			}
		</style>
	</head>
	<body>
		<div id="loader">
			<div class="loader-content">
				<div class="spinner"></div>
				<div class="loader-text">
					Tunggu Sebentar, Websitemu lagi disiapin...
				</div>
				<div class="loader-subtext">Muhammad Nafis Fadhil</div>
			</div>
		</div>

		<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
			<div class="container">
				<a class="navbar-brand" href="#">
					<img
						src="images/profile.jpeg"
						alt="Profile"
						width="30"
						class="rounded-circle"
					/>
					Muhammad Nafis Fadhil
				</a>
				<button
					class="navbar-toggler"
					type="button"
					data-bs-toggle="collapse"
					data-bs-target="#navbarNav"
					aria-controls="navbarNav"
					aria-expanded="false"
					aria-label="Toggle navigation"
				>
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav ms-auto align-items-center">
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="admin.php?page=dashboard"
								>Dashboard</a
							>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="admin.php?page=artikel">Artikel</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="admin.php?page=galeri">Galeri</a>
						</li>
						<li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= $_SESSION['username']?>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li> 
                </ul>
            </li>
						<li class="nav-item">
							<button
								class="theme-toggle"
								id="themeToggle"
								aria-label="Toggle theme"
							>
								<i class="bi bi-moon-fill" id="themeIcon"></i>
							</button>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- content begin -->
    <section id="content" class="p-5">
        <div class="container"> 
						<?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = "dashboard";
            }

            echo '<h4 class="lead display-6 pb-2 border-bottom border-danger-subtle">' . $page . '</h4>';
            include($page . ".php");
            ?>
        </div> 
    </section>
    <!-- content end -->

		<footer class="bg-dark text-white text-center py-3 mt-5">
			<div class="container">
				<p class="mb-0">&copy; 2025 Nafis Fadhil | Pemrograman Berbasis Web</p>
			</div>
		</footer>

		<!-- Scroll to Top Button -->
		<button id="scrollToTop" aria-label="Scroll to top">
			<i class="bi bi-arrow-up"></i>
		</button>

		<script
			src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
			integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
			crossorigin="anonymous"
		></script>
		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
			crossorigin="anonymous"
		></script>

		<script>
			function initTheme() {
				const savedTheme = localStorage.getItem("theme") || "light";
				const body = document.body;
				const themeIcon = document.getElementById("themeIcon");

				if (savedTheme === "dark") {
					body.classList.add("dark-mode");
					themeIcon.classList.remove("bi-moon-fill");
					themeIcon.classList.add("bi-sun-fill");
				} else {
					body.classList.remove("dark-mode");
					themeIcon.classList.remove("bi-sun-fill");
					themeIcon.classList.add("bi-moon-fill");
				}
			}

			function toggleTheme() {
				const body = document.body;
				const themeIcon = document.getElementById("themeIcon");
				const isDarkMode = body.classList.contains("dark-mode");

				if (isDarkMode) {
					body.classList.remove("dark-mode");
					themeIcon.classList.remove("bi-sun-fill");
					themeIcon.classList.add("bi-moon-fill");
					localStorage.setItem("theme", "light");
				} else {
					body.classList.add("dark-mode");
					themeIcon.classList.remove("bi-moon-fill");
					themeIcon.classList.add("bi-sun-fill");
					localStorage.setItem("theme", "dark");
				}
			}

			document.addEventListener("DOMContentLoaded", function () {
				initTheme();

				const themeToggle = document.getElementById("themeToggle");
				if (themeToggle) {
					themeToggle.addEventListener("click", toggleTheme);
				}

			});

			window.addEventListener("load", function () {
				setTimeout(function () {
					const loader = document.getElementById("loader");
					loader.classList.add("fade-out");

					setTimeout(function () {
						loader.style.display = "none";
					}, 500);
				}, 1000);
			});

			const scrollToTopBtn = document.getElementById("scrollToTop");

			window.addEventListener("scroll", function () {
				if (window.pageYOffset > 300) {
					scrollToTopBtn.classList.add("show");
				} else {
					scrollToTopBtn.classList.remove("show");
				}
			});

			scrollToTopBtn.addEventListener("click", function () {
				window.scrollTo({
					top: 0,
					behavior: "smooth",
				});
			});
		</script>
	</body>
</html>

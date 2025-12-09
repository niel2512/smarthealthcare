<?php
session_start();

// Jika belum login maka redirect ke login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Smart Health Care - Solusi Kesehatan Digital</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#10b981",
            secondary: "#06b6d4",
          },
        },
      },
    };
  </script>
  <style>
    @keyframes float {

      0%,
      100% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    .float-animation {
      animation: float 3s ease-in-out infinite;
    }

    .gradient-text {
      background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-emerald-50 via-white to-cyan-50 scroll-smooth">
  <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
          </div>
          <span class="text-xl font-bold gradient-text">Smart Health Care</span>
        </div>
        <div class="md:flex items-center gap-8 ">
          <h1 class="text-xl">Selamat datang, <span class="font-bold"><?= htmlspecialchars($name) ?>! 👋</span> </h1>
          <a class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-6 py-2 rounded-full hover:shadow-lg transition" href="logout.php">Logout</a>

          <!-- <a href="/login.html" class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-6 py-2 rounded-full hover:shadow-lg transition">Login</a> -->
        </div>
        <button class="md:hidden text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="max-w-7xl mx-auto px-6 py-16 md:py-24">
    <div class="grid md:grid-cols-2 gap-12 items-center">
      <div class="space-y-6">
        <div class="inline-block">
          <span class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full text-sm font-semibold">✨ Platform Kesehatan Terpercaya</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
          Kesehatan Anda,
          <span class="gradient-text">Prioritas Kami</span>
        </h1>
        <p class="text-lg text-gray-600 leading-relaxed">Smart Health Care menghubungkan Anda dengan dokter terbaik melalui sistem janji temu yang mudah, cepat, dan terpercaya. Kelola kesehatan Anda dengan lebih efisien.</p>
        <div class="flex flex-wrap gap-4">
          <a href="/list-dokter.php" class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-8 py-4 rounded-full font-semibold hover:shadow-xl transition transform hover:scale-105"> Cek Jadwal Dokter </a>
          <a href="#about" class="bg-white text-gray-700 px-8 py-4 rounded-full font-semibold border-2 border-gray-200 hover:border-emerald-500 transition"> Pelajari Lebih Lanjut </a>
        </div>
        <div class="flex items-center gap-8 pt-4">
          <div>
            <div class="text-3xl font-bold text-gray-900">1000+</div>
            <div class="text-sm text-gray-600">Pasien Terdaftar</div>
          </div>
          <div>
            <div class="text-3xl font-bold text-gray-900">50+</div>
            <div class="text-sm text-gray-600">Dokter Berpengalaman</div>
          </div>
          <div>
            <div class="text-3xl font-bold text-gray-900">24/7</div>
            <div class="text-sm text-gray-600">Layanan Tersedia</div>
          </div>
        </div>
      </div>
      <div class="relative">
        <div class="float-animation">
          <div class="bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-3xl p-8 shadow-2xl">
            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600" alt="Healthcare" class="rounded-2xl w-full h-auto" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="layanan" class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Layanan Kami</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Berbagai fitur yang memudahkan Anda dalam mengakses layanan kesehatan</p>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="group bg-gradient-to-br from-emerald-50 to-cyan-50 p-6 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-2">
          <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Janji Temu</h3>
          <p class="text-gray-600">Buat dan kelola janji temu dengan dokter pilihan Anda dengan mudah</p>
        </div>

        <div class="group bg-gradient-to-br from-emerald-50 to-cyan-50 p-6 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-2">
          <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Cek Ketersediaan</h3>
          <p class="text-gray-600">Lihat jadwal dan ketersediaan dokter secara real-time</p>
        </div>

        <div class="group bg-gradient-to-br from-emerald-50 to-cyan-50 p-6 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-2">
          <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Lokasi Fasilitas</h3>
          <p class="text-gray-600">Temukan rumah sakit dan klinik terdekat dari lokasi Anda</p>
        </div>

        <div class="group bg-gradient-to-br from-emerald-50 to-cyan-50 p-6 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-2">
          <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">Status Real-time</h3>
          <p class="text-gray-600">Monitor status layanan dan kesehatan sistem secara langsung</p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
          <img src="https://images.unsplash.com/photo-1551076805-e1869033e561?w=600" alt="About" class="rounded-3xl shadow-2xl" />
        </div>
        <div class="space-y-6">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Mengapa Memilih <span class="gradient-text">Smart Health Care?</span></h2>
          <p class="text-gray-600 leading-relaxed">
            Kami adalah platform kesehatan digital yang mengintegrasikan teknologi modern dengan layanan kesehatan berkualitas. Dengan fokus pada kemudahan akses dan pengalaman pengguna yang optimal.
          </p>
          <div class="space-y-4">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 mb-1">Mudah Digunakan</h4>
                <p class="text-gray-600">Interface yang intuitif dan user-friendly untuk semua kalangan</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 mb-1">Terintegrasi</h4>
                <p class="text-gray-600">Terhubung dengan berbagai layanan kesehatan melalui API Gateway</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <div>
                <h4 class="font-semibold text-gray-900 mb-1">Terpercaya & Aman</h4>
                <p class="text-gray-600">Dibangun dengan teknologi terkini untuk keamanan data maksimal</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="bg-gradient-to-r from-emerald-500 to-cyan-500 py-20">
    <div class="max-w-4xl mx-auto px-6 text-center text-white">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Memulai Perjalanan Kesehatan Anda?</h2>
      <p class="text-lg mb-8 text-emerald-50">Bergabunglah dengan ribuan pengguna yang telah mempercayai Smart Health Care</p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="/list-dokter.php" class="bg-white text-emerald-600 px-8 py-4 rounded-full font-semibold hover:shadow-xl transition transform hover:scale-105"> Mulai Sekarang </a>
        <a href="#about" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-emerald-600 transition"> Hubungi Kami </a>
      </div>
    </div>
  </section>

  <footer class="bg-gray-900 text-gray-300 py-12">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-4 gap-8 mb-8">
        <div>
          <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg"></div>
            <span class="text-white font-bold">Smart Health Care</span>
          </div>
          <p class="text-sm">Platform kesehatan digital terpercaya untuk kemudahan akses layanan medis Anda.</p>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-4">Menu</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#home" class="hover:text-emerald-400 transition">Home</a></li>
            <li><a href="#about" class="hover:text-emerald-400 transition">About Us</a></li>
            <li><a href="#lokasi" class="hover:text-emerald-400 transition">Lokasi</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-4">Layanan</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-emerald-400 transition">Janji Temu</a></li>
            <li><a href="#" class="hover:text-emerald-400 transition">Konsultasi Online</a></li>
            <li><a href="#" class="hover:text-emerald-400 transition">Rekam Medis</a></li>
            <li><a href="#" class="hover:text-emerald-400 transition">Farmasi</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-4">Kontak</h4>
          <ul class="space-y-2 text-sm">
            <li>Email: info@smarthealthcare.id</li>
            <li>Phone: (021) 1234-5678</li>
            <li>Senin - Jumat: 08:00 - 17:00</li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-800 pt-8 text-center text-sm">
        <p>&copy; 2025 Smart Health Care. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const user = JSON.parse(localStorage.getItem("user"));
      const navMenu = document.querySelector(".hidden.md\\:flex.items-center.gap-8");

      navMenu.innerHTML = "";

      if (user) {
        navMenu.innerHTML = `
        <span class="font-semibold text-gray-800">${user.name}</span>
        <button id="logoutBtn" class="bg-red-500 text-white px-6 py-2 rounded-full hover:shadow-lg transition">
          Logout
        </button>
      `;

        document.getElementById("logoutBtn").addEventListener("click", () => {
          localStorage.removeItem("user");
          window.location.reload();
        });
      } else {
        navMenu.innerHTML = `
        <a href="/login.html" class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-6 py-2 rounded-full hover:shadow-lg transition">Login</a>
      `;
      }
    });
  </script>
</body>

</html>
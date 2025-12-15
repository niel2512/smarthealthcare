<?php
session_start();

// Jika belum login maka redirect ke login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$name = $_SESSION['user_name'];

include 'db.php'; // koneksi database

// Ambil data Dokter berdasarkan role = dokter
$query = "SELECT id, name FROM users WHERE role = 'dokter'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Medly - Solusi Kesehatan Digital</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 30px;
    }

    .doctor-card {
      background: #f3f3f3;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 6px;
      width: 300px;
    }

    .btn {
      display: inline-block;
      padding: 8px 12px;
      background: #007BFF;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
    }

    .btn:hover {
      background: #0056b3;
    }
  </style>

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Medly - Solusi Kesehatan Digital</title>
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
</head>

<body class="bg-gradient-to-br from-emerald-100 via-teal-100 to-cyan-100 min-h-screen text-gray-800">

  <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-200">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
          </div>
          <span class="text-xl font-bold gradient-text">Medly</span>
        </div>

        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
          <a href="dashboard.php" class="hover:text-emerald-500 transition">Home</a>
          <a href="#" class="hover:text-emerald-500 transition">About Us</a>
          <a href="#" class="text-emerald-600 font-bold">Cek Jadwal</a>
          <a href="#" class="hover:text-emerald-500 transition">Lokasi</a>

          <a href="logout.php" class="bg-gradient-to-r from-cyan-400 to-emerald-400 text-white px-6 py-2.5 rounded-full font-semibold shadow-md hover:shadow-lg hover:scale-105 transition transform duration-200">
            Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <main class="max-w-7xl mx-auto px-6 py-10">

    <div class="mb-8">
      <a href="dashboard.php" class="inline-flex items-center gap-2 text-gray-700 font-semibold hover:text-emerald-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Home
      </a>
    </div>

    <div class="mb-10">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Jadwal Ketersediaan Dokter</h1>
      <p class="text-gray-600 text-lg">Pilih dokter dari jadwal yang sesuai untuk konsultasi anda</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-white/50">
            <div class="flex items-start gap-4 mb-6">
              <div class="w-16 h-16 rounded-full bg-emerald-400 flex items-center justify-center flex-shrink-0 text-white shadow-emerald-200 shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>

              <div>
                <h3 class="font-bold text-xl text-gray-900"><?= htmlspecialchars($row['name']); ?></h3>
                <p class="text-emerald-500 font-medium text-sm mb-1">Dokter Umum</p>
                <p class="text-gray-500 text-sm mb-2">Biaya : Rp. 100.000</p>

                <span class="inline-block bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full font-medium">
                  Shift : Pagi (07.00-10.00)
                </span>
              </div>
            </div>

            <a href="booking.php?doctor_id=<?= $row['id']; ?>" class="block w-full text-center bg-gradient-to-r from-cyan-400 to-emerald-400 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg hover:from-cyan-500 hover:to-emerald-500 transition transform hover:-translate-y-0.5">
              Lihat Jadwal & Booking
            </a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-span-3 text-center py-10">
          <p class="text-gray-500 text-lg">Tidak ada dokter tersedia saat ini.</p>
        </div>
      <?php endif; ?>

    </div>
  </main>

</body>

</html>
<?php
session_start();
include 'db.php';

// Cek Login User
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$user_id = $_SESSION['user_id'];

// QUERY: Ambil booking yang statusnya 'completed' (Sudah diperiksa)
$query = "SELECT b.*, u.name as doctor_name 
          FROM bookings b 
          JOIN users u ON b.doctor_id = u.id 
          WHERE b.user_id = ? AND b.status = 'completed' 
          ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

function tgl_indo($tanggal)
{
  $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
  $pecahkan = explode('-', $tanggal);
  return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rekam Medis - Medly</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-[#bce3d4] min-h-screen">

  <nav class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-200">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
          </div>
          <span class="text-xl font-bold bg-gradient-to-br from-emerald-500 to-cyan-500 text-transparent bg-clip-text">Medly</span>
        </div>

        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
          <a href="index.php" class="hover:text-emerald-500 transition">Home</a>
          <a href="riwayat.php" class="flex items-center gap-1 text-gray-600 hover:text-emerald-500">
            Riwayat Medis
          </a>

          <a href="logout.php" class="bg-gradient-to-r from-cyan-400 to-emerald-400 text-white px-6 py-2.5 rounded-full font-semibold shadow-md hover:shadow-lg hover:scale-105 transition transform duration-200">
            Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Riwayat & Rekam Medis</h1>

    <?php if ($result->num_rows > 0): ?>
      <div class="space-y-6">
        <?php while ($row = $result->fetch_assoc()): ?>

          <div class="bg-white rounded-3xl p-8 shadow-lg border-2 border-white hover:border-emerald-200 transition">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 pb-4 mb-4">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                  </svg>
                </div>
                <div>
                  <h2 class="font-bold text-xl text-gray-900"><?= htmlspecialchars($row['doctor_name']) ?></h2>
                  <p class="text-gray-500 text-sm"><?= tgl_indo($row['booking_date']) ?> • <?= date('H:i', strtotime($row['booking_time'])) ?></p>
                </div>
              </div>
              <span class="mt-2 md:mt-0 bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                Selesai Konsultasi
              </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

              <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Keluhan Anda</h3>
                <div class="bg-gray-50 p-4 rounded-xl text-gray-700 italic border border-gray-100">
                  "<?= htmlspecialchars($row['complaint']) ?>"
                </div>
              </div>

              <div class="space-y-4">

                <div>
                  <h3 class="text-sm font-bold text-emerald-600 uppercase tracking-wider mb-2">Catatan Dokter</h3>
                  <p class="text-gray-800 font-medium leading-relaxed">
                    <?= nl2br(htmlspecialchars($row['doctor_notes'])) ?>
                  </p>
                </div>

                <div class="bg-emerald-50/50 p-4 rounded-xl border border-emerald-100">
                  <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <h3 class="text-sm font-bold text-emerald-700">Resep Obat</h3>
                  </div>
                  <p class="text-emerald-900 font-semibold">
                    <?= nl2br(htmlspecialchars($row['medicine'])) ?>
                  </p>
                </div>

              </div>
            </div>

          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="text-center py-20 bg-white/50 rounded-3xl">
        <div class="mb-4 inline-flex p-4 bg-white rounded-full shadow-sm">
          <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-600">Belum ada riwayat medis</h3>
        <p class="text-gray-500 mt-2">Anda belum memiliki konsultasi yang selesai.</p>
        <a href="pilih-dokter.php" class="inline-block mt-6 bg-emerald-500 text-white px-6 py-2 rounded-full font-bold hover:bg-emerald-600 transition">Mulai Konsultasi</a>
      </div>
    <?php endif; ?>

  </div>
</body>

</html>
<?php
session_start();
include 'db.php';

// Cek apakah user adalah dokter
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dokter') {
  header("Location: login.html");
  exit;
}

$doctor_id = $_SESSION['user_id'];
$doctor_name = $_SESSION['user_name'];

// --- QUERY DATA UNTUK KARTU ---

// 1. Pasien Hari Ini (Confirmed & Tanggal Hari Ini)
$query_today = "SELECT COUNT(*) as total FROM bookings WHERE doctor_id = ? AND booking_date = CURDATE() AND status = 'confirmed'";
$stmt = $conn->prepare($query_today);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$count_today = $stmt->get_result()->fetch_assoc()['total'];

// 2. Menunggu Konfirmasi (Pending)
$query_pending = "SELECT COUNT(*) as total FROM bookings WHERE doctor_id = ? AND status = 'pending'";
$stmt = $conn->prepare($query_pending);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$count_pending = $stmt->get_result()->fetch_assoc()['total'];

// 3. Selesai Diperiksa (Completed)
$query_done = "SELECT COUNT(*) as total FROM bookings WHERE doctor_id = ? AND status = 'completed'";
$stmt = $conn->prepare($query_done);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$count_done = $stmt->get_result()->fetch_assoc()['total'];

// 4. Total Pasien Unik
$query_patients = "SELECT COUNT(DISTINCT user_id) as total FROM bookings WHERE doctor_id = ?";
$stmt = $conn->prepare($query_patients);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$count_patients = $stmt->get_result()->fetch_assoc()['total'];

// --- QUERY UNTUK TABEL (Jadwal Hari Ini) ---
$query_list = "SELECT b.id, b.booking_time, b.status, b.complaint, u.name as patient_name 
               FROM bookings b 
               JOIN users u ON b.user_id = u.id 
               WHERE b.doctor_id = ? AND b.booking_date = CURDATE() 
               ORDER BY b.booking_time ASC";
$stmt = $conn->prepare($query_list);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result_list = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Dokter - Smart Health</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 flex">

  <aside class="w-64 bg-white h-screen border-r border-gray-200 hidden md:block fixed">
    <div class="pt-6 justify-between">
      <div class="flex items-center pl-3 gap-2">
        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-200">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
          </svg>
        </div>
        <span class="text-xl font-bold bg-gradient-to-br from-emerald-500 to-cyan-500 text-transparent bg-clip-text">Smart Health Care</span>
      </div>
    </div>
    <br>
    <hr>
    <nav class="mt-6 px-4 space-y-2">
      <a href="#" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-600 rounded-lg font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
        </svg>
        Dashboard
      </a>
      <a href="atur-jadwal.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium hover:text-emerald-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Atur Jadwal
      </a>
      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg font-medium mt-10">
        Logout
      </a>
      <!-- <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        Data Pasien
      </a> -->
    </nav>
  </aside>

  <main class="flex-1 ml-0 md:ml-64 p-8">

    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Dokter</h1>
        <p class="text-gray-500">Selamat datang kembali, dr. <?= htmlspecialchars($doctor_name); ?>! 👋</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

      <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
        </div>
        <div>
          <h3 class="text-gray-500 text-sm font-medium">Pasien Hari Ini</h3>
          <p class="text-2xl font-bold text-gray-900"><?= $count_today ?></p>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <h3 class="text-gray-500 text-sm font-medium">Perlu Konfirmasi</h3>
          <p class="text-2xl font-bold text-gray-900"><?= $count_pending ?></p>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <div>
          <h3 class="text-gray-500 text-sm font-medium">Selesai Diperiksa</h3>
          <p class="text-2xl font-bold text-gray-900"><?= $count_done ?></p>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <div>
          <h3 class="text-gray-500 text-sm font-medium">Total Pasien</h3>
          <p class="text-2xl font-bold text-gray-900"><?= $count_patients ?></p>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-bold text-gray-800">Jadwal Konsultasi Hari Ini</h2>
          <p class="text-sm text-gray-500">Berikut daftar pasien yang terdaftar untuk hari ini.</p>
        </div>
        <div class="text-sm bg-emerald-50 text-emerald-600 px-4 py-2 rounded-lg font-medium">
          <?= date('l, d F Y'); ?>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="text-gray-400 text-sm border-b border-gray-100">
              <th class="py-4 font-medium">Jam</th>
              <th class="py-4 font-medium">Nama Pasien</th>
              <th class="py-4 font-medium">Keluhan</th>
              <th class="py-4 font-medium">Status</th>
              <th class="py-4 font-medium text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php if ($result_list->num_rows > 0): ?>
              <?php while ($row = $result_list->fetch_assoc()): ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                  <td class="py-4 font-bold text-emerald-600">
                    <?= date('H:i', strtotime($row['booking_time'])); ?>
                  </td>
                  <td class="py-4 font-medium">
                    <?= htmlspecialchars($row['patient_name']); ?>
                  </td>
                  <td class="py-4 text-sm text-gray-600 max-w-xs truncate">
                    <?= htmlspecialchars($row['complaint']); ?>
                  </td>
                  <td class="py-4">
                    <?php
                    // Badge Status Warna-warni
                    $statusClass = 'bg-gray-100 text-gray-600';
                    if ($row['status'] == 'confirmed') $statusClass = 'bg-green-100 text-green-600';
                    if ($row['status'] == 'pending') $statusClass = 'bg-orange-100 text-orange-600';
                    if ($row['status'] == 'completed') $statusClass = 'bg-blue-100 text-blue-600';
                    ?>
                    <span class="<?= $statusClass ?> px-3 py-1 rounded-full text-xs font-bold capitalize">
                      <?= $row['status'] ?>
                    </span>
                  </td>
                  <td class="py-4 text-right">
                    <?php if ($row['status'] == 'pending'): ?>
                      <a href="approve.php?id=<?= $row['id'] ?>" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold mr-2">Terima</a>
                    <?php elseif ($row['status'] == 'confirmed'): ?>
                      <a href="periksa.php?id=<?= $row['id'] ?>" class="bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-600 shadow-md transition">Periksa</a>
                    <?php else: ?>
                      <span class="text-gray-400 text-sm">Selesai</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="py-8 text-center text-gray-400">
                  Belum ada jadwal konsultasi hari ini.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>

</body>

</html>
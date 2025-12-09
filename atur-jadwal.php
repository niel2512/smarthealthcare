<?php
session_start();
include 'db.php';

// 1. Cek Login & Role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dokter') {
  header("Location: login.html");
  exit;
}

$doctor_id = $_SESSION['user_id'];
$message = "";

// 2. PROSES SIMPAN DATA (Jika tombol Simpan ditekan)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tanggal = $_POST['tanggal'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];
  $jumlah_slot = $_POST['jumlah_slot'];

  // Validasi sederhana
  if ($jam_selesai <= $jam_mulai) {
    $message = "<div class='bg-red-100 text-red-600 p-4 rounded mb-4'>Jam Selesai harus lebih besar dari Jam Mulai!</div>";
  } else {
    // Query Insert
    $stmt = $conn->prepare("INSERT INTO doctor_schedules (doctor_id, day_date, start_time, end_time, max_slots) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $doctor_id, $tanggal, $jam_mulai, $jam_selesai, $jumlah_slot);

    if ($stmt->execute()) {
      $message = "<div class='bg-emerald-100 text-emerald-600 p-4 rounded mb-4'>Jadwal berhasil ditambahkan!</div>";
    } else {
      $message = "<div class='bg-red-100 text-red-600 p-4 rounded mb-4'>Gagal menyimpan: " . $conn->error . "</div>";
    }
  }
}

// 3. AMBIL DATA JADWAL SAYA (Untuk ditampilkan di tabel bawah)
$query = "SELECT * FROM doctor_schedules WHERE doctor_id = ? AND day_date >= CURDATE() ORDER BY day_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

// Helper hari indo
function getHariIndo($date)
{
  $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
  return $days[date('l', strtotime($date))];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Atur Jadwal Praktik</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 flex min-h-screen">

  <aside class="w-64 bg-white border-r border-gray-200 hidden md:block fixed h-full">
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
      <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
        </svg>
        Dashboard
      </a>
      <a href="atur-jadwal.php" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-600 rounded-lg font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Atur Jadwal
      </a>
      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg font-medium mt-10">
        Logout
      </a>
    </nav>
  </aside>

  <main class="flex-1 ml-0 md:ml-64 p-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Atur Jadwal Praktik</h1>

    <?= $message; ?>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 mb-8">
      <h2 class="text-lg font-bold mb-4">Tambah Jadwal Baru</h2>
      <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
          <input type="date" name="tanggal" required min="<?= date('Y-m-d'); ?>"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
          <input type="time" name="jam_mulai" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
          <input type="time" name="jam_selesai" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pasien</label>
          <input type="number" name="jumlah_slot" required min="1" placeholder="Cth: 10" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-emerald-500">
        </div>

        <div>
          <button type="submit" class="w-full bg-emerald-500 text-white font-bold py-2.5 rounded-lg hover:bg-emerald-600 transition shadow-md">
            + Simpan Jadwal
          </button>
        </div>
      </form>
      <p class="text-xs text-gray-500 mt-3">*Sistem akan otomatis membagi jadwal menjadi slot per 30 menit pada tampilan user.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h3 class="font-bold text-gray-700">Jadwal Aktif Saya</h3>
      </div>
      <table class="w-full text-left">
        <thead class="bg-gray-50 text-gray-500 text-sm">
          <tr>
            <th class="px-6 py-3">Hari / Tanggal</th>
            <th class="px-6 py-3">Jam Praktik</th>
            <th class="px-6 py-3">Slot Tersedia</th>
            <th class="px-6 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php while ($row = $result->fetch_assoc()): ?>
            <td class="px-6 py-4">
              <span class="font-bold text-gray-800"><?= getHariIndo($row['day_date']); ?></span>,
              <?= date('d M Y', strtotime($row['day_date'])); ?>
            </td>
            <td class="px-6 py-4 text-emerald-600 font-medium">
              <?= date('H:i', strtotime($row['start_time'])); ?> - <?= date('H:i', strtotime($row['end_time'])); ?>
            </td>
            <td class="px-6 py-4">
              <span class="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full font-bold">
                <?= $row['max_slots'] ?> Pasien
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <a href="hapus-jadwal.php?id=<?= $row['id'] ?>" class="text-red-500 hover:text-red-700 text-sm font-medium" onclick="return confirm('Yakin hapus jadwal ini?')">Hapus</a>
            </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>

</html>
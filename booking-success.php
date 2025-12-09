<?php
session_start();
include 'db.php';

// Ambil ID Booking dari URL
$booking_id = $_GET['id'];

// Ambil Detail Booking untuk ditampilkan
$query = "SELECT b.*, u.name as doctor_name 
          FROM bookings b 
          JOIN users u ON b.doctor_id = u.id 
          WHERE b.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

// Fungsi Format Tanggal
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
  <title>Booking Berhasil</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-[#bce3d4] min-h-screen flex flex-col items-center justify-center text-center px-4">

  <h1 class="text-3xl font-bold mb-8 text-black">Booking Berhasil!</h1>

  <div class="bg-white rounded-[40px] p-10 shadow-2xl w-full max-w-lg">
    <h2 class="font-bold text-xl mb-6 text-left border-b pb-4">Ringkasan Booking</h2>

    <div class="space-y-4 text-left">
      <div class="flex justify-between">
        <span class="text-gray-600 font-medium">Dokter :</span>
        <span class="font-bold text-gray-900"><?= htmlspecialchars($booking['doctor_name']) ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-600 font-medium">Tanggal :</span>
        <span class="font-bold text-gray-900"><?= tgl_indo($booking['booking_date']) ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-600 font-medium">Waktu :</span>
        <span class="font-bold text-gray-900"><?= date('H:i', strtotime($booking['booking_time'])) ?></span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-600 font-medium">Biaya :</span>
        <span class="font-bold text-gray-900">Rp. 100.000</span>
      </div>

      <div class="pt-4 border-t mt-4">
        <span class="text-gray-600 font-medium block mb-1">Catatan/Keluhan:</span>
        <p class="text-gray-800 bg-gray-50 p-3 rounded-lg text-sm italic">
          "<?= htmlspecialchars($booking['complaint']) ?>"
        </p>
      </div>
    </div>
  </div>

  <p class="text-gray-700 text-xs mt-6 mb-8 font-medium">
    Mohon datang 15 menit sebelum jadwal konsultasi. Simpan bukti booking ini.
  </p>

  <div class="flex gap-4">
    <a href="pilih-dokter.php" class="bg-[#00ff88] hover:bg-emerald-500 text-white font-bold py-3 px-8 rounded-full shadow-lg transition">
      Booking Lagi
    </a>
    <a href="dashboard.php" class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-3 px-8 rounded-full shadow-lg border border-gray-200 transition">
      Kembali ke Home
    </a>
  </div>

</body>

</html>
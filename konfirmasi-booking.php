<?php
session_start();
include 'db.php';

// Pastikan ada data yang dikirim dari booking.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: dashboard.php");
  exit;
}

$doctor_id = $_POST['doctor_id'];
$schedule_id = $_POST['schedule_id'];
$booking_time = $_POST['booking_time'];

// Ambil Data Dokter & Tanggal Jadwal untuk ditampilkan di Ringkasan
$query = "SELECT u.name as doctor_name, ds.day_date 
          FROM users u 
          JOIN doctor_schedules ds ON u.id = ds.doctor_id 
          WHERE u.id = ? AND ds.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $doctor_id, $schedule_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

// Format Tanggal Indonesia
function tgl_indo($tanggal)
{
  $bulan = array(
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  $pecahkan = explode('-', $tanggal);
  return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Booking</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-[#bce3d4] min-h-screen flex items-center justify-center py-10">

  <div class="w-full max-w-2xl px-4">
    <div class="bg-white rounded-3xl p-8 shadow-xl border-2 border-emerald-500/20">
      <h1 class="text-2xl font-bold mb-6 text-gray-800">Informasi Booking</h1>

      <form action="proses-booking.php" method="POST">
        <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
        <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">
        <input type="hidden" name="booking_time" value="<?= $booking_time ?>">
        <input type="hidden" name="booking_date" value="<?= $result['day_date'] ?>">

        <div class="mb-6">
          <label class="block text-gray-700 font-bold mb-2">Keluhan / Catatan</label>
          <textarea name="complaint" rows="4" required
            class="w-full border-2 border-gray-800 rounded-xl p-4 focus:outline-none focus:border-emerald-500 text-gray-700 placeholder-gray-400"
            placeholder="Tuliskan keluhan yang anda rasakan..."></textarea>
        </div>

        <div class="border-2 border-gray-800 rounded-xl p-6 mb-8 relative">
          <h3 class="font-bold text-lg mb-4">Ringkasan Booking</h3>

          <div class="text-right">
            <p class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($result['doctor_name']) ?></p>
            <p class="text-gray-600 text-sm mt-1">
              <?= tgl_indo($result['day_date']) ?>
            </p>
            <p class="text-gray-800 font-bold text-2xl mt-1"><?= date('H:i', strtotime($booking_time)) ?></p>
            <p class="text-gray-600 font-medium mt-1">Rp. 100.000</p>
          </div>
        </div>

        <button type="submit" class="w-full bg-[#00ff88] hover:bg-emerald-500 text-white font-bold py-4 rounded-full text-lg shadow-lg transition transform hover:-translate-y-1">
          Konfirmasi Booking
        </button>
      </form>
    </div>

    <div class="mt-6 text-center">
      <a href="booking.php?doctor_id=<?= $doctor_id ?>" class="text-gray-700 font-semibold hover:underline">← Kembali</a>
    </div>
  </div>

</body>

</html>
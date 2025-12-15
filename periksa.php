<?php
session_start();
include 'db.php';

// Cek Dokter
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dokter') {
  header("Location: login.html");
  exit;
}

$booking_id = $_GET['id'];
$message = "";

// PROSES SIMPAN DIAGNOSA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $notes = $_POST['notes'];
  $medicine = $_POST['medicine'];

  // Update data, dan ubah status menjadi 'completed'
  $stmt = $conn->prepare("UPDATE bookings SET doctor_notes = ?, medicine = ?, status = 'completed' WHERE id = ?");
  $stmt->bind_param("ssi", $notes, $medicine, $booking_id);

  if ($stmt->execute()) {
    echo "<script>alert('Rekam medis berhasil disimpan!'); window.location.href='dashboard.php';</script>";
    exit;
  }
}

// Ambil Data Pasien
$query = "SELECT b.*, u.name as patient_name FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Periksa Pasien</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 py-10 px-4">
  <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm">
    <h1 class="text-2xl font-bold mb-6">Pemeriksaan Medis</h1>

    <div class="bg-blue-50 p-4 rounded-xl mb-6">
      <p class="text-gray-600 text-sm">Pasien:</p>
      <p class="font-bold text-lg text-gray-800"><?= htmlspecialchars($data['patient_name']) ?></p>
      <p class="text-gray-600 text-sm mt-2">Keluhan:</p>
      <p class="italic text-gray-800">"<?= htmlspecialchars($data['complaint']) ?>"</p>
    </div>

    <form method="POST">
      <div class="mb-4">
        <label class="block font-bold mb-2">Catatan Dokter / Diagnosa</label>
        <textarea name="notes" rows="4" class="w-full border p-3 rounded-lg" required placeholder="Hasil pemeriksaan..."></textarea>
      </div>

      <div class="mb-6">
        <label class="block font-bold mb-2">Resep Obat</label>
        <textarea name="medicine" rows="3" class="w-full border p-3 rounded-lg" required placeholder="Paracetamol 3x1..."></textarea>
      </div>

      <button type="submit" class="w-full bg-emerald-500 text-white font-bold py-3 rounded-lg hover:bg-emerald-600">
        Simpan & Selesaikan Sesi
      </button>
    </form>
  </div>
</body>

</html>
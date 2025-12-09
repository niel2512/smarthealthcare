<?php
session_start();
include 'db.php';

// 1. Cek Login & Role (Keamanan Dasar)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dokter') {
  header("Location: login.html");
  exit;
}

// 2. Cek apakah ada ID yang dikirim
if (!isset($_GET['id'])) {
  header("Location: atur-jadwal.php");
  exit;
}

$schedule_id = $_GET['id'];
$doctor_id = $_SESSION['user_id'];

// 3. Hapus Data (Dengan validasi kepemilikan)
// Klausa "AND doctor_id = ?" memastikan dokter hanya bisa menghapus jadwal miliknya sendiri
$stmt = $conn->prepare("DELETE FROM doctor_schedules WHERE id = ? AND doctor_id = ?");
$stmt->bind_param("ii", $schedule_id, $doctor_id);

if ($stmt->execute()) {
  // Jika berhasil, kembali ke atur-jadwal
  // Kita bisa mengirim parameter msg untuk menampilkan alert (opsional)
  echo "<script>alert('Jadwal berhasil dihapus!'); window.location.href='atur-jadwal.php';</script>";
} else {
  echo "<script>alert('Gagal menghapus jadwal.'); window.location.href='atur-jadwal.php';</script>";
}

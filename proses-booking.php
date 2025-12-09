<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 1. Ambil Data dari Form Konfirmasi
  $user_id = $_SESSION['user_id']; // Dari Login
  $doctor_id = $_POST['doctor_id'];
  $schedule_id = $_POST['schedule_id'];
  $booking_date = $_POST['booking_date'];
  $booking_time = $_POST['booking_time'];
  $complaint = $_POST['complaint']; // Data Baru

  // 2. Query Insert Database
  // Status default 'pending' -> Dokter harus konfirmasi nanti di dashboard
  $stmt = $conn->prepare("INSERT INTO bookings (user_id, doctor_id, schedule_id, booking_date, booking_time, status, complaint) VALUES (?, ?, ?, ?, ?, 'pending', ?)");

  $stmt->bind_param("iissss", $user_id, $doctor_id, $schedule_id, $booking_date, $booking_time, $complaint);

  if ($stmt->execute()) {
    // Ambil ID booking yang baru dibuat untuk ditampilkan di halaman sukses
    $new_booking_id = $stmt->insert_id;

    // Redirect ke halaman Sukses
    header("Location: booking-success.php?id=" . $new_booking_id);
    exit;
  } else {
    echo "Gagal menyimpan: " . $conn->error;
  }
} else {
  header("Location: dashboard.php");
}

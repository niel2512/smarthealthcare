<?php
session_start();
include 'db.php';

// Cek Login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

// Ambil ID Dokter dari URL
$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;

// 1. Ambil Data Dokter
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'dokter'");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$doctor = $stmt->get_result()->fetch_assoc();

if (!$doctor) {
  echo "<script>alert('Dokter tidak ditemukan!'); window.location.href='list-dokter.php';</script>";
  exit;
}

// 2. Ambil Jadwal Dokter (Tanggal yang tersedia)
$query_schedules = "SELECT * FROM doctor_schedules WHERE doctor_id = ? AND day_date >= CURDATE() ORDER BY day_date ASC LIMIT 3";
$stmt_sched = $conn->prepare($query_schedules);
$stmt_sched->bind_param("i", $doctor_id);
$stmt_sched->execute();
$schedules_result = $stmt_sched->get_result();

// Simpan jadwal ke array untuk diproses
$schedules = [];
while ($row = $schedules_result->fetch_assoc()) {
  $schedules[] = $row;
}

// Helper function untuk nama hari Indonesia
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
  <title>Booking Jadwal - Smart Health Care</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#10b981",
            secondary: "#06b6d4",
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          }
        },
      },
    };
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    /* Hide scrollbar for clean look */
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }

    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
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
          <span class="text-xl font-bold bg-gradient-to-br from-emerald-500 to-cyan-500 text-transparent bg-clip-text">Smart Health Care</span>
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

  <div class="max-w-5xl mx-auto px-4 pt-10 pb-20">
    <div class="mb-4">
      <a href="list-dokter.php" class="inline-flex items-center gap-2 text-gray-700 font-semibold hover:text-emerald-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali
      </a>
    </div>

    <div class="flex items-start gap-4 mb-8">
      <div class="w-20 h-20 bg-[#00ff88] rounded-full flex items-center justify-center border-4 border-[#bce3d4] shadow-sm">
        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>

      <div class="mt-2">
        <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($doctor['name']); ?></h1>
        <p class="text-emerald-500 text-sm font-medium">Ahli Sihir</p>
        <p class="text-gray-600 text-xs mt-1">Biaya : Rp. 100.000</p>
        <div class="mt-2 bg-white/50 inline-block px-3 py-1 rounded text-[10px] text-gray-600 font-medium">
          Shift : Pagi (07.00-10.00)
        </div>
      </div>
    </div>

    <div class="bg-white rounded-[40px] p-8 shadow-xl min-h-[500px]">

      <h2 class="text-2xl font-bold mb-6">Pilih Tanggal</h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
        <?php if (count($schedules) > 0): ?>
          <?php foreach ($schedules as $index => $sched):
            $dateObj = new DateTime($sched['day_date']);
            $isActive = $index === 0; // Default pilih hari pertama
            $borderColor = $isActive ? 'border-black ring-1 ring-black' : 'border-gray-200';
          ?>
            <div onclick="selectDate(this, '<?= $sched['id'] ?>')"
              class="date-card cursor-pointer border-2 <?= $borderColor ?> rounded-2xl p-6 flex flex-col items-center justify-center hover:border-gray-400 transition h-40 bg-white">
              <span class="text-sm font-bold text-gray-800"><?= getHariIndo($sched['day_date']) ?></span>
              <span class="text-4xl font-bold text-gray-900 my-1"><?= $dateObj->format('d') ?></span>
              <span class="text-xs text-gray-500 uppercase"><?= $dateObj->format('M') ?></span>

              <?php if ($sched['day_date'] == date('Y-m-d')): ?>
                <span class="text-[10px] text-emerald-500 font-bold mt-2">Hari Ini</span>
              <?php endif; ?>

              <?php
              $start = strtotime($sched['start_time']);
              $end = strtotime($sched['end_time']);
              $diff = ($end - $start) / 1800; // Dibagi 30 menit (1800 detik)
              ?>
              <span class="text-[10px] text-emerald-400 mt-1"><?= floor($diff) ?> Slot</span>
            </div>

            <?php if ($isActive): ?>
              <input type="hidden" id="selectedScheduleId" value="<?= $sched['id'] ?>">
              <input type="hidden" id="startTime" value="<?= $sched['start_time'] ?>">
              <input type="hidden" id="endTime" value="<?= $sched['end_time'] ?>">
            <?php endif; ?>

          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-red-500">Jadwal dokter belum tersedia.</p>
        <?php endif; ?>
      </div>

      <h2 class="text-2xl font-bold mb-6">Pilih Waktu</h2>

      <form action="proses-booking.php" method="POST">
        <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
        <input type="hidden" name="schedule_id" id="formScheduleId" value="<?= isset($schedules[0]['id']) ? $schedules[0]['id'] : '' ?>">

        <div id="timeSlotContainer" class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-8">
        </div>

        <button type="submit" id="btnBooking" class="hidden w-full bg-emerald-500 text-white font-bold py-4 rounded-xl hover:bg-emerald-600 transition">
          Konfirmasi Booking
        </button>
      </form>

    </div>
  </div>

  <script>
    // Data Jadwal dari PHP ke JS
    const schedules = <?= json_encode($schedules); ?>;

    function selectDate(element, scheduleId) {
      // 1. Ubah Style Card (Visual Selection)
      document.querySelectorAll('.date-card').forEach(el => {
        el.classList.remove('border-black', 'ring-1', 'ring-black');
        el.classList.add('border-gray-200');
      });
      element.classList.remove('border-gray-200');
      element.classList.add('border-black', 'ring-1', 'ring-black');

      // 2. Update Input Hidden
      document.getElementById('formScheduleId').value = scheduleId;

      // 3. Generate Ulang Slot Waktu
      const selectedSched = schedules.find(s => s.id == scheduleId);
      if (selectedSched) {
        generateTimeSlots(selectedSched.start_time, selectedSched.end_time);
      }
    }

    function generateTimeSlots(start, end) {
      const container = document.getElementById('timeSlotContainer');
      container.innerHTML = ''; // Kosongkan dulu

      let startTime = new Date("2000-01-01 " + start);
      let endTime = new Date("2000-01-01 " + end);

      // Loop setiap 30 menit
      while (startTime < endTime) {
        let timeString = startTime.toTimeString().substring(0, 5); // Ambil HH:MM

        // Buat Elemen Radio Button tapi di-styling seperti tombol
        let label = document.createElement('label');
        label.className = "cursor-pointer";
        label.innerHTML = `
                    <input type="radio" name="booking_time" value="${timeString}" class="peer hidden" onchange="showButton()">
                    <div class="border-2 border-black rounded-full py-3 text-center font-bold text-lg hover:bg-gray-50 peer-checked:bg-black peer-checked:text-white transition">
                        ${timeString}
                    </div>
                `;
        container.appendChild(label);

        // Tambah 30 menit
        startTime.setMinutes(startTime.getMinutes() + 30);
      }
    }

    function showButton() {
      document.getElementById('btnBooking').classList.remove('hidden');
    }

    // Initialize pertama kali load page (Jadwal pertama)
    if (schedules.length > 0) {
      generateTimeSlots(schedules[0].start_time, schedules[0].end_time);
    }
  </script>
</body>

</html>
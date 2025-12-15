<?php
session_start();

// Jika belum login maka redirect ke login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="id">

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

<body class="bg-gradient-to-br from-emerald-50 via-white to-cyan-50 scroll-smooth">
  <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
          </div>
          <span class="text-xl font-bold gradient-text">Medly</span>
        </div>
        <div class="md:flex items-center gap-8">
          <h1 class="text-xl">
            Selamat datang, <span class="font-bold"><?= htmlspecialchars($name) ?>! 👋</span>
          </h1>
          <a class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-6 py-2 rounded-full hover:shadow-lg transition" href="logout.php">Logout</a>

          <!-- <a href="/login.html" class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-6 py-2 rounded-full hover:shadow-lg transition">Login</a> -->
        </div>
        <button class="md:hidden text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>
  </nav>
  <div class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-xl shadow-md">
    <a href="jadwal.php" class="text-emerald-600 text-sm block mb-2">← Kembali ke Home</a>
    <h2 class="text-lg font-bold mb-4">Pilih Tanggal</h2>


    <div class="grid grid-cols-3 gap-4">
      <button onclick="selectDate('2025-11-10')" class="border p-4 rounded-lg hover:bg-emerald-200">
        <p class="font-semibold text-center">Senin</p>
        <p class="text-3xl font-bold text-center">10</p>
        <p class="text-center text-xs text-emerald-600">Hari Ini</p>
        <p class="text-center text-xs text-gray-500">12 Slot</p>
      </button>
    </div>

    <h2 class="text-lg font-bold mt-6 mb-2">Pilih Waktu</h2>

    <div class="grid grid-cols-4 gap-3">
      <?php
      $hours = ["07:00", "08:00", "09:00", "10:00", "11:00", "13:00", "14:00", "15:00"];
      foreach ($hours as $h) {
        echo "<button onclick=\"selectTime('$h')\" class='border rounded-lg py-3 hover:bg-emerald-200'>$h</button>";
      }
      ?>
    </div>

    <input type="hidden" id="selectedDate">
    <input type="hidden" id="selectedTime">

    <button onclick="nextStep()"
      class="w-full mt-6 bg-emerald-500 text-white py-3 rounded-lg font-semibold hover:opacity-90">
      Lanjutkan
    </button>


  </div>

  <script>
    function selectDate(date) {
      document.getElementById("selectedDate").value = date;
    }

    function selectTime(time) {
      document.getElementById("selectedTime").value = time;
    }

    function nextStep() {
      const d = document.getElementById("selectedDate").value;
      const t = document.getElementById("selectedTime").value;
      if (!d || !t) return alert("Silahkan pilih tanggal & waktu");

      window.location.href = `konfirmasi.php?tanggal=${d}&waktu=${t}`;
    }
  </script>
</body>

</html>
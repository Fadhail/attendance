<?php
date_default_timezone_set("Asia/Jakarta");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);
    if ($attendanceData) {
        try {
            $sql = "INSERT INTO presensi (npm, id_fakultas, id_kelas, status, created_at)  
                VALUES (:npm, :id_fakultas, :id_kelas, :status, :date)";

            $stmt = $pdo->prepare($sql);

            foreach ($attendanceData as $data) {
                $npm = $data['npm'];
                $id_fakultas = $data['id_fakultas'];
                $id_kelas = $data['id_kelas'];
                $status = $data['status'];
                $date = date("Y-m-d H:i:s");

                $stmt->execute([
                    ':npm' => $npm,
                    ':id_fakultas' => $id_fakultas,
                    ':id_kelas' => $id_kelas,
                    ':status' => $status,
                    ':date' => $date
                ]);
            }

            $_SESSION['message'] = "Kehadiran berhasil dicatat";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Gagal mencatat kehadiran: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Tidak ada data kehadiran yang diterima";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi</title>
    <script src="resources/assets/javascript/presensi.js" defer></script>
    <script src="resources/assets/javascript/face-api.min.js" defer></script>
    <script defer src="resources/assets/javascript/face-api.min.js"></script>
    <script defer src="resources/assets/javascript/presensi.js"></script>
</head>

<body>
    <div class="p-4 sm:ml-64">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
            <section class="main">
                <?php include 'includes/sidebar.php'; ?>
                <div class="main--content">
                    <div id="messageDiv" class="messageDiv hidden text-center text-green-600 font-bold"> </div>
                    <p class="text-xl font-semibold text-center text-blue-600 mt-4">Select Faculty and Class before starting Presence</p>
                    <form class="lecture-options flex justify-center gap-4 mt-6" id="selectForm">
                        <select required name="fakultas" id="fakultas" class="border border-gray-300 rounded-md p-2"
                            onChange="updateTable()">
                            <option value="" selected>Select Faculty</option>
                            <?php
                            $namaFakultas = getFakultas();
                            foreach ($namaFakultas as $fakultas) {
                                echo '<option value="' . $fakultas["id_fakultas"] . '">' . $fakultas["nama_fakultas"] . '</option>';
                            }
                            ?>
                        </select>

                        <select required name="kelas" id="kelas" class="border border-gray-300 rounded-md p-2"
                            onChange="updateTable()">
                            <option value="" selected>Select Class</option>
                            <?php
                            $namaKelas = getKelas();
                            foreach ($namaKelas as $kelas) {
                                echo '<option value="' . $kelas["id_kelas"] . '">' . $kelas["nama_kelas"] . '</option>';
                            }
                            ?>
                        </select>
                    </form>
                    <div class="attendance-button flex justify-center gap-4 mt-6">
                        <button id="startButton"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 ease-in-out hover:bg-blue-700">Start Presence</button>
                        <button id="endAttendance"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 ease-in-out hover:bg-blue-700">End Presence</button>
                    </div>

                    <!-- Video Container -->
                    <div class="video-container hidden mt-6 flex justify-center">
                        <div>
                            <video id="video" width="600" height="450" autoplay class="block mx-auto z-0"></video>
                            <canvas id="overlay"></canvas>
                        </div>
                    </div>

                    <!-- Table Mahasiswa -->
                    <div id="tabelMahasiswa" class="mt-6"> </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>
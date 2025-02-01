<?php
session_start();
include "../../../database/koneksi.php";

$npm = $firstName = $lastName = $phone_no = $email = $fakultas = $kelas = "";

if (isset($_GET['npm'])) {
    $npm = htmlspecialchars(trim($_GET['npm']));

    try {
        $query = $pdo->prepare("SELECT m.*, f.nama_fakultas, k.nama_kelas   
                                 FROM mahasiswa m   
                                 INNER JOIN fakultas f ON m.id_fakultas = f.id_fakultas   
                                 INNER JOIN kelas k ON m.id_kelas = k.id_kelas   
                                 WHERE m.npm = :npm");
        $query->bindParam(':npm', $npm, PDO::PARAM_STR);
        $query->execute();
        $mahasiswa = $query->fetch(PDO::FETCH_ASSOC);

        if ($mahasiswa) {
            $firstName = $mahasiswa['first_name'];
            $lastName = $mahasiswa['last_name'];
            $phone_no = $mahasiswa['phone_no'];
            $email = $mahasiswa['email'];
            $fakultas = $mahasiswa['id_fakultas'];
            $kelas = $mahasiswa['id_kelas'];
        } else {
            $_SESSION['message'] = "Mahasiswa tidak ditemukan.";
            header("Location: ../../../mahasiswa");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error loading data mahasiswa: " . $e->getMessage();
        header("Location: ../../../mahasiswa");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Mahasiswa</title>
    <link rel="stylesheet" href="../../assets/css/output.css">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Student Detail</h2>

        <div class="flex justify-center mb-6">
            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-300">
                <img src="../../labels/<?= htmlspecialchars($npm); ?>/1_<?= htmlspecialchars($npm); ?>.png"
                    alt="Profile Picture <?= htmlspecialchars($npm); ?>"
                    class="w-full h-full object-cover rounded-full">
            </div>
        </div>

        <div class="space-y-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">NPM:</label>
                <p class="mt-1">
                    <?= htmlspecialchars($npm); ?>
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name:</label>
                <p class="mt-1">
                    <?= htmlspecialchars($firstName . ' ' . $lastName); ?>
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Phone Number:</label>
                <p class="mt-1">
                    <?= htmlspecialchars($phone_no); ?>
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email:</label>
                <p class="mt-1">
                    <?= htmlspecialchars($email); ?>
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Faculty:</label>
                <p class="mt-1">
                    <?= htmlspecialchars($fakultas); ?>
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Class:</label>
                <p class="mt-1">
                    <?= htmlspecialchars($kelas); ?>
                </p>
            </div>

            <div class="flex space-x-4 mt-6">
                <a href="../../../mahasiswa"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</body>

</html>
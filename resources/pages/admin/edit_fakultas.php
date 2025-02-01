<?php
session_start();
include "../../../database/koneksi.php";

$id_fakultas = $nama_fakultas = "";

if (isset($_GET['id'])) {
    $id_fakultas = htmlspecialchars(trim($_GET['id']));

    try {
        // Query untuk mengambil data fakultas berdasarkan id_fakultas    
        $query = $pdo->prepare("SELECT * FROM fakultas WHERE id_fakultas = :id_fakultas");
        $query->bindParam(':id_fakultas', $id_fakultas, PDO::PARAM_INT);
        $query->execute();
        $fakultas = $query->fetch(PDO::FETCH_ASSOC);

        if ($fakultas) {
            $nama_fakultas = $fakultas['nama_fakultas'];
            $id_fakultas = $fakultas['id_fakultas'];
        } else {
            $_SESSION['message'] = "Fakultas tidak ditemukan.";
            header("Location: ../../../manage_fakultas");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error loading data fakultas: " . $e->getMessage();
        header("Location: ../../../manage_fakultas");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["updateFakultas"])) {
    $id_fakultas = htmlspecialchars(trim($_POST["id_fakultas"] ?? ""));
    $nama_fakultas = htmlspecialchars(trim($_POST["nama_fakultas"] ?? ""));

    if (!empty($id_fakultas) && !empty($nama_fakultas)) {
        try {
            $query = $pdo->prepare("UPDATE fakultas       
                                    SET nama_fakultas = :nama_fakultas       
                                    WHERE id_fakultas = :id_fakultas");
            $query->bindParam(':id_fakultas', $id_fakultas, PDO::PARAM_INT);
            $query->bindParam(':nama_fakultas', $nama_fakultas, PDO::PARAM_STR);

            $query->execute();

            $_SESSION['message'] = "Fakultas berhasil diperbarui.";
            header("Location: ../../../manage_fakultas");
            exit();
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
            header("Location: ../../../manage_fakultas");
            exit();
        }
    } else {
        $_SESSION['message'] = "Data tidak lengkap. Mohon lengkapi semua field.";
        header("Location: ../../../manage_fakultas");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty</title>
    <link rel="stylesheet" href="resources/assets/css/output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Edit Faculty</h2>
        <form id="updateFakultas" method="POST" action="" class="space-y-6">

            <div>
                <label for="id_fakultas" class="block text-sm font-medium text-gray-700">Faculty ID</label>
                <input type="text" id="id_fakultas" name="id_fakultas" value="<?= htmlspecialchars($id_fakultas); ?>"
                    required readonly
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="nama_fakultas" class="block text-sm font-medium text-gray-700">Faculty Name</label>
                <input type="text" id="nama_fakultas" name="nama_fakultas"
                    value="<?= htmlspecialchars($nama_fakultas); ?>" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="flex space-x-4">
                <button type="submit" name="updateFakultas"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Update Faculty
                </button>
                <a href="../../../manage_fakultas"
                    class="w-full bg-gray-400 text-white py-2 px-4 rounded-md hover:bg-gray-500 text-center focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Back
                </a>
            </div>
        </form>
    </div>
</body>

</html>
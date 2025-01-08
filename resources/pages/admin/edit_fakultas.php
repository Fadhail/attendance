<?php      
session_start();      
include "../../../database/koneksi.php"; // Pastikan file ini menginisialisasi koneksi $pdo      
  
// Inisialisasi variabel untuk menghindari error pada halaman pertama kali dibuka      
$id_fakultas = $nama_fakultas = ""; // Inisialisasi variabel      
  
// Cek apakah ada id_fakultas yang dikirim melalui GET    
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
            header("Location: ../../../manage_fakultas"); // Redirect jika tidak ditemukan  
            exit();  
        }      
    } catch (PDOException $e) {      
        $_SESSION['message'] = "Error loading data fakultas: " . $e->getMessage();      
        header("Location: ../../../manage_fakultas"); // Redirect jika terjadi error  
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
            header("Location: ../../../manage_fakultas"); // Redirect jika terjadi error  
            exit();  
        }      
    } else {      
        $_SESSION['message'] = "Data tidak lengkap. Mohon lengkapi semua field.";      
        header("Location: ../../../manage_fakultas"); // Redirect jika data tidak lengkap  
        exit();  
    }      
}      
?>      
  
<!DOCTYPE html>      
<html lang="en">      
<head>      
    <meta charset="UTF-8">      
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    <title>Edit Fakultas</title>      
    <link rel="stylesheet" href="resources/assets/css/output.css">      
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />      
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>      
</head>      
<body>      
<?php include 'includes/sidebar.php'; ?>      
<div class="p-4 sm:ml-64">      
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">      
  
        <form id="updateFakultas" method="POST" action="" class="space-y-6">      
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Edit Fakultas</h2>      
  
            <div>      
                <label for="id_fakultas" class="block text-sm font-medium text-gray-700">ID Fakultas</label>      
                <input type="text" id="id_fakultas" name="id_fakultas" value="<?= htmlspecialchars($id_fakultas); ?>" required readonly       
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">      
            </div>      
  
            <div>      
                <label for="nama_fakultas" class="block text-sm font-medium text-gray-700">Nama Fakultas</label>      
                <input type="text" id="nama_fakultas" name="nama_fakultas" value="<?= htmlspecialchars($nama_fakultas); ?>" required      
                       class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">      
            </div>      
  
            <div>      
                <button type="submit" name="updateFakultas"      
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">      
                    Update Fakultas      
                </button>      
            </div>      
        </form>      
    </div>      
</div>      
</body>      
</html>      

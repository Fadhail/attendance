<?php      
session_start();      
include "../../../database/koneksi.php"; // Pastikan file ini menginisialisasi koneksi $pdo      
      
// Inisialisasi variabel untuk menghindari error pada halaman pertama kali dibuka      
$id_kelas = $nama_kelas = $id_dosen = ""; // Inisialisasi variabel      
      
// Cek apakah ada id_kelas yang dikirim melalui GET    
if (isset($_GET['id_kelas'])) {      
    $id_kelas = htmlspecialchars(trim($_GET['id_kelas']));      
      
    try {      
        // Query untuk mengambil data kelas berdasarkan id_kelas    
        $query = $pdo->prepare("SELECT * FROM kelas WHERE id_kelas = :id_kelas");      
        $query->bindParam(':id_kelas', $id_kelas, PDO::PARAM_INT);      
        $query->execute();      
        $kelas = $query->fetch(PDO::FETCH_ASSOC);      
      
        if ($kelas) {      
            $nama_kelas = $kelas['nama_kelas'];      
            $id_dosen = $kelas['dosen_nidn'];      
            $id_kelas = $kelas['id_kelas'];      
        } else {    
            $_SESSION['message'] = "Kelas tidak ditemukan.";    
            header("Location: ../../../manage_kelas"); // Redirect jika tidak ditemukan    
            exit();    
        }      
    } catch (PDOException $e) {      
        $_SESSION['message'] = "Error loading data kelas: " . $e->getMessage();      
        header("Location: ../../../manage_kelas"); // Redirect jika terjadi error    
        exit();    
    }      
}      
      
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["updateKelas"])) {      
    $id_kelas = htmlspecialchars(trim($_POST["id_kelas"] ?? ""));      
    $nama_kelas = htmlspecialchars(trim($_POST["nama_kelas"] ?? ""));      
    $id_dosen = htmlspecialchars(trim($_POST["id_dosen"] ?? ""));      
      
    if (!empty($id_kelas) && !empty($nama_kelas) && !empty($id_dosen)) {      
        try {      
            $query = $pdo->prepare("UPDATE kelas       
                                    SET nama_kelas = :nama_kelas, dosen_nidn = :dosen_nidn       
                                    WHERE id_kelas = :id_kelas");      
            $query->bindParam(':id_kelas', $id_kelas, PDO::PARAM_INT);      
            $query->bindParam(':nama_kelas', $nama_kelas, PDO::PARAM_STR);      
            $query->bindParam(':dosen_nidn', $id_dosen, PDO::PARAM_INT);      
      
            $query->execute();      
      
            $_SESSION['message'] = "Kelas berhasil diperbarui.";      
            header("Location: ../../../manage_kelas");      
            exit();      
        } catch (PDOException $e) {      
            $_SESSION['message'] = "Error: " . $e->getMessage();      
            header("Location: ../../../manage_kelas"); // Redirect jika terjadi error    
            exit();    
        }      
    } else {      
        $_SESSION['message'] = "Data tidak lengkap. Mohon lengkapi semua field.";      
        header("Location: ../../../manage_kelas"); // Redirect jika data tidak lengkap    
        exit();    
    }      
}      
?>      
      
<!DOCTYPE html>      
<html lang="en">      
<head>      
    <meta charset="UTF-8">      
    <meta name="viewport" content="width=device-width, initial-scale=1.0">      
    <title>Update Kelas</title>      
    <link rel="stylesheet" href="resources/assets/css/output.css">      
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />      
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>      
</head>      
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Edit Kelas</h2>    
   
      
            <form id="updateKelas" method="POST" action="" class="space-y-6">      
                <div>      
                    <label for="id_kelas" class="block text-sm font-medium text-gray-700">ID Kelas</label>      
                    <input type="text" id="id_kelas" name="id_kelas" value="<?= htmlspecialchars($id_kelas); ?>" required        
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">      
                </div>      
      
                <div>      
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>      
                    <input type="text" id="nama_kelas" name="nama_kelas" value="<?= htmlspecialchars($nama_kelas); ?>" required      
                           class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">      
                </div>      
      
                <div>      
                    <label for="id_dosen" class="block text-sm font-medium text-gray-700">Pilih Dosen</label>      
                    <select id="id_dosen" name="id_dosen" required      
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">      
                        <option value="" <?= empty($id_dosen) ? 'selected' : ''; ?>>Pilih Dosen</option>      
                        <?php      
                        try {      
                            $nama_dosen = $pdo->query("SELECT * FROM dosen");      
                            foreach ($nama_dosen as $dosen) {      
                                $selected = ($dosen["nidn"] == $id_dosen) ? 'selected' : '';      
                                echo '<option value="' . htmlspecialchars($dosen["nidn"]) . '" ' . $selected . '>'       
                                    . htmlspecialchars($dosen["first_name"] . " " . $dosen["last_name"])       
                                    . '</option>';      
                            }      
                        } catch (PDOException $e) {      
                            echo '<option value="">Error loading dosen: ' . htmlspecialchars($e->getMessage()) . '</option>';      
                        }      
                        ?>      
                    </select>      
                </div>      
      
            <div class="flex space-x-4">
                <button type="submit" name="updateKelas"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Update Kelas
                </button>
                <a href="../../../manage_kelas"
                   class="w-full bg-gray-400 text-white py-2 px-4 rounded-md hover:bg-gray-500 text-center focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Kemabli
                </a>
            </div>    
        </form>        
    </div>      
</body>
</html>      

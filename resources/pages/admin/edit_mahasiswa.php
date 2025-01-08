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
            header("Location: ../../../manage_mahasiswa"); 
            exit();    
        }        
    } catch (PDOException $e) {        
        $_SESSION['message'] = "Error loading data mahasiswa: " . $e->getMessage();        
        header("Location: ../../../manage_mahasiswa");
        exit();    
    }        
}        
  
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["updateMahasiswa"])) {        
    $npm = htmlspecialchars(trim($_POST["npm"] ?? ""));        
    $firstName = htmlspecialchars(trim($_POST["first_name"] ?? ""));        
    $lastName = htmlspecialchars(trim($_POST["last_name"] ?? ""));        
    $phone_no = htmlspecialchars(trim($_POST["phone_no"] ?? ""));        
    $email = htmlspecialchars(trim($_POST["email"] ?? ""));        
    $fakultas = htmlspecialchars(trim($_POST["id_fakultas"] ?? ""));        
    $kelas = htmlspecialchars(trim($_POST["id_kelas"] ?? ""));        
  
    if (!empty($npm) && !empty($firstName) && !empty($lastName) && !empty($phone_no) && !empty($email) && !empty($fakultas) && !empty($kelas)) {        
        try {        
            $query = $pdo->prepare("UPDATE mahasiswa         
                                    SET first_name = :first_name,   
                                        last_name = :last_name,   
                                        phone_no = :phone_no,   
                                        email = :email,   
                                        id_fakultas = :id_fakultas,   
                                        id_kelas = :id_kelas   
                                    WHERE npm = :npm");        
            $query->bindParam(':npm', $npm, PDO::PARAM_STR);        
            $query->bindParam(':first_name', $firstName, PDO::PARAM_STR);        
            $query->bindParam(':last_name', $lastName, PDO::PARAM_STR);        
            $query->bindParam(':phone_no', $phone_no, PDO::PARAM_STR);        
            $query->bindParam(':email', $email, PDO::PARAM_STR);        
            $query->bindParam(':id_fakultas', $fakultas, PDO::PARAM_INT);        
            $query->bindParam(':id_kelas', $kelas, PDO::PARAM_INT);        
  
            $query->execute();        
  
            $_SESSION['message'] = "Mahasiswa berhasil diperbarui.";        
            header("Location: ../../../manage_mahasiswa");        
            exit();        
        } catch (PDOException $e) {        
            $_SESSION['message'] = "Error: " . $e->getMessage();        
            header("Location: ../../../manage_mahasiswa");
            exit();    
        }        
    } else {        
        $_SESSION['message'] = "Data tidak lengkap. Mohon lengkapi semua field.";        
        header("Location: ../../../manage_mahasiswa");    
        exit();    
    }        
}        
?>        
  
<!DOCTYPE html>        
<html lang="en">        
<head>        
    <meta charset="UTF-8">        
    <meta name="viewport" content="width=device-width, initial-scale=1.0">        
    <title>Edit Mahasiswa</title>        
    <link rel="stylesheet" href="resources/assets/css/output.css">        
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />        
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>        
</head>        
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Edit Mahasiswa</h2>

        <form id="updateMahasiswa" method="POST" action="" class="space-y-6">
            <div>
                <label for="npm" class="block text-sm font-medium text-gray-700">NPM</label>
                <input type="text" id="npm" name="npm" value="<?= htmlspecialchars($npm); ?>" required readonly
                       class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($firstName); ?>" required
                       class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($lastName); ?>" required
                       class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="phone_no" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phone_no" name="phone_no" value="<?= htmlspecialchars($phone_no); ?>" required
                       class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required
                       class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="id_fakultas" class="block text-sm font-medium text-gray-700">Fakultas</label>
                <select id="id_fakultas" name="id_fakultas" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" selected>Pilih Fakultas</option>
                    <?php
                    // Fetch faculties from the database
                    $fakultasList = $pdo->query("SELECT * FROM fakultas")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($fakultasList as $fakultasItem) {
                        $selected = ($fakultasItem['id_fakultas'] == $fakultas) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($fakultasItem['id_fakultas']) . '" ' . $selected . '>' . htmlspecialchars($fakultasItem['nama_fakultas']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="id_kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                <select id="id_kelas" name="id_kelas" required class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" selected>Pilih Kelas</option>
                    <?php
                    // Fetch classes from the database
                    $kelasList = $pdo->query("SELECT * FROM kelas")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($kelasList as $kelasItem) {
                        $selected = ($kelasItem['id_kelas'] == $kelas) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($kelasItem['id_kelas']) . '" ' . $selected . '>' . htmlspecialchars($kelasItem['nama_kelas']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="flex space-x-4">
                <button type="submit" name="updateMahasiswa"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Update Mahasiswa
                </button>
                <a href="../../../manage_mahasiswa"
                   class="w-full bg-gray-400 text-white py-2 px-4 rounded-md hover:bg-gray-500 text-center focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</body>
</body>
</html>        

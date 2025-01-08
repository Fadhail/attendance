<?php  
if (isset($_POST["tambahMahasiswa"])) {  
    // Existing code for adding a student...  
}  
  
if (isset($_POST["editMahasiswa"])) {  
    $npm = htmlspecialchars(trim($_POST["npm"]));  
    $firstName = htmlspecialchars(trim($_POST["first_name"]));  
    $lastName = htmlspecialchars(trim($_POST["last_name"]));  
    $phone_no = htmlspecialchars(trim($_POST["phone_no"]));  
    $email = htmlspecialchars(trim($_POST["email"]));  
    $fakultas = htmlspecialchars(trim($_POST["id_fakultas"]));  
    $kelas = htmlspecialchars(trim($_POST["id_kelas"]));  
      
    try {  
        $updateQuery = $pdo->prepare("  
            UPDATE mahasiswa SET   
            first_name = :first_name,   
            last_name = :last_name,   
            phone_no = :phone_no,   
            email = :email,   
            id_fakultas = :id_fakultas,   
            id_kelas = :id_kelas   
            WHERE npm = :npm  
        ");  
          
        $updateQuery->execute([  
            ":first_name" => $firstName,  
            ":last_name" => $lastName,  
            ":phone_no" => $phone_no,  
            ":email" => $email,  
            ":id_fakultas" => $fakultas,  
            ":id_kelas" => $kelas,  
            ":npm" => $npm,  
        ]);  
  
        $_SESSION['message'] = "Mahasiswa berhasil diperbarui!";  
    } catch (PDOException $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
    }  
}  
  
if (isset($_GET["deleteNpm"])) {  
    $npm = htmlspecialchars(trim($_GET["deleteNpm"]));  
      
    try {  
        $deleteQuery = $pdo->prepare("DELETE FROM mahasiswa WHERE npm = :npm");  
        $deleteQuery->execute([':npm' => $npm]);  
          
        $_SESSION['message'] = "Mahasiswa berhasil dihapus!";  
    } catch (PDOException $e) {  
        $_SESSION['message'] = "Error: " . $e->getMessage();  
    }  
}  
?>  
  
<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Manage Mahasiswa</title>  
    <script src="resources/assets/javascript/admin.js"></script>  
    <script>  
        function toggleForm() {  
            const form = document.getElementById('tambahMahasiswa');  
            form.classList.toggle('hidden');  
        }  
  
        function confirmDelete() {  
            return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?');  
        }  
    </script>  
</head>  
<body>  
    <?php include 'includes/sidebar.php'; ?>  
  
    <div class="p-4 sm:ml-64">  
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">  
            <button onclick="toggleForm()"  
                class="mb-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Tambahkan Mahasiswa</button>  
            <form id="tambahMahasiswa" method="POST" enctype="multipart/form-data" class="space-y-6 hidden">  
                <!-- Existing fields for adding a student -->  
                <!-- ... -->  
                <div>  
                    <button type="submit" name="tambahMahasiswa" id="submitBtn" disabled  
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Save Mahasiswa</button>  
                </div>  
            </form>  
  
            <!-- Tabel Mahasiswa -->  
            <div class="mt-8">  
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">  
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">  
                        <tr>  
                            <th scope="col" class="py-3 px-6">NPM</th>  
                            <th scope="col" class="py-3 px-6">Nama</th>  
                            <th scope="col" class="py-3 px-6">Kelas</th>  
                            <th scope="col" class="py-3 px-6">Fakultas</th>  
                            <th scope="col" class="py-3 px-6">NO Telepon</th>  
                            <th scope="col" class="py-3 px-6">Email</th>  
                            <th scope="col" class="py-3 px-6">Created At</th>  
                            <th scope="col" class="py-3 px-6">Action</th>  
                        </tr>  
                    </thead>  
                    <tbody>  
                        <?php  
                        $sql = "SELECT m.npm, m.first_name, m.last_name, m.phone_no, m.email, m.created_at, f.nama_fakultas, k.nama_kelas  
                        FROM mahasiswa m  
                        INNER JOIN fakultas f ON m.id_fakultas = f.id_fakultas  
                        INNER JOIN kelas k ON m.id_kelas = k.id_kelas";  
                        $result = fetch($sql);  
                        if ($result) {  
                            foreach ($result as $mahasiswa) {  
                                echo "<tr id='mahasiswa{$mahasiswa["npm"]}'>";  
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($mahasiswa["npm"]) . "</td>";  
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($mahasiswa["first_name"] . " " . $mahasiswa["last_name"]) . "</td>";  
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($mahasiswa["nama_kelas"]) . "</td>";  
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($mahasiswa["nama_fakultas"]) . "</td>";  
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($mahasiswa["phone_no"]) . "</td>";  
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($mahasiswa["email"]) . "</td>";  
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($mahasiswa["created_at"]) . "</td>";  
                                echo "<td class='py-3 px-6'>";  
                                echo "<a href='resources/pages/admin/edit_mahasiswa.php?npm={$mahasiswa["npm"]}' class='text-blue-600 hover:underline'>Edit</a> | ";  
                                echo "<a href='?deleteNpm={$mahasiswa["npm"]}' class='text-red-600 hover:underline' onclick='return confirmDelete();'>Hapus</a>";  
                                echo "</td>";  
                                echo "</tr>";  
                            }  
                        } else {  
                            echo "<tr><td colspan='8' class='text-center py-3 px-6'>No records found</td></tr>";  
                        }  
                        ?>  
                    </tbody>  
                </table>  
            </div>  
        </div>  
    </div>  
</body>  
</html>  

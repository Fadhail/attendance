<?php  
if (isset($_POST["addKelas"])) {  
    $id_kelas = htmlspecialchars(trim($_POST["id_kelas"]));  
    $nama_kelas = htmlspecialchars(trim($_POST["nama_kelas"]));  
    $id_dosen = htmlspecialchars(trim($_POST["id_dosen"]));  
  
    if ($id_kelas && $nama_kelas && $id_dosen) {  
        try {  
            $query = $pdo->prepare("INSERT INTO kelas (id_kelas, nama_kelas, dosen_nidn)   
                    VALUES (:id_kelas, :nama_kelas, :dosen_nidn)");  
            $query->bindParam(':id_kelas', $id_kelas);  
            $query->bindParam(':nama_kelas', $nama_kelas);  
            $query->bindParam(':dosen_nidn', $id_dosen);  
  
            $query->execute();  
  
            $_SESSION['message'] = "Kelas berhasil ditambahkan";  
        } catch (PDOException $e) {  
            $_SESSION['message'] = "Error: " . $e->getMessage();  
        }  
    } else {  
        $_SESSION['message'] = "Data salah";  
    }  
}  
?>  
  
<!DOCTYPE html>  
<html lang="en">  
  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Manage Class</title>  
    <script>  
        function toggleForm() {  
            const form = document.getElementById('tambahKelas');  
            form.classList.toggle('hidden');  
        }  
  
        function confirmDelete() {  
            return confirm('Apakah Anda yakin ingin menghapus kelas ini?');  
        }  
    </script>  
    <link rel="stylesheet" href="resources/assets/css/output.css">  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>  
</head>  
  
<body>  
    <?php include 'includes/sidebar.php'; ?>  
    <div class="p-4 sm:ml-64">  
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">  
            <button onclick="toggleForm()"  
                class="mb-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Add Class</button>  
            <form id="tambahKelas" method="POST" action="" class="space-y-6 hidden">  
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add Class</h2>  
                <div>  
                    <label for="id_kelas" class="block text-sm font-medium text-gray-700"> Class ID</label>  
                    <input type="text" id="id_kelas" name="id_kelas" required  
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">  
                </div>  
                <div>  
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Class Name</label>  
                    <input type="text" id="nama_kelas" name="nama_kelas" required  
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">  
                </div>  
                <div>  
                    <label for="id_dosen" class="block text-sm font-medium text-gray-700">Select Lecture</label>  
                    <select id="id_dosen" name="id_dosen" required  
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">  
                        <option value="" selected>Select Lecture</option>  
                        <?php  
                        $nama_dosen = fetch("SELECT * FROM dosen");  
                        foreach ($nama_dosen as $dosen) {  
                            echo '<option value="' . $dosen["nidn"] . '">' . htmlspecialchars($dosen["first_name"] . " " . $dosen["last_name"]) . '</option>';  
                        }  
                        ?>  
                    </select>  
                </div>  
                <div>  
                    <button type="submit" name="addKelas"  
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Add</button>  
                </div>  
            </form>  
  
            <!-- Tabel Kelas -->  
            <div class="mt-8">  
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">  
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">  
                        <tr>  
                            <th scope="col" class="py-3 px-6">Class ID</th>  
                            <th scope="col" class="py-3 px-6">Class Name</th>  
                            <th scope="col" class="py-3 px-6">Lecture</th>  
                            <th scope="col" class="py-3 px-6">Action</th>  
                        </tr>  
                    </thead>  
                    <tbody>  
                        <?php  
                        $sql = "SELECT k.id_kelas, k.nama_kelas, d.first_name, d.last_name    
                                FROM kelas k    
                                INNER JOIN dosen d ON k.dosen_nidn = d.nidn";    
                        $result = fetch($sql);    
                          
                        if ($result && count($result) > 0) {    
                            foreach ($result as $kelas) {    
                                echo "<tr id='kelas{$kelas["id_kelas"]}'>";    
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($kelas["id_kelas"]) . "</td>";    
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($kelas["nama_kelas"]) . "</td>";    
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($kelas["first_name"] . " " . $kelas["last_name"]) . "</td>";    
                                echo "<td class='py-3 px-6'><a href='resources/pages/admin/edit_kelas.php?id={$kelas["id_kelas"]}' class='text-blue-600 hover:underline'>Edit</a> | ";    
                                echo "<a href='resources/pages/admin/hapus_kelas.php?id={$kelas["id_kelas"]}' class='text-red-600 hover:underline' onclick=\"return confirmDelete();\">Hapus</a>";    
                                echo "</td>";    
                                echo "</tr>";    
                            }    
                        } else {    
                            echo "<tr><td colspan='4' class='text-center py-3 px-6'>No records found</td></tr>";    
                        }    
                        ?>    
                    </tbody>    
                </table>  
            </div>  
        </div>  
    </div>  
</body>  
  
</html>  

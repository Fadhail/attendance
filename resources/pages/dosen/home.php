<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="resources/images/logo/attnlg.png" rel="icon">
    <title>Dosen</title>
    <link rel="stylesheet" href="./../../src/css/output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</head>

<body>

    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 sm:ml-64">  
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">  
            <h2 class="text-xl font-semibold mb-4">Dashboard</h2>  
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">  
                <!-- Total Mahasiswa -->  
                <div class="bg-blue-100 p-4 rounded-lg shadow-md">  
                    <h3 class="text-lg font-semibold">Total Students</h3>  
                    <p class="text-2xl font-bold text-blue-600">  
                        <?php  
                        // Menghitung total mahasiswa  
                        $totalMahasiswa = fetch("SELECT COUNT(*) as total FROM mahasiswa")[0]['total'];  
                        echo $totalMahasiswa;  
                        ?>  
                    </p>  
                </div>  
  
                <!-- Total Dosen -->  
                <div class="bg-green-100 p-4 rounded-lg shadow-md">  
                    <h3 class="text-lg font-semibold">Total Lecturers</h3>  
                    <p class="text-2xl font-bold text-green-600">  
                        <?php  
                        // Menghitung total dosen  
                        $totalDosen = fetch("SELECT COUNT(*) as total FROM dosen")[0]['total'];  
                        echo $totalDosen;  
                        ?>  
                    </p>  
                </div>  
  
                <!-- Total Kelas -->  
                <div class="bg-yellow-100 p-4 rounded-lg shadow-md">  
                    <h3 class="text-lg font-semibold">Total Class</h3>  
                    <p class="text-2xl font-bold text-yellow-600">  
                        <?php  
                        // Menghitung total kelas  
                        $totalKelas = fetch("SELECT COUNT(*) as total FROM kelas")[0]['total'];  
                        echo $totalKelas;  
                        ?>  
                    </p>  
                </div>  
  
                <!-- Total Fakultas -->  
                <div class="bg-red-100 p-4 rounded-lg shadow-md">  
                    <h3 class="text-lg font-semibold">Total Faculty</h3>  
                    <p class="text-2xl font-bold text-red-600">  
                        <?php  
                        // Menghitung total fakultas  
                        $totalFakultas = fetch("SELECT COUNT(*) as total FROM fakultas")[0]['total'];  
                        echo $totalFakultas;  
                        ?>  
                    </p>  
                </div>  
            </div>  
  
            <!-- Tabel Data (Optional) -->  
            <div class="mt-8">  
                <h3 class="text-lg font-semibold mb-4">Data Mahasiswa</h3>  
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">  
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">  
                        <tr>  
                            <th scope="col" class="py-3 px-6">NPM</th>  
                            <th scope="col" class="py-3 px-6">Name</th>  
                            <th scope="col" class="py-3 px-6">Phone Number</th>  
                            <th scope="col" class="py-3 px-6">Email</th>  
                            <th scope="col" class="py-3 px-6">Action</th>  
                        </tr>  
                    </thead>  
                    <tbody>  
                        <?php  
                        // Query untuk mengambil semua mahasiswa  
                        $sql = "SELECT * FROM mahasiswa";  
                        $result = fetch($sql);  
  
                        if ($result) {  
                            foreach ($result as $mahasiswa) {  
                                echo "<tr id='mahasiswa{$mahasiswa["npm"]}'>";  
                                echo "<td class='py-3 px-6'>" . $mahasiswa["npm"] . "</td>";  
                                echo "<td class='py-3 px-6'>" . $mahasiswa["first_name"] . " " . $mahasiswa["last_name"] . "</td>";  
                                echo "<td class='py-3 px-6'>" . $mahasiswa["phone_no"] . "</td>";  
                                echo "<td class='py-3 px-6'>" . $mahasiswa["email"] . "</td>";  
                                echo "<td class='py-3 px-6'>";  
                                echo "<a href='resources/pages/dosen/detail_mahasiswa.php?npm={$mahasiswa["npm"]}' class='text-blue-600 hover:underline'>Detail</a>";  
                                echo "</td>";  
                                echo "</tr>";  
                            }  
                        } else {  
                            echo "<tr><td colspan='5' class='text-center py-3 px-6'>No records found</td></tr>";  
                        }  
                        ?>  
                    </tbody>  
                </table>  
            </div>  
        </div>  
    </div>  


</body>

</html>
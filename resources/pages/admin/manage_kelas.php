<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Kelas</title>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 sm:ml-64">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
            <!-- Tabel Kelas -->
            <div class="mt-8">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">Kode Kelas</th>
                            <th scope="col" class="py-3 px-6">Nama Kelas</th>
                            <th scope="col" class="py-3 px-6">Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT k.kode_kelas, k.nama_kelas, d.first_name, d.last_name, k.id 
                        FROM kelas k 
                        INNER JOIN dosen d ON k.dosen_id = d.id";
                        $result = fetch($sql); // Assuming fetch() returns an array of results or false.
                        
                        if ($result && count($result) > 0) {
                            foreach ($result as $kelas) {
                                echo "<tr id='kelas{$kelas["id"]}'>";
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($kelas["kode_kelas"]) . "</td>";
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($kelas["nama_kelas"]) . "</td>";
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($kelas["first_name"] . " " . $kelas["last_name"]) . "</td>";
                                echo "<td class='py-3 px-6'><span><i class='ri-delete-bin-line delete' data-id='{$kelas["id"]}' data-name='kelas'></i></span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-3 px-6'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
<?php
if (isset($_POST["addFakultas"])) {
    $id_fakultas = htmlspecialchars(trim($_POST["id_fakultas"]));
    $nama_fakultas = htmlspecialchars(trim($_POST["nama_fakultas"]));

    if ($id_fakultas && $nama_fakultas) {
        try {
            $query = $pdo->prepare("INSERT INTO fakultas (id_fakultas, nama_fakultas) 
                    VALUES (:id_fakultas, :nama_fakultas)");
            $query->bindParam(':id_fakultas', $id_fakultas);
            $query->bindParam(':nama_fakultas', $nama_fakultas);

            $query->execute();

            $_SESSION['message'] = "Fakultas berhasil ditambahkan";
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
    <title>Manage Fakultas</title>
    <script>
        function toggleForm() {
            const form = document.getElementById('tambahFakultas');
            form.classList.toggle('hidden');
        }
    </script>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
    <div class="p-4 sm:ml-64">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
            <button onclick="toggleForm()"
                class="mb-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Tambah Fakultas</button>
            <form id="tambahFakultas" method="POST" action="" class="space-y-6 hidden">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Tambah Fakultas</h2>
                <div>
                    <label for="id_fakultas" class="block text-sm font-medium text-gray-700">ID Fakultas</label>
                    <input type="text" id="id_fakultas" name="id_fakultas" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="nama_fakultas" class="block text-sm font-medium text-gray-700">Nama Fakultaas</label>
                    <input type="text" id="nama_fakultas" name="nama_fakultas" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <button type="submit" name="addFakultas"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Tambahkan</button>
                </div>
            </form>

            <!-- Tabel Fakultas -->
            <div class="mt-8">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">ID Fakultas</th>
                            <th scope="col" class="py-3 px-6">Nama Fakultas</th>
                            <th scope="col" class="py-3 px-6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM fakultas";
                        $result = fetch($sql);
                        if ($result) {
                            foreach ($result as $fakultas) {
                                echo "<tr id='fakultas{$fakultas["id_fakultas"]}'>";
                                echo "<td class='py-3 px-6'>" . $fakultas["id_fakultas"] . "</td>";
                                echo "<td class='py-3 px-6'>" . $fakultas["nama_fakultas"] . "</td>";
                                echo "<td class='py-3 px-6'><span><i class='ri-delete-bin-line delete' data-id='{$fakultas["id_fakultas"]}' data-name='fakultas'></i></span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-3 px-6'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
</body>
</html>
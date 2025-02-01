<?php
date_default_timezone_set("Asia/Jakarta");

if (isset($_POST["addLecture"])) {
    $nidn = htmlspecialchars(trim($_POST["nidn"]));
    $firstName = htmlspecialchars(trim($_POST["first_name"]));
    $lastName = htmlspecialchars(trim($_POST["last_name"]));
    $phone_no = htmlspecialchars(trim($_POST["phone_no"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $created_at = date("Y-m-d H:i:s");
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    if ($nidn && $firstName && $lastName && $phone_no && $email) {
        try {
            $query = $pdo->prepare("SELECT * FROM dosen WHERE email = :email");
            $query->bindParam(':email', $email);
            $query->execute();

            if ($query->rowCount() > 0) {
                $_SESSION['message'] = "Dosen sudah terdaftar";
            } else {
                $query = $pdo->prepare("INSERT INTO dosen (nidn, first_name, last_name, phone_no, email, password, created_at)   
                        VALUES (:nidn, :first_name, :last_name, :phone_no, :email, :password, :created_at)");
                $query->bindParam(':nidn', $nidn);
                $query->bindParam(':first_name', $firstName);
                $query->bindParam(':last_name', $lastName);
                $query->bindParam(':phone_no', $phone_no);
                $query->bindParam(':email', $email);
                $query->bindParam(':password', $hashedPassword);
                $query->bindParam(':created_at', $created_at);

                $query->execute();

                $_SESSION['message'] = "Dosen berhasil ditambahkan";
            }
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
    <title>Manage Lecturers</title>
    <script>
        function toggleForm() {
            const form = document.getElementById('tambahDosen');
            form.classList.toggle('hidden');
        }

        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus dosen ini?');
        }  
    </script>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 sm:ml-64">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
            <button onclick="toggleForm()"
                class="mb-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Add
                New Dosen</button>
            <form id="tambahDosen" method="POST" action="" class="space-y-6 hidden">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add New Dosen</h2>
                <div>
                    <label for="nidn" class="block text-sm font-medium text-gray-700">NIDN:</label>
                    <input type="text" id="nidn" name="nidn" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="phone_no" class="block text-sm font-medium text-gray-700">Phone Number:</label>
                    <input type="text" id="phone_no" name="phone_no" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                    <input type="password" id="password" name="password" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <button type="submit" name="addLecture"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Save
                        Dosen</button>
                </div>
            </form>

            <!-- Tabel Dosen -->
            <div class="mt-8">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">NIDN</th>
                            <th scope="col" class="py-3 px-6">Name</th>
                            <th scope="col" class="py-3 px-6">Phone Number</th>
                            <th scope="col" class="py-3 px-6">Email</th>
                            <th scope="col" class="py-3 px-6">Created At</th>
                            <th scope="col" class="py-3 px-6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM dosen";
                        $result = fetch($sql);
                        if ($result) {
                            foreach ($result as $dosen) {
                                echo "<tr id='dosen{$dosen["nidn"]}'>";
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($dosen["nidn"]) . "</td>";
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($dosen["first_name"] . " " . $dosen["last_name"]) . "</td>";
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($dosen["phone_no"]) . "</td>";
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($dosen["email"]) . "</td>";
                                echo "<td class='py-3 px-6'>" . htmlspecialchars($dosen["created_at"]) . "</td>";
                                echo "<td class='py-3 px-6'>";
                                echo "<a href='resources/pages/admin/edit_dosen.php?nidn={$dosen["nidn"]}' class='text-blue-600 hover:underline'>Edit</a> | ";
                                echo "<a href='resources/pages/admin/hapus_dosen.php?nidn={$dosen["nidn"]}' class='text-red-600 hover:underline' onclick='return confirmDelete();'>Hapus</a>";
                                echo "</td>";
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
</body>

</html>
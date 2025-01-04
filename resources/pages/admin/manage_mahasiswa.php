<?php

if (isset($_POST["tambahMahasiswa"])) {
    $npm = $_POST['npm'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone_no = $_POST['phone_no'];
    $email = $_POST['email'];
    $fakultas = $_POST['id_fakultas'];
    $kelas = $_POST['id_kelas'];
    $created_at = date("Y-m-d");

    $image = []; // Corrected variable name

    // Save images
    $folderPath = "resources/labels/{$npm}/";
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["capturedImage$i"])) {
            $base64Data = explode(',', $_POST["capturedImage$i"])[1];
            $imageData = base64_decode($base64Data);
            $fileName = "{$npm}_image{$i}.png";
            $labelName = "{$i}_{$npm}.png";
            file_put_contents("{$folderPath}{$labelName}", $imageData);
            $image[] = $labelName;
        }
    }

    // Convert image file names to JSON
    $imagesJson = json_encode($image);

    // Cek Duplikasi NPM
    $query = $pdo->prepare("SELECT * FROM mahasiswa WHERE npm = :npm");
    $query->execute(['npm' => $npm]);
    $count = $query->fetchColumn();

    if ($count > 0) {
        $_SESSION['message'] = "Mahasiswa dengan NPM $npm sudah terdaftar!";
    } else {
        $insertQuery = $pdo->prepare("
        INSERT INTO mahasiswa
        (npm, first_name, last_name, phone_no, email, images, id_fakultas, id_kelas, created_at)  
        VALUES 
        (:npm, :first_name, :last_name, :phone_no, :email, :images, :id_fakultas, :id_kelas, :created_at)
    ");

        $insertQuery->execute([
            ":npm" => $npm,
            ":first_name" => $firstName,
            ":last_name" => $lastName,
            ":phone_no" => $phone_no,
            ":email" => $email,
            ":images" => $imagesJson,
            "id_fakultas" => $fakultas,
            "id_kelas" => $kelas,
            ":created_at" => $created_at,
        ]);

        $_SESSION['message'] = "Mahasiswa berhasil ditambahkan!";
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
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="p-4 sm:ml-64">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
            <button onclick="toggleForm()"
                class="mb-4 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Tambahkan
                Mahasiswa</button>
            <form id="tambahMahasiswa" method="POST" enctype="multipart/form-data" class="space-y-6 hidden">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add New Mahasiswa</h2>
                <!-- Existing fields -->
                <div>
                    <label for="npm" class="block text-sm font-medium text-gray-700">NPM:</label>
                    <input type="text" id="npm" name="npm" required
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
                <select required name="id_fakultas">
                    <option value="" selected>Pilih Fakultas</option>
                    <?php
                    $nama_fakultas = fetch("SELECT * FROM fakultas");
                    foreach ($nama_fakultas as $fakultas) {
                        echo '<option value="' . $fakultas["id_fakultas"] . '">' . $fakultas["nama_fakultas"] . '</option>';
                    }
                    ?>
                </select>
                <select required name="id_kelas">
                    <option value="" selected>Pilih Kelas</option>
                    <?php
                    $nama_kelas = fetch("SELECT * FROM kelas");
                    foreach ($nama_kelas as $kelas) {
                        echo '<option value="' . $kelas["id_kelas"] . '">' . $kelas["nama_kelas"] . '</option>';
                    }
                    ?>
                </select>
                <!-- Camera and Results Section -->
                <div class="mt-6">
                    <div class="camera-section">
                        <video id="camera" autoplay class="w-sm rounded-md border shadow"></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <p class="text-gray-700 mb-2 mt-2"><i>*Ambil 5 Gambar</i></p>
                        <button type="button" onclick="captureImage()"
                            class="mt-2 bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">Ambil Gambar</button>
                    </div>
                    <div class="results-section mt-4">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Hasil :</h3>
                        <div id="images" class="flex gap-2 overflow-x-auto">
                        </div>
                        <input type="hidden" name="capturedImage1" id="capturedImage1">
                        <input type="hidden" name="capturedImage2" id="capturedImage2">
                        <input type="hidden" name="capturedImage3" id="capturedImage3">
                        <input type="hidden" name="capturedImage4" id="capturedImage4">
                        <input type="hidden" name="capturedImage5" id="capturedImage5">
                    </div>
                </div>

                <div>
                    <button type="submit" name="tambahMahasiswa" id="submitBtn" disabled
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Save
                        Mahasiswa</button>
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
                        $sql = "SELECT m.npm, m.first_name, m.last_name, m.phone_no, m.email, m.created_at, f.id_fakultas, f.nama_fakultas, k.id_kelas, k.nama_kelas
                        FROM mahasiswa m
                        INNER JOIN fakultas f ON m.id_fakultas = f.id_fakultas
                        INNER JOIN kelas k ON m.id_kelas = k.id_kelas";
                        $result = fetch($sql);
                        if ($result) {
                            foreach ($result as $mahasiswa) {
                                echo "<tr id='mahasiswa{$mahasiswa["npm"]}'>";
                                echo "<td class='py-3 px-6'>" . $mahasiswa["npm"] . "</td>";
                                echo "<td class='py-3 px-6'>" . $mahasiswa["first_name"] . " " . $mahasiswa["last_name"] . "</td>";
                                echo "<td class='py-3 px-6'>" . $mahasiswa["nama_kelas"] . "</td>";
                                echo "<td class='py-3 px-6'>" . $mahasiswa["nama_fakultas"] . "</td>";
                                echo "<td class='py-3 px-6'>" . $mahasiswa["phone_no"] . "</td>";
                                echo "<td class='py-3 px-6'>" . $mahasiswa["email"] . "</td>";
                                echo "<td class='py-3 px-6'>" . $mahasiswa["created_at"] . "</td>";
                                echo "<td class='py-3 px-6'><span><i class='ri-delete-bin-line delete' data-id='{$mahasiswa["npm"]}' data-name='mahasiswa'></i></span></td>";
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
</body>

</html>
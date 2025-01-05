<?php
include "../../../database/koneksi.php";

if(isset($_GET['id_kelas'])) {
    $id_kelas = $_GET['id_kelas'];

    $query = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas = '$id_kelas'");
    $data = mysqli_fetch_array($query);
}

if(isset($_POST['updateKelas'])) {
    $id_kelas = $_POST['id_kelas'];
    $nama_kelas = $_POST['nama_kelas'];
    $id_dosen = $_POST['id_dosen'];

    $updateQuery = "UPDATE kelas SET nama_kelas='$nama_kelas', id_dosen='$id_dosen' WHERE id_kelas='$id_kelas'";

    if(mysqli_query($koneksi, $updateQuery)) {
        $_SESSION['message'] = "Data kelas berhasil diupdate!";
    } else {
        $_SESSION['message'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
    }
    header("Location: update_kelas.php?id_kelas=$id_kelas");
    exit;
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
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="p-4 sm:ml-64">
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <p><?= htmlspecialchars($_SESSION['message']); ?></p>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Update Kelas</h2>

            <div>
                <label for="id_kelas" class="block text-sm font-medium text-gray-700">ID Kelas</label>
                <input type="text" id="id_kelas" name="id_kelas" value="<?= htmlspecialchars($data['id_kelas']); ?>" readonly
                       class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
            </div>

            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                <input type="text" id="nama_kelas" name="nama_kelas" value="<?= htmlspecialchars($data['nama_kelas']); ?>" required
                       class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
            </div>

            <div>
                <label for="id_dosen" class="block text-sm font-medium text-gray-700">Pilih Dosen</label>
                <select id="id_dosen" name="id_dosen" required
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                    <option value="">Pilih Dosen</option>
                    <?php
                    $nama_dosen = mysqli_query($koneksi, "SELECT * FROM dosen");
                    while($dosen = mysqli_fetch_assoc($nama_dosen)) {
                        $selected = ($data['id_dosen'] == $dosen['nidn']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($dosen["nidn"]) . '" ' . $selected . '>'
                            . htmlspecialchars($dosen["first_name"] . " " . $dosen["last_name"]) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div>
                <button type="submit" name="updateKelas"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                    Update Kelas
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 sm:ml-64">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
            <form method="GET" action="">
                <!-- Pilih Fakultas -->
                <select required name="id_fakultas" id="fakultas" onchange="this.form.submit()">
                    <option value="" selected>Select Faculty</option>
                    <?php
                    $fakultasList = fetch("SELECT * FROM fakultas");
                    foreach ($fakultasList as $fakultas) {
                        $selected = isset($_GET['id_fakultas']) && $fakultas["id_fakultas"] == $_GET['id_fakultas'] ? "selected" : "";
                        echo '<option value="' . $fakultas["id_fakultas"] . '" ' . $selected . '>' . $fakultas["nama_fakultas"] . '</option>';
                    }
                    ?>
                </select>

                <!-- Pilih Kelas -->
                <select required name="id_kelas" id="kelas" onchange="this.form.submit()">
                    <option value="" selected>Select Class</option>
                    <?php
                    $kelasList = fetch("SELECT * FROM kelas");
                    foreach ($kelasList as $kelas) {
                        $selected = isset($_GET['id_kelas']) && $kelas["id_kelas"] == $_GET['id_kelas'] ? "selected" : "";
                        echo '<option value="' . $kelas["id_kelas"] . '" ' . $selected . '>' . $kelas["nama_kelas"] . '</option>';
                    }
                    ?>
                </select>

                <!-- Pilih Tanggal -->
                <input type="date" name="tanggal" id="tanggal" required onchange="this.form.submit()" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">

            </form>

            <!-- Tabel Mahasiswa -->
            <div class="mt-8">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">Name</th>
                            <th scope="col" class="py-3 px-6">NPM</th>
                            <th scope="col" class="py-3 px-6">Faculty</th>
                            <th scope="col" class="py-3 px-6">Class</th>
                            <th scope="col" class="py-3 px-6">Date</th>
                            <th scope="col" class="py-3 px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_GET['id_fakultas']) && isset($_GET['id_kelas']) && isset($_GET['tanggal'])) {
                            $id_fakultas = $_GET['id_fakultas'];
                            $id_kelas = $_GET['id_kelas'];
                            $tanggal = $_GET['tanggal'];

                            $sql = "SELECT 
                                    m.first_name, m.last_name, m.npm, f.nama_fakultas, k.nama_kelas, p.status, p.created_at
                                    FROM mahasiswa m 
                                    JOIN fakultas f ON m.id_fakultas = f.id_fakultas 
                                    JOIN kelas k ON m.id_kelas = k.id_kelas 
                                    JOIN presensi p ON m.npm = p.npm 
                                    WHERE p.id_fakultas = :id_fakultas AND p.id_kelas = :id_kelas AND DATE(p.created_at) = :tanggal";

                            $stmt = $pdo->prepare($sql);
                            $tanggal = date('Y-m-d H:i:s', strtotime($tanggal));
                            $stmt->execute(['id_fakultas' => $id_fakultas, 'id_kelas' => $id_kelas, 'tanggal' => $tanggal]);
                            $result = $stmt->fetchAll();

                            foreach ($result as $row) {
                                echo '<tr>';
                                echo '<td class="py-3 px-6">' . $row['first_name'] . ' ' . $row['last_name'] . '</td>';
                                echo '<td class="py-3 px-6">' . $row['npm'] . '</td>';
                                echo '<td class="py-3 px-6">' . $row['nama_fakultas'] . '</td>';
                                echo '<td class="py-3 px-6">' . $row['nama_kelas'] . '</td>';
                                echo '<td class="py-3 px-6">' . $tanggal . '</td>';
                                echo '<td class="py-3 px-6">' . $row['status'] . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                    </table>
            </div>
        </div>
    </div>
</body>
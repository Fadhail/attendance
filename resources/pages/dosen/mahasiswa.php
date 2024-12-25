<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="p-4 sm:ml-64">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg bg-white p-6 rounded-lg">
            <form method="GET" action="">
                <!-- Pilih Fakultas -->
                <select required name="id_fakultas" id="fakultas" onchange="this.form.submit()">
                    <option value="" selected>Select Fakultas</option>
                    <?php
                    // Fetch all fakultas
                    $fakultasList = fetch("SELECT * FROM fakultas");
                    foreach ($fakultasList as $fakultas) {
                        // Set selected if ID matches
                        $selected = isset($_GET['id_fakultas']) && $fakultas["id_fakultas"] == $_GET['id_fakultas'] ? "selected" : "";
                        echo '<option value="' . $fakultas["id_fakultas"] . '" ' . $selected . '>' . $fakultas["nama_fakultas"] . '</option>';
                    }
                    ?>
                </select>

                <!-- Pilih Kelas -->
                <select required name="id_kelas" id="kelas" onchange="this.form.submit()">
                    <option value="" selected>Select Kelas</option>
                    <?php
                    // Fetch all kelas
                    $kelasList = fetch("SELECT * FROM kelas");
                    foreach ($kelasList as $kelas) {
                        // Set selected if ID matches
                        $selected = isset($_GET['id_kelas']) && $kelas["id_kelas"] == $_GET['id_kelas'] ? "selected" : "";
                        echo '<option value="' . $kelas["id_kelas"] . '" ' . $selected . '>' . $kelas["nama_kelas"] . '</option>';
                    }
                    ?>
                </select>
            </form>

            <!-- Tabel Mahasiswa -->
            <div class="mt-8">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6">NPM</th>
                            <th scope="col" class="py-3 px-6">Nama</th>
                            <th scope="col" class="py-3 px-6">NO Telepon</th>
                            <th scope="col" class="py-3 px-6">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Pastikan ada parameter id_fakultas dan id_kelas di URL
                        if (isset($_GET['id_fakultas']) && isset($_GET['id_kelas'])) {
                            $id_fakultas = $_GET['id_fakultas'];
                            $id_kelas = $_GET['id_kelas'];

                            // Query untuk mengambil mahasiswa berdasarkan fakultas dan kelas
                            $sql = "SELECT * FROM mahasiswa WHERE id_fakultas = '$id_fakultas' AND id_kelas = '$id_kelas'";
                            $result = fetch($sql);

                            if ($result) {
                                foreach ($result as $mahasiswa) {
                                    echo "<tr id='mahasiswa{$mahasiswa["npm"]}'>";
                                    echo "<td class='py-3 px-6'>" . $mahasiswa["npm"] . "</td>";
                                    echo "<td class='py-3 px-6'>" . $mahasiswa["first_name"] . " " . $mahasiswa["last_name"] . "</td>";
                                    echo "<td class='py-3 px-6'>" . $mahasiswa["phone_no"] . "</td>";
                                    echo "<td class='py-3 px-6'>" . $mahasiswa["email"] . "</td>";
                                    echo "<td class='py-3 px-6'><span><i class='ri-delete-bin-line delete' data-id='{$mahasiswa["npm"]}' data-name='mahasiswa'></i></span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-3 px-6'>No records found for the selected fakultas and kelas</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-3 px-6'>Please select a fakultas and kelas</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
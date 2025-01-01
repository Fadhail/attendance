<div class="mt-8">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">NPM</th>
                <th scope="col" class="py-3 px-6">Nama</th>
                <th scope="col" class="py-3 px-6">ID Fakultas</th>
                <th scope="col" class="py-3 px-6">ID Kelas</th>
                <th scope="col" class="py-3 px-6">Status</th>
                <th scope="col" class="py-3 px-6">Action</th>
            </tr>
        </thead>
        <tbody id="tabelMahasiswa">
            <?php
            if (isset($_POST['id_fakultas']) && isset($_POST['id_kelas'])) {

                $id_fakultas = $_POST['id_fakultas'];
                $id_kelas = $_POST['id_kelas'];

                $sql = "SELECT * FROM mahasiswa WHERE id_fakultas = '$id_fakultas' AND id_kelas = '$id_kelas'";
                $result = fetch($sql);

                if ($result) {
                    foreach ($result as $row) {
                        echo "<tr>";
                        $npm = $row["npm"];
                        echo "<td class='py-3 px-6'>" . $npm . "</td>";
                        echo "<td class='py-3 px-6'>" . $row["first_name"] . "</td>";
                        echo "<td class='py-3 px-6'>" . $id_fakultas . "</td>";
                        echo "<td class='py-3 px-6'>" . $id_kelas . "</td>";
                        echo "<td class='py-3 px-6'>Absent</td>";
                        echo "<td class='py-3 px-6'><span><i class='ri-edit-line edit'></i><i class='ri-delete-bin-line delete'></i></span></td>";
                        echo "</tr>";
                    }

                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
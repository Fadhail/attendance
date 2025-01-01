<?php

require_once "../../../database/koneksi.php";
require_once "../../functions/functions.php";
$response = array();

try {
    if (isset($_POST['id_fakultas']) && isset($_POST['id_kelas'])) {
        $id_fakultas = $_POST['id_fakultas'];
        $id_kelas = $_POST['id_kelas'];

        $sql = "SELECT npm FROM mahasiswa WHERE id_kelas = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_kelas]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            $npm = array_map(fn($row) => $row['npm'], $result);

            $response['status'] = 'success';
            $response['data'] = $npm;

            ob_start();
            include './tabelMahasiswa.php';
            $response['html'] = ob_get_clean();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No records found.';
        }
    } else {
        throw new Exception('Invalid or missing parameters.');
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);


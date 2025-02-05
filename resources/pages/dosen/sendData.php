<?php
date_default_timezone_set("Asia/Jakarta");

require_once "../../../database/koneksi.php";
require_once "../../functions/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);
    $response = [];

    if ($attendanceData) {
        try {
            $sql = "INSERT INTO presensi (npm, id_fakultas, id_kelas, status, created_at) VALUES (:npm, :id_fakultas, :id_kelas, :status, :created_at)";

            $stmt = $pdo->prepare($sql);

            foreach ($attendanceData as $data) {
                $npm = $data['npm'];
                $id_fakultas = $data['id_fakultas'];
                $id_kelas = $data['id_kelas'];
                $status = $data['status'];
                $created_at = date("Y-m-d H:i:s");

                $stmt->execute([
                    ':npm' => $npm,
                    ':id_fakultas' => $id_fakultas,
                    ':id_kelas' => $id_kelas,
                    ':status' => $status,
                    ':created_at' => $created_at
                ]);
            }

            $response['status'] = 'success';
        } catch (PDOException $e) {
            $response['status'] = 'error';
        }
    } else {
        $response['status'] = 'error';
    }

    echo json_encode($response);
}
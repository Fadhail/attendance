<?php

require_once "../../database/koneksi.php";
require_once "../functions/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);
    $response = [];

    if ($attendanceData) {
        try {
            $sql = "INSERT INTO presensi (npm, status, created_at) VALUES (:npm, :status, :created_at)";

            $stmt = $pdo->prepare($sql);

            foreach ($attendanceData as $data) {
                $npm = $data['npm'];
                $status = $data['status'];
                $created_at = date("Y-m-d");

                $stmt->execute([
                    ':npm' => $npm,
                    ':status' => $status,
                    ':created_at' => $created_at
                ]);
            }

            $response['status'] = 'success';
            $response['message'] = "Attendance recorded successfully for all entries.";
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Error inserting attendance data: " . $e->getMessage();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "No attendance data received.";
    }

    echo json_encode($response);
}
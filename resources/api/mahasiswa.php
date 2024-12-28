<?php

require_once "../../database/koneksi.php";
require_once "../functions/functions.php";


$sql = "SELECT npm status FROM mahasiswa";

$result = fetch($sql);

$response = array();


if ($result) {
    $response['status'] = 'success';
    $response['data'] = $result;
} else {
    $response['status'] = 'error';
    $response['message'] = 'No records found';
}


header('Content-Type: application/json');
echo json_encode($response);

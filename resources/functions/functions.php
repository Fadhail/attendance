<?php
function user()
{
    if (isset($_SESSION['user'])) {
        return (object) $_SESSION['user'];
    }
    return null;
}

function getFakultas()
{
    global $pdo;
    $sql = "SELECT * FROM fakultas";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $fakultas = array();
    if ($result) {
        foreach ($result as $row) {
            $fakultas[] = $row;
        }
    }

    return $fakultas;
}

function getKelas()
{
    global $pdo;
    $sql = "SELECT * FROM kelas";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $kelas = array();
    if ($result) {
        foreach ($result as $row) {
            $kelas[] = $row;
        }
    }

    return $kelas;
}

function fetch($sql)
{
    global $pdo;
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}




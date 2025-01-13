<?php

use PHPUnit\Framework\TestCase;

class HapusKelasTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("CREATE TABLE kelas (
            id_kelas INTEGER PRIMARY KEY,
            nama_kelas TEXT,
            dosen_nidn INTEGER
        )");

        $this->pdo->exec("INSERT INTO kelas (id_kelas, nama_kelas, dosen_nidn) VALUES
            (1, 'Kelas A', 12345)");
    }

    public function testDeleteKelas()
    {
        $_GET['id'] = 1;
        ob_start();
        include '../../resources/pages/admin/hapus_kelas.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM kelas WHERE id_kelas = 1");
        $kelas = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($kelas);
    }
}
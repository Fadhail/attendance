<?php

use PHPUnit\Framework\TestCase;

class ManageKelasTest extends TestCase
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

        $this->pdo->exec("CREATE TABLE dosen (
            nidn INTEGER PRIMARY KEY,
            first_name TEXT,
            last_name TEXT
        )");

        $this->pdo->exec("INSERT INTO dosen (nidn, first_name, last_name) VALUES
            (12345, 'John', 'Doe')");
    }

    public function testAddKelas()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['addKelas'] = true;
        $_POST['id_kelas'] = 1;
        $_POST['nama_kelas'] = 'Kelas A';
        $_POST['id_dosen'] = 12345;

        ob_start();
        include 'resources/pages/admin/manage_kelas.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM kelas WHERE id_kelas = 1");
        $kelas = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(1, $kelas['id_kelas']);
    }

    public function testGetKelasList()
    {
        $this->pdo->exec("INSERT INTO kelas (id_kelas, nama_kelas, dosen_nidn) VALUES
            (1, 'Kelas A', 12345)");

        ob_start();
        include '../../resources/pages/admin/manage_kelas.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('1', $output);
        $this->assertStringContainsString('Kelas A', $output);
        $this->assertStringContainsString('John Doe', $output);
    }
}
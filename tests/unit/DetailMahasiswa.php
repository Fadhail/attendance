<?php

use PHPUnit\Framework\TestCase;

class DetailMahasiswaTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("CREATE TABLE mahasiswa (
            npm TEXT PRIMARY KEY,
            first_name TEXT,
            last_name TEXT,
            phone_no TEXT,
            email TEXT,
            id_fakultas INTEGER,
            id_kelas INTEGER
        )");

        $this->pdo->exec("CREATE TABLE fakultas (
            id_fakultas INTEGER PRIMARY KEY,
            nama_fakultas TEXT
        )");

        $this->pdo->exec("CREATE TABLE kelas (
            id_kelas INTEGER PRIMARY KEY,
            nama_kelas TEXT
        )");

        $this->pdo->exec("INSERT INTO mahasiswa (npm, first_name, last_name, phone_no, email, id_fakultas, id_kelas) VALUES
            ('12345', 'John', 'Doe', '1234567890', 'john.doe@example.com', 1, 1)");

        $this->pdo->exec("INSERT INTO fakultas (id_fakultas, nama_fakultas) VALUES
            (1, 'Fakultas Teknik')");

        $this->pdo->exec("INSERT INTO kelas (id_kelas, nama_kelas) VALUES
            (1, 'Kelas A')");
    }

    public function testGetMahasiswaDetails()
    {
        $_GET['npm'] = '12345';

        $pdo = $this->pdo;

        ob_start();
        $GLOBALS['pdo'] = $pdo; 
        include '../../resources/pages/dosen/detail_mahasiswa.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('12345', $output);
    }
}

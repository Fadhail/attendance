<?php

use PHPUnit\Framework\TestCase;

class HapusMahasiswaTest extends TestCase
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

        $this->pdo->exec("INSERT INTO mahasiswa (npm, first_name, last_name, phone_no, email, id_fakultas, id_kelas) VALUES
            ('12345', 'John', 'Doe', '1234567890', 'john.doe@example.com', 1, 1)");
    }

    public function testDeleteMahasiswa()
    {
        $_GET['id'] = '12345';
        ob_start();
        include '../../resources/pages/admin/hapus_mahasiswa.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM mahasiswa WHERE npm = '12345'");
        $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($mahasiswa);
    }
}
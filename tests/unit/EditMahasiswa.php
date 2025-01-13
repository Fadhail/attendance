<?php

use PHPUnit\Framework\TestCase;

class EditMahasiswaTest extends TestCase
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
        ob_start();
        include 'resources/pages/admin/edit_mahasiswa.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('value="12345"', $output);
    }

    public function testPostUpdateMahasiswaDetails()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['updateMahasiswa'] = true;
        $_POST['npm'] = '12345';
        $_POST['first_name'] = 'Jane';
        $_POST['last_name'] = 'Smith';
        $_POST['phone_no'] = '0987654321';
        $_POST['email'] = 'jane.smith@example.com';
        $_POST['id_fakultas'] = 1;
        $_POST['id_kelas'] = 1;

        ob_start();
        include '../../resources/pages/admin/edit_mahasiswa.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM mahasiswa WHERE npm = '12345'");
        $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Jane', $mahasiswa['first_name']);
    }
}
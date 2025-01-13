<?php

use PHPUnit\Framework\TestCase;

class ManageMahasiswaTest extends TestCase
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
            images TEXT,
            id_fakultas INTEGER,
            id_kelas INTEGER,
            created_at TEXT
        )");

        $this->pdo->exec("CREATE TABLE fakultas (
            id_fakultas INTEGER PRIMARY KEY,
            nama_fakultas TEXT
        )");

        $this->pdo->exec("CREATE TABLE kelas (
            id_kelas INTEGER PRIMARY KEY,
            nama_kelas TEXT
        )");

        $this->pdo->exec("INSERT INTO fakultas (id_fakultas, nama_fakultas) VALUES
            (1, 'Fakultas Teknik')");

        $this->pdo->exec("INSERT INTO kelas (id_kelas, nama_kelas) VALUES
            (1, 'Kelas A')");
    }

    public function testAddMahasiswa()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['tambahMahasiswa'] = true;
        $_POST['npm'] = '12345';
        $_POST['first_name'] = 'John';
        $_POST['last_name'] = 'Doe';
        $_POST['phone_no'] = '1234567890';
        $_POST['email'] = 'john.doe@example.com';
        $_POST['id_fakultas'] = 1;
        $_POST['id_kelas'] = 1;
        $_POST['capturedImage1'] = 'data:image/png;base64,' . base64_encode('image1');
        $_POST['capturedImage2'] = 'data:image/png;base64,' . base64_encode('image2');
        $_POST['capturedImage3'] = 'data:image/png;base64,' . base64_encode('image3');
        $_POST['capturedImage4'] = 'data:image/png;base64,' . base64_encode('image4');
        $_POST['capturedImage5'] = 'data:image/png;base64,' . base64_encode('image5');

        ob_start();
        include '../../resources/pages/admin/manage_mahasiswa.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM mahasiswa WHERE npm = '12345'");
        $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('12345', $mahasiswa['npm']);
    }

    public function testGetMahasiswaList()
    {
        $this->pdo->exec("INSERT INTO mahasiswa (npm, first_name, last_name, phone_no, email, images, id_fakultas, id_kelas, created_at) VALUES
            ('12345', 'John', 'Doe', '1234567890', 'john.doe@example.com', '[]', 1, 1, '2023-01-01')");

        ob_start();
        include '../../resources/pages/admin/manage_mahasiswa.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('12345', $output);
    }
}
<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $host = "localhost";
        $username = "root";
        $password = "";
        $db = "attendance";

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->fail("Connection failed: " . $e->getMessage());
        }
    }

    public function testDatabaseConnection()
    {
        $this->assertInstanceOf(PDO::class, $this->pdo);
    }

    public function testInsertOperation()
    {
        $sql = "INSERT INTO admin (first_name, last_name, email, password) VALUES ('John', 'Doe', 'john.doe@example.com', 'password')";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM admin WHERE email = 'john.doe@example.com'";
        $stmt = $this->pdo->query($sql);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($admin);
        $this->assertEquals('John', $admin['first_name']);
    }

    public function testUpdateOperation()
    {
        $sql = "UPDATE admin SET first_name = 'Jane' WHERE email = 'john.doe@example.com'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM admin WHERE email = 'john.doe@example.com'";
        $stmt = $this->pdo->query($sql);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Jane', $admin['first_name']);
    }

    public function testDeleteOperation()
    {
        $sql = "DELETE FROM admin WHERE email = 'john.doe@example.com'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM admin WHERE email = 'john.doe@example.com'";
        $stmt = $this->pdo->query($sql);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEmpty($admin);
    }

    public function testInsertDosen()
    {
        $sql = "INSERT INTO dosen (nidn, first_name, last_name, phone_no, email, password) VALUES (123456, 'Jane', 'Doe', '1234567890', 'jane.doe@example.com', 'password')";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM dosen WHERE email = 'jane.doe@example.com'";
        $stmt = $this->pdo->query($sql);
        $dosen = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($dosen);
        $this->assertEquals('Jane', $dosen['first_name']);
    }

    public function testUpdateDosen()
    {
        $sql = "UPDATE dosen SET first_name = 'John' WHERE email = 'jane.doe@example.com'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM dosen WHERE email = 'jane.doe@example.com'";
        $stmt = $this->pdo->query($sql);
        $dosen = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('John', $dosen['first_name']);
    }

    public function testDeleteDosen()
    {
        $sql = "DELETE FROM dosen WHERE email = 'jane.doe@example.com'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM dosen WHERE email = 'jane.doe@example.com'";
        $stmt = $this->pdo->query($sql);
        $dosen = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEmpty($dosen);
    }

    public function testInsertFakultas()
    {
        $sql = "INSERT INTO fakultas (nama_fakultas) VALUES ('Fakultas Teknik')";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM fakultas WHERE nama_fakultas = 'Fakultas Teknik'";
        $stmt = $this->pdo->query($sql);
        $fakultas = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($fakultas);
        $this->assertEquals('Fakultas Teknik', $fakultas['nama_fakultas']);
    }

    public function testUpdateFakultas()
    {
        $sql = "UPDATE fakultas SET nama_fakultas = 'Fakultas Sains' WHERE nama_fakultas = 'Fakultas Teknik'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM fakultas WHERE nama_fakultas = 'Fakultas Sains'";
        $stmt = $this->pdo->query($sql);
        $fakultas = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Fakultas Sains', $fakultas['nama_fakultas']);
    }

    public function testDeleteFakultas()
    {
        $sql = "DELETE FROM fakultas WHERE nama_fakultas = 'Fakultas Sains'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM fakultas WHERE nama_fakultas = 'Fakultas Sains'";
        $stmt = $this->pdo->query($sql);
        $fakultas = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEmpty($fakultas);
    }

    public function testInsertKelas()
    {
        $sql = "INSERT INTO kelas (id_kelas, nama_kelas, dosen_nidn) VALUES ('123', 'Kelas A', 1234)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM kelas WHERE nama_kelas = 'Kelas A'";
        $stmt = $this->pdo->query($sql);
        $kelas = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($kelas);
        $this->assertEquals('Kelas A', $kelas['nama_kelas']);
    }

    public function testUpdateKelas()
    {
        $sql = "UPDATE kelas SET nama_kelas = 'Kelas B' WHERE nama_kelas = 'Kelas A'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

    }

    public function testDeleteKelas()
    {
        $sql = "DELETE FROM kelas WHERE nama_kelas = 'Kelas B'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM kelas WHERE nama_kelas = 'Kelas B'";
        $stmt = $this->pdo->query($sql);
        $kelas = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEmpty($kelas);
    }

    public function testInsertMahasiswa()
    {
        $sql = "INSERT INTO mahasiswa (npm, first_name, last_name, phone_no, email, id_fakultas, id_kelas) VALUES (123456789, 'John', 'Doe', '1234567890', 'john.doe@student.example.com', 333, 44)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM mahasiswa WHERE email = 'john.doe@student.example.com'";
        $stmt = $this->pdo->query($sql);
        $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($mahasiswa);
        $this->assertEquals('John', $mahasiswa['first_name']);
    }

    public function testUpdateMahasiswa()
    {
        $sql = "UPDATE mahasiswa SET first_name = 'Jane' WHERE email = 'john.doe@student.example.com'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);
    }

    public function testDeleteMahasiswa()
    {
        $sql = "DELETE FROM mahasiswa WHERE email = 'john.doe@student.example.com'";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM mahasiswa WHERE email = 'john.doe@student.example.com'";
        $stmt = $this->pdo->query($sql);
        $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEmpty($mahasiswa);
    }

    public function testInsertPresensi()
    {
        $sql = "INSERT INTO presensi (npm, id_fakultas, id_kelas, status) VALUES (123456789, 1, 1, 'Hadir')";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM presensi WHERE npm = 123456789";
        $stmt = $this->pdo->query($sql);
        $presensi = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($presensi);
        $this->assertEquals('Hadir', $presensi['status']);
    }

    public function testUpdatePresensi()
    {
        $sql = "UPDATE presensi SET status = 'Tidak Hadir' WHERE npm = 123456789";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM presensi WHERE npm = 123456789";
        $stmt = $this->pdo->query($sql);
        $presensi = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Tidak Hadir', $presensi['status']);
    }

    public function testDeletePresensi()
    {
        $sql = "DELETE FROM presensi WHERE npm = 123456789";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        $this->assertTrue($result);

        $sql = "SELECT * FROM presensi WHERE npm = 123456789";
        $stmt = $this->pdo->query($sql);
        $presensi = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEmpty($presensi);
    }
}
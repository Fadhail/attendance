<?php

use PHPUnit\Framework\TestCase;

class HomeAdminTest extends TestCase
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
            email TEXT
        )");

        $this->pdo->exec("CREATE TABLE dosen (
            nidn TEXT PRIMARY KEY,
            first_name TEXT,
            last_name TEXT,
            phone_no TEXT,
            email TEXT
        )");

        $this->pdo->exec("CREATE TABLE kelas (
            id_kelas INTEGER PRIMARY KEY,
            nama_kelas TEXT
        )");

        $this->pdo->exec("CREATE TABLE fakultas (
            id_fakultas INTEGER PRIMARY KEY,
            nama_fakultas TEXT
        )");

        $this->pdo->exec("INSERT INTO mahasiswa (npm, first_name, last_name, phone_no, email) VALUES
            ('12345', 'John', 'Doe', '1234567890', 'john.doe@example.com')");

        $this->pdo->exec("INSERT INTO dosen (nidn, first_name, last_name, phone_no, email) VALUES
            ('67890', 'Jane', 'Smith', '0987654321', 'jane.smith@example.com')");

        $this->pdo->exec("INSERT INTO kelas (id_kelas, nama_kelas) VALUES
            (1, 'Kelas A')");

        $this->pdo->exec("INSERT INTO fakultas (id_fakultas, nama_fakultas) VALUES
            (1, 'Fakultas Teknik')");
    }

    public function testDashboardDataFetching()
    {
        ob_start();
        include '../../resources/pages/admin/home.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Total Mahasiswa', $output);
    }
}
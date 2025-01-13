<?php

use PHPUnit\Framework\TestCase;

class ManageDosenTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("CREATE TABLE dosen (
            nidn TEXT PRIMARY KEY,
            first_name TEXT,
            last_name TEXT,
            phone_no TEXT,
            email TEXT,
            password TEXT,
            created_at TEXT
        )");
    }

    public function testAddDosen()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['addLecture'] = true;
        $_POST['nidn'] = '12345';
        $_POST['first_name'] = 'John';
        $_POST['last_name'] = 'Doe';
        $_POST['phone_no'] = '1234567890';
        $_POST['email'] = 'john.doe@example.com';
        $_POST['password'] = 'password123';

        ob_start();
        include '../../esources/pages/admin/manage_dosen.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM dosen WHERE nidn = '12345'");
        $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('12345', $dosen['nidn']);
    }

    public function testGetDosenList()
    {
        $this->pdo->exec("INSERT INTO dosen (nidn, first_name, last_name, phone_no, email, password, created_at) VALUES
            ('12345', 'John', 'Doe', '1234567890', 'john.doe@example.com', 'password123', '2023-01-01')");

        ob_start();
        include 'resources/pages/admin/manage_dosen.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('12345', $output);
    }
}
<?php

use PHPUnit\Framework\TestCase;

class EditDosenTest extends TestCase
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
            email TEXT
        )");

        $this->pdo->exec("INSERT INTO dosen (nidn, first_name, last_name, phone_no, email) VALUES
            ('12345', 'John', 'Doe', '1234567890', 'john.doe@example.com')");
    }

    public function testGetDosenDetails()
    {
        $_GET['nidn'] = '12345';
        ob_start();
        include '../../resources/pages/admin/edit_dosen.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('value="12345"', $output);
    }

    public function testPostUpdateDosenDetails()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['updateDosen'] = true;
        $_POST['nidn'] = '12345';
        $_POST['first_name'] = 'Jane';
        $_POST['last_name'] = 'Smith';
        $_POST['phone_no'] = '0987654321';
        $_POST['email'] = 'jane.smith@example.com';

        ob_start();
        include '../../resources/pages/admin/edit_dosen.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM dosen WHERE nidn = '12345'");
        $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Jane', $dosen['first_name']);
    }
}
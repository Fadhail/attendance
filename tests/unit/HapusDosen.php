<?php

use PHPUnit\Framework\TestCase;

class HapusDosenTest extends TestCase
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

    public function testDeleteDosen()
    {
        $_GET['nidn'] = '12345';
        ob_start();
        include '../../resources/pages/admin/hapus_dosen.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM dosen WHERE nidn = '12345'");
        $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($dosen);
    }
}
<?php

use PHPUnit\Framework\TestCase;

class PresensiTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("CREATE TABLE presensi (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            npm TEXT,
            id_fakultas INTEGER,
            id_kelas INTEGER,
            status TEXT,
            created_at TEXT
        )");
    }

    public function testRecordPresensi()
    {
        $_POST['attendanceData'] = json_encode([
            [
                'npm' => '12345',
                'id_fakultas' => 1,
                'id_kelas' => 1,
                'status' => 'Hadir'
            ]
        ]);

        ob_start();
        include '../../resources/pages/dosen/presensi.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM presensi");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(1, $result);
    }
}
<?php

use PHPUnit\Framework\TestCase;

class HapusFakultasTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("CREATE TABLE fakultas (
            id_fakultas INTEGER PRIMARY KEY,
            nama_fakultas TEXT
        )");

        $this->pdo->exec("INSERT INTO fakultas (id_fakultas, nama_fakultas) VALUES
            (1, 'Fakultas Teknik')");
    }

    public function testDeleteFakultas()
    {
        $_GET['id'] = 1;
        ob_start();
        include '../../resources/pages/admin/hapus_fakultas.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM fakultas WHERE id_fakultas = 1");
        $fakultas = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($fakultas);
    }
}
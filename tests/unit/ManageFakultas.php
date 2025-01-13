<?php

use PHPUnit\Framework\TestCase;

class ManageFakultasTest extends TestCase
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
    }

    public function testAddFakultas()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['addFakultas'] = true;
        $_POST['id_fakultas'] = '1';
        $_POST['nama_fakultas'] = 'Fakultas Teknik';

        ob_start();
        include '../../resources/pages/admin/manage_fakultas.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM fakultas WHERE id_fakultas = '1'");
        $fakultas = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('1', $fakultas['id_fakultas']);
        $this->assertEquals('Fakultas Teknik', $fakultas['nama_fakultas']);
    }

    public function testGetFakultasList()
    {
        $this->pdo->exec("INSERT INTO fakultas (id_fakultas, nama_fakultas) VALUES
            ('1', 'Fakultas Teknik')");

        ob_start();
        include '../../resources/pages/admin/manage_fakultas.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('1', $output);
        $this->assertStringContainsString('Fakultas Teknik', $output);
    }
}
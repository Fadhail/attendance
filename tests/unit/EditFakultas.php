<?php

use PHPUnit\Framework\TestCase;

class EditFakultasTest extends TestCase
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

    public function testGetFakultasDetails()
    {
        $_GET['id'] = 1;
        ob_start();
        include '../../resources/pages/admin/edit_fakultas.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('value="1"', $output);
        $this->assertStringContainsString('value="Fakultas Teknik"', $output);
    }

    public function testPostUpdateFakultasDetails()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['updateFakultas'] = true;
        $_POST['id_fakultas'] = 1;
        $_POST['nama_fakultas'] = 'Fakultas Sains';

        ob_start();
        include '../../resources/pages/admin/edit_fakultas.php';
        ob_end_clean();

        $stmt = $this->pdo->query("SELECT * FROM fakultas WHERE id_fakultas = 1");
        $fakultas = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Fakultas Sains', $fakultas['nama_fakultas']);
    }
}
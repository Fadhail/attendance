<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../resources/functions/functions.php';


#[CoversClass(Functions::class)]
class FunctionsTest extends TestCase
{
    public function testUser()
    {
        $_SESSION['user'] = ['id' => 1, 'name' => 'John Doe'];
        $user = user();
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user->name);

        unset($_SESSION['user']);
        $user = user();
        $this->assertNull($user);
    }

    public function testGetFakultas()
    {
        global $pdo;
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('query')->willReturn($stmt);
        $stmt->method('fetchAll')->willReturn([
            ['id_fakultas' => 1, 'nama_fakultas' => 'Fakultas A'],
            ['id_fakultas' => 2, 'nama_fakultas' => 'Fakultas B']
        ]);

        $fakultas = getFakultas();
        $this->assertCount(2, $fakultas);
        $this->assertEquals('Fakultas A', $fakultas[0]['nama_fakultas']);
    }

    public function testGetKelas()
    {
        global $pdo;
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('query')->willReturn($stmt);
        $stmt->method('fetchAll')->willReturn([
            ['id_kelas' => 1, 'nama_kelas' => 'Kelas A'],
            ['id_kelas' => 2, 'nama_kelas' => 'Kelas B']
        ]);

        $kelas = getKelas();
        $this->assertCount(2, $kelas);
        $this->assertEquals('Kelas A', $kelas[0]['nama_kelas']);
    }

    public function testFetch()
    {
        global $pdo;
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('query')->willReturn($stmt);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe']
        ]);

        $result = fetch('SELECT * FROM users');
        $this->assertCount(2, $result);
        $this->assertEquals('John Doe', $result[0]['name']);
    }
    public function testGetFakultasEdgeCases()
    {
        global $pdo;
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('query')->willReturn($stmt);
        $stmt->method('fetchAll')->willReturn([]);

        $fakultas = getFakultas();
        $this->assertCount(0, $fakultas);
    }

    public function testGetKelasEdgeCases()
    {
        global $pdo;
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('query')->willReturn($stmt);
        $stmt->method('fetchAll')->willReturn([]);

        $kelas = getKelas();
        $this->assertCount(0, $kelas);
    }

    public function testFetchEdgeCases()
    {
        global $pdo;
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->method('query')->willReturn($stmt);
        $stmt->method('fetchAll')->willReturn([]);

        $result = fetch('SELECT * FROM non_existent_table');
        $this->assertCount(0, $result);
    }
}
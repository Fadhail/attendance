<?php

use PHPUnit\Framework\TestCase;

#[CoversClass(Database::class)]
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
}
